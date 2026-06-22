<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;

/**
 * Copy a small, referentially-complete subset of the live database into a fresh
 * database. Picks ~50-60 real patients chosen (greedy set-cover) so that every
 * clinical module, status, cycle-stage and plan is represented, then copies those
 * patients plus every child row that belongs to them, plus all reference/lookup
 * tables in full.
 *
 * The SOURCE database (the one the current .env points at) is only ever read from.
 * The TARGET database is dropped and recreated. Schema is cloned from the live DB
 * via mysqldump --no-data (NOT replayed from migrations, which have drifted).
 *
 * Source and target must live on the same MySQL server (cross-database
 * INSERT ... SELECT is used for speed).
 */
class CopySubset extends Command
{
    protected $signature = 'data:copy-subset
        {--target=candor_sample : Name of the new database to create and fill.}
        {--count=55 : Number of patients to keep (kept within 50-60).}
        {--dry-run : Show the patient selection + coverage matrix; create/copy nothing.}
        {--no-create : Do not DROP/CREATE the target DB (it must already exist). Use when the DB user lacks CREATE/DROP DATABASE privilege.}
        {--force : Skip the interactive confirmation prompt.}';

    protected $description = 'Clone a curated 50-60 patient subset (with all related + reference data) into a fresh database.';

    /** Transient / log / archive tables: schema is cloned but NO data is copied. */
    private const SKIP_DATA = [
        'migrations', 'sessions', 'jobs', 'failed_jobs', 'password_resets',
        'password_reset_tokens', 'cache', 'cache_locks', 'notifications',
        'appointments_archive',
    ];

    /** @var string */
    private $source;
    /** @var string */
    private $target;
    /** @var array<string,array<string>> table => [columns] */
    private $cols = [];
    /** @var array<string,string> table => primary key column */
    private $pk = [];
    /** @var array<int> chosen patient ids */
    private $patientIds = [];

    public function handle()
    {
        $conn = config('database.default');
        $this->source = config("database.connections.$conn.database");
        $this->target = $this->option('target');

        if ($this->target === $this->source) {
            $this->error("Refusing to run: --target ({$this->target}) is the SOURCE database. The source must stay read-only.");
            return self::FAILURE;
        }

        $count = max(50, min(60, (int) $this->option('count')));
        $dryRun = (bool) $this->option('dry-run');

        $this->line("Source : <info>{$this->source}</info> (read-only)");
        $targetNote = $dryRun ? ' (dry-run, untouched)' : ($this->option('no-create') ? ' (existing DB; tables reset & filled)' : ' (will be DROPPED & recreated)');
        $this->line("Target : <info>{$this->target}</info>" . $targetNote);
        $this->newLine();

        // 1. Introspect the live schema once.
        $this->loadSchema();

        // 2. Choose the patients.
        $this->patientIds = $this->selectPatients($count);

        if ($dryRun) {
            $this->info('Dry-run complete. No database was created or modified.');
            return self::SUCCESS;
        }

        if (! $this->option('force')) {
            if (! $this->confirm("Drop & recreate `{$this->target}` and copy " . count($this->patientIds) . ' patients into it?')) {
                $this->line('Aborted.');
                return self::SUCCESS;
            }
        }

        // 3. Clone schema, then copy data.
        $this->cloneSchema();
        $this->copyData();

        $this->newLine();
        $this->info('Done. New database `' . $this->target . '` is ready with ' . count($this->patientIds) . ' patients.');
        $this->line('Tip: point a scratch .env at it (DB_DATABASE=' . $this->target . ') and run `php artisan serve`.');
        $this->line('Optional: realign dates to today with `php artisan data:shift-dates` (after switching .env to the new DB).');

        return self::SUCCESS;
    }

    // ---------------------------------------------------------------- schema

    private function loadSchema(): void
    {
        $rows = DB::table('information_schema.columns')
            ->select('TABLE_NAME as t', 'COLUMN_NAME as c', 'COLUMN_KEY as k')
            ->where('TABLE_SCHEMA', $this->source)
            ->orderBy('ORDINAL_POSITION')
            ->get();

        foreach ($rows as $r) {
            $this->cols[$r->t][] = $r->c;
            if ($r->k === 'PRI' && ! isset($this->pk[$r->t])) {
                $this->pk[$r->t] = $r->c;
            }
        }
        $this->line('Discovered ' . count($this->cols) . ' tables.');
    }

