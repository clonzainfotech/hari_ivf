<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Re-base every date in the database forward by one fixed whole-day offset so the
 * most-recent clinical activity lands on today, keeping all relative gaps intact.
 *
 * Environment-agnostic: targets whatever DB the current .env points at. Runs the
 * same on local and live. Destructive and irreversible — preview with --dry-run.
 */
class ShiftDates extends Command
{
    protected $signature = 'data:shift-dates
        {--days= : Override the computed offset (whole days). Skips anchor computation.}
        {--dry-run : Show what would change (counts + before/after samples); write nothing.}
        {--force : Skip the interactive confirmation prompt.}
        {--only= : Limit scope to "typed" or "json" (default: both).}';

    protected $description = 'Shift all dates forward so the newest data lands on today (typed columns + clinical JSON).';

    /** Clinical "activity" tables whose newest created_at/updated_at defines "now". */
    private const ANCHOR_TABLES = [
        'ivf_history', 'iui_history', 'ivf_plan_report', 'ivf_plan_od_report',
        'ivf_result_reviews', 'ivf_transfer_reports', 'iui_reports', 'hsa_reports',
        'semen_freezings', 'embryo_discards', 'semen_discards', 'pickup_discharge',
        'indoor_setup_history', 'patients_memory', 'anc_history', 'appointments',
    ];

    /** Structured-JSON columns whose embedded date strings must also shift. */
    private const JSON_COLUMNS = [
        'ivf_history' => 'description', 'iui_history' => 'description',
        'iui_reports' => 'description', 'ivf_plan_report' => 'description',
        'ivf_plan_od_report' => 'description', 'ivf_result_reviews' => 'description',
        'ivf_transfer_reports' => 'description', 'hsa_reports' => 'description',
        'semen_freezings' => 'description', 'patients_memory' => 'description',
        'embryo_discards' => 'description', 'semen_discards' => 'description',
        'pickup_discharge' => 'medicinedata', 'indoor_setup_history' => 'new_data',
    ];

    /**
     * Date(+time) formats the app stores inside JSON, most-specific first.
     * NO bare-time formats — so "09:49 am" / "1.00 pm" can never match.
     */
    private const FORMATS = [
        'd M Y h:i A',   // 18 May 2026 05:41 PM
        'l d F Y',       // Wednesday 11 March 2020
        'D d M Y',       // Fri 24 Apr 2020
        'Y-m-d H:i:s',   // 2026-05-19 10:00:00
        'Y-m-d',         // 2020-03-18
        'd-m-Y',
        'd-M-Y',
        // Slash dates: the app stores these as m/d/Y (US). Do NOT add 'd/m/Y' —
        // it is ambiguous with m/d/Y and mis-shifts ambiguous values (see the
        // data:fix-slash-dates incident). m/d/Y only.
        'm/d/Y',
    ];

    /** @var string resolved target schema name */
    private $db;
    /** @var string|null resolved anchor timestamp (for reporting) */
    private $anchorDate = null;

    public function handle()
    {
        $conn = config('database.default');
        $this->db = config("database.connections.$conn.database");

        // 1. Resolve the offset N.
        if ($this->option('days') !== null && $this->option('days') !== '') {
            $n = (int) $this->option('days');
        } else {
            $n = $this->computeN();
        }

        // 2. Idempotency / no-op guard.
        if ($n <= 0) {
            $this->error("Computed offset is {$n} day(s) — nothing to do.");
            $this->line("The newest clinical activity is already at (or after) today" .
                ($this->anchorDate ? " (anchor: {$this->anchorDate})" : '') . ".");
            $this->line("If you intend to shift anyway, pass --days=<n> explicitly.");
            return self::SUCCESS;
        }

        $dryRun = (bool) $this->option('dry-run');
        $only = $this->option('only');

        // 3. Show what we're about to do.
        $this->info("Target database : {$this->db}");
        $this->info("Anchor (newest) : " . ($this->anchorDate ?: '(--days override)'));
        $this->info("Offset (N)      : +{$n} day(s)");
        $this->info("Today           : " . Carbon::today()->toDateString());
        $this->info("Mode            : " . ($dryRun ? 'DRY-RUN (no writes)' : 'LIVE WRITE'));

        // 4. Confirm.
        if (!$dryRun && !$this->option('force')) {
            if (!$this->confirm("Shift ALL dates in '{$this->db}' forward by {$n} day(s)? This is NOT reversible and double-runs double-shift.")) {
                $this->line('Aborted.');
                return self::SUCCESS;
            }
        }

        // 5. Typed columns.
        if ($only !== 'json') {
            $this->line('');
            $this->info('== Phase 1: typed date/datetime/timestamp columns ==');
            $this->shiftTypedColumns($n, $dryRun);
        }

        // 6. JSON columns.
        if ($only !== 'typed') {
            $this->line('');
            $this->info('== Phase 2: dates inside clinical JSON ==');
            $this->shiftJsonColumns($n, $dryRun);
        }

        $this->line('');
        $this->info($dryRun ? 'Dry-run complete. No changes written.' : 'Done.');
        return self::SUCCESS;
    }

