<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * One-shot repair for slash-format dates corrupted by data:shift-dates.
 *
 * The app stores slash dates as m/d/Y, but the original shift tried d/m/Y first,
 * so ambiguous values (both parts <= 12) were shifted with the wrong interpretation.
 * The shift is invertible, so per slash-date value we:
 *   1. reverse the buggy shift (-N, trying d/m/Y then m/d/Y — the old order), and
 *   2. redo it correctly (+N as m/d/Y).
 * Correctly-shifted (unambiguous) values come out unchanged; corrupted ones are fixed.
 *
 * RUN EXACTLY ONCE. It is NOT idempotent (a second run re-corrupts), so it guards
 * with a confirmation prompt and --dry-run.
 */
class FixSlashDates extends Command
{
    protected $signature = 'data:fix-slash-dates
        {--days=22 : The offset (N) that the original shift applied.}
        {--dry-run : Show before/after samples and counts; write nothing.}
        {--force : Skip the confirmation prompt.}';

    protected $description = 'Repair slash-format clinical dates corrupted by data:shift-dates (reverse d/m/Y, redo m/d/Y).';

    /** Same JSON columns the shift touched. */
    private const JSON_COLUMNS = [
        'ivf_history' => 'description', 'iui_history' => 'description',
        'iui_reports' => 'description', 'ivf_plan_report' => 'description',
        'ivf_plan_od_report' => 'description', 'ivf_result_reviews' => 'description',
        'ivf_transfer_reports' => 'description', 'hsa_reports' => 'description',
        'semen_freezings' => 'description', 'patients_memory' => 'description',
        'embryo_discards' => 'description', 'semen_discards' => 'description',
        'pickup_discharge' => 'medicinedata', 'indoor_setup_history' => 'new_data',
    ];

    /** @var int */
    private $n;
    private $changed = 0;
    private $samples = [];

    public function handle()
    {
        $this->n = (int) $this->option('days');
        $dryRun = (bool) $this->option('dry-run');

        if ($this->n <= 0) {
            $this->error('--days must be a positive integer (the offset the shift applied).');
            return self::FAILURE;
        }

        $conn = config('database.default');
        $db = config("database.connections.$conn.database");

        $this->info("Target database : {$db}");
        $this->info("Offset (N)      : {$this->n}");
        $this->info("Mode            : " . ($dryRun ? 'DRY-RUN (no writes)' : 'LIVE WRITE'));
        $this->warn('This repairs slash-format dates ONLY. Run exactly once (not idempotent).');

        if (!$dryRun && !$this->option('force')) {
            if (!$this->confirm("Repair slash-format dates in '{$db}'?")) {
                $this->line('Aborted.');
                return self::SUCCESS;
            }
        }

        foreach (self::JSON_COLUMNS as $table => $col) {
            if (!Schema::hasTable($table) || !Schema::hasColumn($table, $col)) {
                continue;
            }
            $this->processColumn($table, $col, $dryRun);
        }

        $this->line('');
        $this->info(($dryRun ? '[dry-run] ' : '') . "Slash-date values fixed: {$this->changed}");
        if ($this->samples) {
            $this->line('Samples (before -> after):');
            foreach ($this->samples as $s) {
                $this->line('  ' . $s);
            }
        }
        return self::SUCCESS;
    }

    private function processColumn(string $table, string $col, bool $dryRun): void
    {
        $colChanged = 0;
        DB::table($table)->select('id', $col)
            ->whereNotNull($col)->where($col, '<>', '')
            ->orderBy('id')
            ->chunkById(500, function ($rows) use ($table, $col, $dryRun, &$colChanged) {
                foreach ($rows as $row) {
                    $raw = $row->{$col};
                    $data = json_decode($raw, true);
                    if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
                        continue;
                    }
                    $new = $this->fixNode($data);
                    $encoded = json_encode($new);
                    if ($encoded === false || $encoded === $raw) {
                        continue;
                    }
                    $colChanged++;
                    if (!$dryRun) {
                        DB::table($table)->where('id', $row->id)->update([$col => $encoded]);
                    }
                }
            }, 'id');

        $this->line(sprintf('  %-38s %6d row(s) %s', "$table.$col", $colChanged, $dryRun ? 'would change' : 'changed'));
    }

    private function fixNode($node)
    {
        if (is_array($node)) {
            foreach ($node as $k => $v) {
                $node[$k] = $this->fixNode($v);
            }
            return $node;
        }
        if (!is_string($node)) {
            return $node;
        }
        // Only touch bare slash dates: NN/NN/NNNN.
        if (!preg_match('#^[0-9]{1,2}/[0-9]{1,2}/[0-9]{4}$#', trim($node))) {
            return $node;
        }
        $s = trim($node);

        // 1. Reverse the buggy shift using the OLD order (d/m/Y first, then m/d/Y).
        $orig = $this->applyFormats($s, -$this->n, ['d/m/Y', 'm/d/Y']);
        if ($orig === $s) {
            return $node; // couldn't reverse (not a clean slash date) — leave as-is
        }
        // 2. Redo correctly as m/d/Y (the app's canonical slash format).
        $correct = $this->applyFormats($orig, $this->n, ['m/d/Y']);

        if ($correct !== $s) {
            $this->changed++;
            if (count($this->samples) < 12) {
                $this->samples[] = "$s -> $correct";
            }
            return $correct;
        }
        return $node;
    }

    /**
     * Shift $s by $n days using the first format that round-trips exactly.
     * Returns $s unchanged if no format matches.
     */
    private function applyFormats(string $s, int $n, array $fmts): string
    {
        foreach ($fmts as $fmt) {
            $dt = \DateTime::createFromFormat('!' . $fmt, $s);
            $errors = \DateTime::getLastErrors();
            if ($dt === false || ($errors && ($errors['warning_count'] || $errors['error_count']))) {
                continue;
            }
            if ($dt->format($fmt) !== $s) {
                continue;
            }
            $sign = $n >= 0 ? '+' : '';
            return (clone $dt)->modify("{$sign}{$n} day")->format($fmt);
        }
        return $s;
    }
}
