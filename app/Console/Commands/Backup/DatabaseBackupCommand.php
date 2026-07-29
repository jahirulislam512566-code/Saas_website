<?php

namespace App\Console\Commands\Backup;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class DatabaseBackupCommand extends Command
{
    protected $signature = 'backup:database
                            {--tables=* : Specific tables to backup}
                            {--exclude=* : Tables to exclude}
                            {--compress : Compress the backup}
                            {--format=sql : Backup format (sql, json, csv)}
                            {--chunk=1000 : Chunk size for large tables}';

    protected $description = 'Backup the database';

    public function handle()
    {
        $this->info('Starting database backup...');
        
        $backupPath = storage_path('backups/database/' . date('Y/m/d'));
        $this->ensureDirectoryExists($backupPath);

        $format = $this->option('format');
        $tables = $this->getTables();

        $this->info('📊 Backing up ' . count($tables) . ' tables...');
        
        $progress = $this->output->createProgressBar(count($tables));
        $progress->start();

        $backupData = [];

        foreach ($tables as $table) {
            $data = $this->backupTable($table, $format);
            $backupData[$table] = $data;
            $progress->advance();
        }

        $progress->finish();
        $this->newLine(2);

        // Save backup
        $backupFile = $this->saveBackup($backupPath, $backupData, $format);
        
        // Compress if requested
        if ($this->option('compress')) {
            $this->compressBackup($backupFile);
        }

        Log::info('Database backup completed', [
            'tables' => count($tables),
            'format' => $format,
            'file' => $backupFile
        ]);

        $this->info('✅ Database backup completed successfully!');
        $this->info("📁 Backup saved to: {$backupFile}");

        return Command::SUCCESS;
    }

    private function getTables(): array
    {
        $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();
        
        if ($this->option('tables')) {
            $tables = array_intersect($tables, $this->option('tables'));
        }

        if ($this->option('exclude')) {
            $tables = array_diff($tables, $this->option('exclude'));
        }

        return array_values($tables);
    }

    private function backupTable(string $table, string $format): array
    {
        $chunkSize = (int) $this->option('chunk');
        $query = DB::table($table);

        switch ($format) {
            case 'json':
                $data = $query->get()->toArray();
                return ['data' => json_encode($data, JSON_PRETTY_PRINT)];
            case 'csv':
                $data = $query->get()->toArray();
                return ['data' => $this->arrayToCsv($data)];
            default: // sql
                return ['data' => $this->generateSqlInsert($table, $query)];
        }
    }

    private function generateSqlInsert(string $table, $query): string
    {
        $rows = $query->get();
        $sql = "-- Table: {$table}\n";
        $sql .= "TRUNCATE TABLE `{$table}`;\n\n";

        foreach ($rows->chunk(100) as $chunk) {
            $values = [];
            foreach ($chunk as $row) {
                $rowData = (array) $row;
                $escapedValues = array_map(function ($value) {
                    return is_null($value) ? 'NULL' : "'" . addslashes($value) . "'";
                }, $rowData);
                $values[] = '(' . implode(', ', $escapedValues) . ')';
            }
            $sql .= "INSERT INTO `{$table}` VALUES \n" . implode(",\n", $values) . ";\n\n";
        }

        return $sql;
    }

    private function arrayToCsv(array $data): string
    {
        if (empty($data)) {
            return '';
        }

        $output = fopen('php://temp', 'r+');
        $headers = array_keys((array) $data[0]);
        fputcsv($output, $headers);

        foreach ($data as $row) {
            fputcsv($output, (array) $row);
        }

        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return $csv;
    }

    private function ensureDirectoryExists(string $path): void
    {
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }
    }

    private function saveBackup(string $path, array $data, string $format): string
    {
        $extension = $format === 'sql' ? 'sql' : $format;
        $filename = 'db_backup_' . date('Y-m-d_H-i-s') . '.' . $extension;
        $filePath = $path . '/' . $filename;

        $content = '';
        foreach ($data as $table => $backup) {
            $content .= $backup['data'] . "\n\n";
        }

        File::put($filePath, $content);
        return $filePath;
    }

    private function compressBackup(string $filePath): void
    {
        $zipPath = str_replace('.sql', '.zip', $filePath);
        $zip = new \ZipArchive();

        if ($zip->open($zipPath, \ZipArchive::CREATE) === true) {
            $zip->addFile($filePath, basename($filePath));
            $zip->close();
            File::delete($filePath);
        }
    }
}