    private function has(string $table, string $col): bool
    {
        return in_array($col, $this->cols[$table] ?? [], true);
    }

    private function pkOf(string $table): string
    {
        return $this->pk[$table] ?? 'id';
    }

    // --------------------------------------------------------- patient choice

    /**
     * Build a tag => [patient ids] map, then greedily pick patients that together
     * cover every tag, then top up by "richness" (most tags) to reach $count.
     *
     * @return array<int>
     */
    private function selectPatients(int $count): array
    {
        $tags = $this->coverageTags();

        // Only keep ids that are real patient rows (drops 0 / orphan FK placeholders).
        $valid = array_fill_keys(
            collect(DB::table('patients')->where('id', '>', 0)->pluck('id'))->map('intval')->all(),
            true
        );
        $tags = array_map(fn ($ids) => array_values(array_filter($ids, fn ($id) => isset($valid[$id]))), $tags);
        $tags = array_filter($tags, fn ($ids) => ! empty($ids));

        // patient => set(tag)
        $map = [];
        foreach ($tags as $tag => $ids) {
            foreach ($ids as $id) {
                $map[$id][$tag] = true;
            }
        }

        if (empty($map)) {
            $this->error('No candidate patients found — is the source DB empty?');
            return [];
        }

        $uncovered = array_fill_keys(array_keys($tags), true);
        $selected = [];

        // Greedy set-cover: each round take the patient covering the most still-uncovered tags.
        while (! empty($uncovered) && count($selected) < $count) {
            $bestId = null; $bestGain = -1; $bestTotal = -1;
            foreach ($map as $id => $ptags) {
                if (isset($selected[$id])) continue;
                $gain = count(array_intersect_key($ptags, $uncovered));
                $total = count($ptags);
                if ($gain > $bestGain || ($gain === $bestGain && $total > $bestTotal)) {
                    $bestId = $id; $bestGain = $gain; $bestTotal = $total;
                }
            }
            if ($bestId === null || $bestGain <= 0) break; // all tags covered
            $selected[$bestId] = true;
            foreach (array_keys($map[$bestId]) as $t) unset($uncovered[$t]);
        }

        // Top up to $count with the richest (most-tagged) remaining patients.
        if (count($selected) < $count) {
            $pool = array_diff_key($map, $selected);
            uasort($pool, fn ($a, $b) => count($b) <=> count($a));
            foreach ($pool as $id => $ptags) {
                if (count($selected) >= $count) break;
                $selected[$id] = true;
            }
        }

        $ids = array_map('intval', array_keys($selected));
        $this->reportSelection($ids, $tags, $uncovered);
        return $ids;
    }

