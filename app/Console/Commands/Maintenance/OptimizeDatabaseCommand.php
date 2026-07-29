<?php

namespace App\Console\Commands\Maintenance;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OptimizeDatabaseCommand extends Command
{
    protected $signature = 'maintenance:optimize-database
                            {--tables=* : Specific tables to optimize}
                            {--analyze : Analyze tables after optimization}';

    protected $description = 'Optimize database tables';

    public function handle()
    {
        $this->info('🗄️ Optimizing database...');

        $tables = $this->getTables();
        
        if (empty($tables)) {
            $this->error('No tables found to optimize.');
            return Command::FAILURE;
        }

        $this->info("Found {$this->countTables($tables)} tables to optimize.");

        $optimized = 0;
        $failed = 0;

        foreach ($tables as $table) {
            try {
                $this->optimizeTable($table);
                $optimized++;
                $this->line("  ✓ Optimized: {$table}");
            } catch (\Exception $e) {
                $failed++;
                $this->error("  ✗ Failed: {$table} - {$e->getMessage()}");
            }
        }

        if ($this->option('analyze')) {
            $this->analyzeTables($tables);
        }

        $this->newLine();
        $this->info("✅ Optimization completed: {$optimized} optimized, {$failed} failed");

        Log::info('Database optimization completed', [
            'optimized' => $optimized,
            'failed' => $failed,
        ]);

        return Command::SUCCESS;
    }

    private function getTables(): array
    {
        $specifiedTables = $this->option('tables');
        
        if (!empty($specifiedTables)) {
            return $specifiedTables;
        }

        $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();
        
        // Exclude system tables
        $exclude = ['migrations', 'failed_jobs', 'jobs', 'sessions'];
        
        return array_diff($tables, $exclude);
    }

    private function countTables(array $tables): int
    {
        return count($tables);
    }

    private function optimizeTable(string $table): void
    {
        $driver = DB::connection()->getDriverName();
        
        switch ($driver) {
            case 'mysql':
                DB::statement("OPTIMIZE TABLE {$table}");
                break;
            case 'pgsql':
                DB::statement("VACUUM ANALYZE {$table}");
                break;
            case 'sqlite':
                DB::statement("VACUUM");
                break;
            default:
                throw new \Exception("Unsupported database driver: {$driver}");
        }
    }

    private function analyzeTables(array $tables): void
    {
        $this->info('📊 Analyzing tables...');
        
        foreach ($tables as $table) {
            $driver = DB::connection()->getDriverName();
            
            switch ($driver) {
                case 'mysql':
                    DB::statement("ANALYZE TABLE {$table}");
                    break;
                case 'pgsql':
                    DB::statement("ANALYZE {$table}");
                    break;
            }
            
            $this->line("  ✓ Analyzed: {$table}");
        }
    }
}