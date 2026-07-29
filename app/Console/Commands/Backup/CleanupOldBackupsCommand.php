<?php

namespace App\Console\Commands\Backup;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class CleanupOldBackupsCommand extends Command
{
    protected $signature = 'backup:cleanup
                            {--days=30 : Number of days to keep}
                            {--dry-run : Show what would be deleted without actually deleting}';

    protected $description = 'Clean up old backup files';

    public function handle()
    {
        $days = (int) $this->option('days');
        $dryRun = $this->option('dry-run');
        
        $this->info("🧹 Cleaning up backups older than {$days} days...");
        
        if ($dryRun) {
            $this->warn('⚠️ DRY RUN - No files will be deleted');
        }

        $backupPath = storage_path('backups');
        if (!File::exists($backupPath)) {
            $this->error('Backup directory not found.');
            return Command::FAILURE;
        }

        $deleted = [
            'files' => 0,
            'size' => 0,
            'folders' => 0,
        ];

        // Clean up daily backups
        $dailyPath = $backupPath . '/database';
        if (File::exists($dailyPath)) {
            $result = $this->cleanupDirectory($dailyPath, $days, $dryRun);
            $deleted['files'] += $result['files'];
            $deleted['size'] += $result['size'];
            $deleted['folders'] += $result['folders'];
        }

        // Clean up full backups
        $fullBackupPath = $backupPath . '/backups';
        if (File::exists($fullBackupPath)) {
            $result = $this->cleanupDirectory($fullBackupPath, $days, $dryRun);
            $deleted['files'] += $result['files'];
            $deleted['size'] += $result['size'];
            $deleted['folders'] += $result['folders'];
        }

        $this->newLine();
        $this->info('✅ Cleanup completed!');
        $this->table(
            ['Metric', 'Value'],
            [
                ['Files Deleted', $deleted['files']],
                ['Folders Deleted', $deleted['folders']],
                ['Space Freed', $this->formatSize($deleted['size'])],
            ]
        );

        Log::info('Backup cleanup completed', $deleted);

        return Command::SUCCESS;
    }

    private function cleanupDirectory(string $path, int $days, bool $dryRun): array
    {
        $result = ['files' => 0, 'size' => 0, 'folders' => 0];
        $cutoffDate = now()->subDays($days);

        $directories = File::directories($path);
        foreach ($directories as $directory) {
            $dirDate = basename($directory);
            if (strtotime($dirDate) < $cutoffDate->timestamp) {
                $size = $this->getDirectorySize($directory);
                
                if (!$dryRun) {
                    File::deleteDirectory($directory);
                }
                
                $result['folders']++;
                $result['size'] += $size;
                
                $this->line("Removed: {$directory} ({$this->formatSize($size)})");
            }
        }

        // Also clean up old files in the root
        $files = File::files($path);
        foreach ($files as $file) {
            if ($file->getMTime() < $cutoffDate->timestamp) {
                $size = $file->getSize();
                
                if (!$dryRun) {
                    File::delete($file->getRealPath());
                }
                
                $result['files']++;
                $result['size'] += $size;
                
                $this->line("Removed: {$file->getFilename()} ({$this->formatSize($size)})");
            }
        }

        return $result;
    }

    private function getDirectorySize(string $directory): int
    {
        $size = 0;
        foreach (File::allFiles($directory) as $file) {
            $size += $file->getSize();
        }
        return $size;
    }

    private function formatSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
}