    /**
     * Each tag is a distinct condition the sample must exercise.
     * Value = bounded list of recent patient ids satisfying it.
     *
     * @return array<string,array<int>>
     */
    private function coverageTags(): array
    {
        $limit = 300;
        $t = [];
        $distinct = function (string $sql) use ($limit) {
            try {
                return collect(DB::select($sql . " LIMIT $limit"))->pluck('pid')->map('intval')->all();
            } catch (\Throwable $e) {
                $this->warn('  (skipped a coverage tag: ' . $e->getMessage() . ')');
                return [];
            }
        };

        $t['anc']          = $distinct("SELECT DISTINCT patients_id pid FROM anc WHERE patients_id IS NOT NULL ORDER BY id DESC");
        $t['iui']          = $distinct("SELECT DISTINCT patients_id pid FROM iui WHERE patients_id IS NOT NULL ORDER BY id DESC");
        $t['iui_active']   = $distinct("SELECT DISTINCT patients_id pid FROM iui_history WHERE cycle_status=1 AND patients_id IS NOT NULL ORDER BY id DESC");
        $t['iui_finished'] = $distinct("SELECT DISTINCT patients_id pid FROM iui_history WHERE cycle_status=2 AND patients_id IS NOT NULL ORDER BY id DESC");
        $t['iui_multi']    = $distinct("SELECT patients_id pid FROM iui_history WHERE patients_id IS NOT NULL GROUP BY patients_id HAVING COUNT(DISTINCT cycle_no)>=2 ORDER BY patients_id DESC");
        $t['ivf']          = $distinct("SELECT DISTINCT patients_id pid FROM ivf WHERE patients_id IS NOT NULL ORDER BY id DESC");
        foreach ([1, 2, 3, 4] as $p) {
            $t["ivf_plan_$p"] = $distinct("SELECT DISTINCT patients_id pid FROM ivf_history WHERE plan=$p AND patients_id IS NOT NULL ORDER BY id DESC");
        }
        $t['ivf_active']   = $distinct("SELECT DISTINCT patients_id pid FROM ivf_history WHERE cycle_status=1 AND patients_id IS NOT NULL ORDER BY id DESC");
        $t['ivf_finished'] = $distinct("SELECT DISTINCT patients_id pid FROM ivf_history WHERE cycle_status=2 AND patients_id IS NOT NULL ORDER BY id DESC");
        $t['gynec']        = $distinct("SELECT DISTINCT patients_id pid FROM gynec WHERE patients_id IS NOT NULL ORDER BY id DESC");
        $t['indoor']       = $distinct("SELECT DISTINCT patient_id pid FROM indoor_books WHERE patient_id IS NOT NULL ORDER BY id DESC");
        $t['donors']       = $distinct("SELECT DISTINCT patients_id pid FROM donors WHERE patients_id IS NOT NULL ORDER BY id DESC");
        $t['semen_freeze'] = $distinct("SELECT DISTINCT patients_id pid FROM semen_freezings WHERE patients_id IS NOT NULL ORDER BY id DESC");
        $t['hsa_report']   = $distinct("SELECT DISTINCT patients_id pid FROM hsa_reports WHERE patients_id IS NOT NULL ORDER BY id DESC");
        $t['ivf_transfer'] = $distinct("SELECT DISTINCT patient_id pid FROM ivf_transfer_reports WHERE patient_id IS NOT NULL ORDER BY id DESC");
        $t['ivf_payment']  = $distinct("SELECT DISTINCT patients_id pid FROM ivf_payment WHERE patients_id IS NOT NULL ORDER BY id DESC");
        $t['iui_bill']     = $distinct("SELECT DISTINCT patient_id pid FROM iui_bill WHERE patient_id IS NOT NULL ORDER BY id DESC");
        $t['indoor_dep']   = $distinct("SELECT DISTINCT patient_id pid FROM indoor_deposits WHERE patient_id IS NOT NULL ORDER BY id DESC");
        $t['ref_doctor']   = $distinct("SELECT id pid FROM patients WHERE reference_doctor_id IS NOT NULL ORDER BY id DESC");

        // Appointment categories — one bucket per major category.
        foreach ([1, 2, 3, 4, 5, 6, 13, 17, 18, 22] as $cat) {
            $t["appt_cat_$cat"] = $distinct("SELECT DISTINCT patients_id pid FROM appointments WHERE category_id=$cat AND patients_id IS NOT NULL ORDER BY id DESC");
        }

        // Drop tags that have no data so they don't block coverage.
        return array_filter($t, fn ($ids) => ! empty($ids));
    }

    private function reportSelection(array $ids, array $tags, array $uncovered): void
    {
        $this->newLine();
        $this->info('Selected ' . count($ids) . ' patients: ' . implode(', ', $ids));
        $this->newLine();

        $rows = [];
        $chosen = array_fill_keys($ids, true);
        foreach ($tags as $tag => $tagIds) {
            $hit = array_values(array_intersect($tagIds, $ids));
            $rows[] = [$tag, count($tagIds), count($hit), count($hit) ? '✓ ' . implode(',', array_slice($hit, 0, 4)) . (count($hit) > 4 ? '…' : '') : '✗ MISSING'];
        }
        $this->table(['Condition (tag)', 'Avail', 'In sample', 'Example patient ids'], $rows);

        if (! empty($uncovered)) {
            $this->warn('Tags NOT covered (no patient available for them): ' . implode(', ', array_keys($uncovered)));
        } else {
            $this->info('Every condition is covered by the sample. ✓');
        }
    }