    /* ---------------------------------------------------------------------- */
    /* Offset                                                                  */
    /* ---------------------------------------------------------------------- */

    private function computeN(): int
    {
        $cols = DB::select("
            SELECT TABLE_NAME, COLUMN_NAME
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = ?
              AND COLUMN_NAME IN ('created_at','updated_at')
              AND DATA_TYPE IN ('datetime','timestamp')
              AND TABLE_NAME IN ('" . implode("','", self::ANCHOR_TABLES) . "')
        ", [$this->db]);

        $selects = [];
        foreach ($cols as $c) {
            $t = '`' . str_replace('`', '', $c->TABLE_NAME) . '`';
            $col = '`' . str_replace('`', '', $c->COLUMN_NAME) . '`';
            $selects[] = "SELECT MAX($col) AS m FROM $t";
        }
        if (!$selects) {
            throw new \RuntimeException('Could not determine a clinical anchor (no anchor tables found).');
        }

        $anchor = DB::select("SELECT MAX(m) AS anchor FROM (" . implode(' UNION ALL ', $selects) . ") u")[0]->anchor ?? null;
        if (!$anchor) {
            throw new \RuntimeException('Could not determine a clinical anchor (no activity timestamps).');
        }
        $this->anchorDate = $anchor;

        // Whole days between the anchor's date and today (signed).
        return (int) Carbon::parse($anchor)->startOfDay()->diffInDays(Carbon::today(), false);
    }

    /* ---------------------------------------------------------------------- */
    /* Phase 1: typed columns                                                  */
    /* ---------------------------------------------------------------------- */

    private function shiftTypedColumns(int $n, bool $dryRun): void
    {
        $rows = DB::select("
            SELECT TABLE_NAME, COLUMN_NAME, DATA_TYPE
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = ?
              AND DATA_TYPE IN ('date','datetime','timestamp')
              AND EXTRA NOT LIKE '%VIRTUAL GENERATED%'
              AND EXTRA NOT LIKE '%STORED GENERATED%'
            ORDER BY TABLE_NAME, ORDINAL_POSITION
        ", [$this->db]);

        $byTable = [];
        foreach ($rows as $r) {
            $byTable[$r->TABLE_NAME][] = $r;
        }

        $totalRows = 0;
        foreach ($byTable as $table => $cols) {
            $tq = '`' . str_replace('`', '', $table) . '`';
            $sets = [];
            $nonNull = [];
            foreach ($cols as $c) {
                $col = '`' . str_replace('`', '', $c->COLUMN_NAME) . '`';
                $zero = $c->DATA_TYPE === 'date' ? "'0000-00-00'" : "'0000-00-00 00:00:00'";
                $sets[] = "$col = CASE WHEN $col IS NULL OR CAST($col AS CHAR) = $zero THEN $col ELSE DATE_ADD($col, INTERVAL ? DAY) END";
                $nonNull[] = "($col IS NOT NULL AND CAST($col AS CHAR) <> $zero)";
            }
            $where = '(' . implode(' OR ', $nonNull) . ')';

            if ($dryRun) {
                $cnt = (int) DB::table($table)->whereRaw($where)->count();
                $totalRows += $cnt;
                $this->line(sprintf('  [dry] %-40s %6d row(s), %d col(s)', $table, $cnt, count($cols)));
                $this->sampleTyped($table, $cols, $n, $where);
                continue;
            }

            $sql = "UPDATE $tq SET " . implode(', ', $sets) . " WHERE $where";
            $bindings = array_fill(0, count($sets), $n);
            $affected = DB::transaction(fn () => DB::update($sql, $bindings));
            $totalRows += $affected;
            $this->line(sprintf('  %-40s %6d row(s) shifted', $table, $affected));
        }

        $this->info(sprintf('  Phase 1 total: %d row-updates across %d table(s).', $totalRows, count($byTable)));
    }

    private function sampleTyped(string $table, array $cols, int $n, string $where): void
    {
        // Pick a representative non-null column for the sample (avoids always showing deleted_at).
        $col = $cols[0]->COLUMN_NAME;
        foreach ($cols as $c) {
            if (!in_array($c->COLUMN_NAME, ['deleted_at'], true)) { $col = $c->COLUMN_NAME; break; }
        }
        try {
            $row = DB::table($table)
                ->whereRaw("`$col` IS NOT NULL")
                ->first([DB::raw("`$col` AS oldv"), DB::raw("DATE_ADD(`$col`, INTERVAL $n DAY) AS newv")]);
            if ($row && $row->oldv !== null) {
                $this->line(sprintf('        e.g. %s.%s: %s -> %s', $table, $col, $row->oldv, $row->newv));
            }
        } catch (\Throwable $e) {
            // Sampling is best-effort only; never let it abort the run.
        }
    }

    /* ---------------------------------------------------------------------- */
    /* Phase 2: JSON columns                                                   */
    /* ---------------------------------------------------------------------- */

    private function shiftJsonColumns(int $n, bool $dryRun): void
    {
        foreach (self::JSON_COLUMNS as $table => $col) {
            if (!Schema::hasTable($table) || !Schema::hasColumn($table, $col)) {
                $this->line(sprintf('  skip %s.%s (missing)', $table, $col));
                continue;
            }
            $this->shiftJsonColumn($table, $col, $n, $dryRun);
        }
    }

    private function shiftJsonColumn(string $table, string $col, int $n, bool $dryRun): void
    {
        $changed = 0;
        $sampleShown = false;

        DB::table($table)->select('id', $col)
            ->whereNotNull($col)->where($col, '<>', '')
            ->orderBy('id')
            ->chunkById(500, function ($rows) use ($table, $col, $n, $dryRun, &$changed, &$sampleShown) {
                foreach ($rows as $row) {
                    $raw = $row->{$col};
                    $data = json_decode($raw, true);
                    if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
                        continue; // not JSON
                    }
                    $new = $this->shiftJsonValue($data, $n);
                    $encoded = json_encode($new);
                    if ($encoded === false || $encoded === $raw) {
                        continue;
                    }
                    $changed++;
                    if ($dryRun) {
                        if (!$sampleShown) {
                            $this->line(sprintf('  [dry] %s.%s id=%d', $table, $col, $row->id));
                            $this->line('        old: ' . $this->snippet($raw));
                            $this->line('        new: ' . $this->snippet($encoded));
                            $sampleShown = true;
                        }
                        continue;
                    }
                    DB::table($table)->where('id', $row->id)->update([$col => $encoded]);
                }
            }, 'id');

        $this->line(sprintf('  %-40s %6d row(s) %s', "$table.$col", $changed, $dryRun ? 'would change' : 'changed'));
    }

    /**
     * Recursively shift any string leaf that is an exact, round-trip-valid date.
     */
    private function shiftJsonValue($node, int $n)
    {
        if (is_array($node)) {
            foreach ($node as $k => $v) {
                $node[$k] = $this->shiftJsonValue($v, $n);
            }
            return $node;
        }
        if (!is_string($node)) {
            return $node;
        }
        $s = trim($node);
        if ($s === '') {
            return $node;
        }

        foreach (self::FORMATS as $fmt) {
            $dt = \DateTime::createFromFormat('!' . $fmt, $s);
            $errors = \DateTime::getLastErrors();
            if ($dt === false || ($errors && ($errors['warning_count'] || $errors['error_count']))) {
                continue;
            }
            // Strict round-trip: only shift if it reproduces the original exactly.
            if ($dt->format($fmt) !== $s) {
                continue;
            }
            return (clone $dt)->modify("+{$n} day")->format($fmt);
        }
        return $node;
    }

    private function snippet(string $s): string
    {
        $s = preg_replace('/\s+/', ' ', $s);
        return strlen($s) > 160 ? substr($s, 0, 160) . '…' : $s;
    }
}