    // --------------------------------------------------------- schema clone

    private function cloneSchema(): void
    {
        $conn = config('database.default');
        $c = config("database.connections.$conn");
        $env = ['MYSQL_PWD' => (string) $c['password']];
        $base = ['-h' . $c['host'], '-P' . (string) $c['port'], '-u' . $c['username']];

        if ($this->option('no-create')) {
            $this->line('Skipping DROP/CREATE (--no-create); target DB must already exist.');
            // Sanity: confirm we can reach the target schema.
            $exists = DB::table('information_schema.schemata')->where('SCHEMA_NAME', $this->target)->exists();
            if (! $exists) {
                throw new \RuntimeException("Target database `{$this->target}` does not exist. Create it first (as a privileged user) or drop --no-create.");
            }
        } else {
            $this->line('Dropping & recreating target database...');
            DB::statement("DROP DATABASE IF EXISTS `{$this->target}`");
            DB::statement("CREATE DATABASE `{$this->target}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        }

        $this->line('Cloning schema (structure only) via mysqldump...');
        $dump = new Process(array_merge(
            ['mysqldump', '--no-data', '--skip-triggers', '--no-tablespaces', '--skip-lock-tables', '--single-transaction'],
            $base,
            [$this->source]
        ), null, $env);
        $dump->setTimeout(600);
        $dump->mustRun();

        // Strip DEFINER clauses so VIEWS import without needing SUPER/SET_USER_ID
        // (mysqldump records the original definer; a restricted user can't recreate it).
        $sql = $dump->getOutput();
        $sql = preg_replace('/DEFINER=`[^`]*`@`[^`]*`/', '', $sql);
        $sql = preg_replace('/DEFINER=[^ ]+@[^ ]+ /', '', $sql);
        $sql = str_replace('SQL SECURITY DEFINER', 'SQL SECURITY INVOKER', $sql);

        $import = new Process(array_merge(['mysql'], $base, [$this->target]), null, $env);
        $import->setTimeout(600);
        $import->setInput($sql);
        $import->mustRun();

        $this->info('Schema cloned.');
    }

    // ----------------------------------------------------------- data copy

    private function copyData(): void
    {
        // Classify tables.
        $patientKeyed = []; // table => fk column (patients_id|patient_id)
        $undecided = [];
        foreach (array_keys($this->cols) as $table) {
            if (in_array($table, self::SKIP_DATA, true) || $this->endsWith($table, '_archive')) {
                continue;
            }
            if ($table === 'patients') {
                continue;
            }
            if ($this->has($table, 'patients_id')) {
                $patientKeyed[$table] = 'patients_id';
            } elseif ($this->has($table, 'patient_id')) {
                $patientKeyed[$table] = 'patient_id';
            } else {
                $undecided[$table] = true;
            }
        }

        // Patient-scoped set P = patients + patient-keyed + transitive grandchildren.
        // A still-undecided table is a grandchild if it has an *_id column whose base
        // resolves to a table already in P; copy it WHERE that fk IN (target.parent.pk).
        $inP = array_merge(['patients' => 'id'], $patientKeyed); // table => fk-into-patients (unused for grandchildren)
        $grandchildren = []; // table => [fkColumn, parentTable]

        $changed = true;
        while ($changed) {
            $changed = false;
            foreach (array_keys($undecided) as $table) {
                foreach ($this->cols[$table] as $col) {
                    if (! $this->endsWith($col, '_id')) continue;
                    $parent = $this->resolveParent($col);
                    if ($parent !== null && (isset($inP[$parent]) || isset($grandchildren[$parent])) && $parent !== $table) {
                        $grandchildren[$table] = [$col, $parent];
                        unset($undecided[$table]);
                        $changed = true;
                        break;
                    }
                }
            }
        }

        // Everything still undecided has no link to a patient => reference table (copy full).
        $reference = array_keys($undecided);

        $idList = implode(',', $this->patientIds);

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('SET UNIQUE_CHECKS=0');

        $summary = [];

        // a) patients themselves
        $summary[] = $this->copyWhere('patients', "id IN ($idList)");

        // b) reference / lookup tables — full copy
        $this->line('Copying ' . count($reference) . ' reference tables in full...');
        foreach ($reference as $table) {
            $summary[] = $this->copyWhere($table, null);
        }

        // c) patient-keyed tables — filtered
        $this->line('Copying ' . count($patientKeyed) . ' patient-keyed tables...');
        foreach ($patientKeyed as $table => $fk) {
            $summary[] = $this->copyWhere($table, "`$fk` IN ($idList)");
        }

        // d) grandchildren — filtered by their already-copied parent (fixpoint order)
        $this->line('Copying ' . count($grandchildren) . ' grandchild tables...');
        foreach ($this->orderGrandchildren($grandchildren, $inP) as $table) {
            [$fk, $parent] = $grandchildren[$table];
            $ppk = $this->pkOf($parent);
            $summary[] = $this->copyWhere($table, "`$fk` IN (SELECT `$ppk` FROM `{$this->target}`.`$parent`)");
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        DB::statement('SET UNIQUE_CHECKS=1');

        $this->renderSummary($summary, $grandchildren);
    }

    /**
     * Copy rows from source.table into target.table (same column layout) and return
     * [table, class, rowsCopied]. $where is null for a full copy.
     */
    private function copyWhere(string $table, ?string $where): array
    {
        $sql = "INSERT INTO `{$this->target}`.`$table` SELECT * FROM `{$this->source}`.`$table`"
            . ($where ? " WHERE $where" : '');
        try {
            DB::statement($sql);
            $n = DB::table("{$this->target}.$table")->count();
        } catch (\Throwable $e) {
            $this->warn("  ! $table: " . $e->getMessage());
            $n = -1;
        }
        return [$table, $where === null ? 'reference' : 'filtered', $n];
    }

    /** Order grandchildren so parents are copied before children (simple topo by depth). */
    private function orderGrandchildren(array $grandchildren, array $inP): array
    {
        $depth = function ($table) use (&$depth, $grandchildren, $inP) {
            if (! isset($grandchildren[$table])) return 0; // parent already in P
            return 1 + $depth($grandchildren[$table][1]);
        };
        $tables = array_keys($grandchildren);
        usort($tables, fn ($a, $b) => $depth($a) <=> $depth($b));
        return $tables;
    }

    /** Map an `xxx_id` column to an existing table name (handles plural + irregular). */
    private function resolveParent(string $col): ?string
    {
        $base = substr($col, 0, -3); // strip _id
        foreach ([$base, $base . 's', rtrim($base, 's')] as $cand) {
            if (isset($this->cols[$cand])) return $cand;
        }
        return null;
    }

    private function renderSummary(array $summary, array $grandchildren): void
    {
        $this->newLine();
        $total = 0; $warned = [];
        $rows = [];
        foreach ($summary as [$table, $class, $n]) {
            $total += max(0, $n);
            $rows[] = [$table, $class, $n < 0 ? 'ERROR' : $n];
            // Flag a reference table that came up empty but looks linkable (possible missed grandchild).
            if ($n === 0 && $class === 'reference') {
                foreach ($this->cols[$table] as $col) {
                    if ($this->endsWith($col, '_id') && $this->resolveParent($col)) { $warned[] = $table; break; }
                }
            }
        }
        // Only show non-empty + error rows to keep it readable.
        $rows = array_values(array_filter($rows, fn ($r) => $r[2] !== 0));
        $this->table(['Table', 'Class', 'Rows'], $rows);
        $this->info('Total rows copied: ' . number_format($total));

        if (! empty($warned)) {
            $this->warn('Empty reference tables that have *_id columns (review — may be an unmapped child): ' . implode(', ', array_unique($warned)));
        }
    }

    private function endsWith(string $haystack, string $needle): bool
    {
        return $needle === '' || substr($haystack, -strlen($needle)) === $needle;
    }
}
