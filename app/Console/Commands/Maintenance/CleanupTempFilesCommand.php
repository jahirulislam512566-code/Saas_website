<?php

namespace App\Console\Commands\Maintenance;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CleanupTempFilesCommand extends Command
{
    protected $signature = 'maintenance:cleanup-temp
                            {--days=7 : Delete files older than this many days}
                            {--dry-run : Show what would be deleted without actually deleting}';

    protected $description = 'Clean up temporary files';

    public function handle()
    {
        $this->info('🧹 Cleaning up temporary files...');

        $days = (int) $this->option('days');
        $dryRun = $this->option('dry-run');
        $cutoffDate = now()->subDays($days);

        if ($dryRun) {
            $this->warn('⚠️ DRY RUN - No files will be deleted');
        }

        $deleted = [
            'files' => 0,
            'size' => 0,
        ];

        // Clean up storage temp directory
        $tempPath = storage_path('temp');
        if (File::exists($tempPath)) {
            $result = $this->cleanupDirectory($tempPath, $cutoffDate, $dryRun);
            $deleted['files'] += $result['files'];
            $deleted['size'] += $result['size'];
        }

        // Clean up storage framework cache
        $cachePath = storage_path('framework/cache');
        if (File::exists($cachePath)) {
            $result = $this->cleanupDirectory($cachePath, $cutoffDate, $dryRun);
            $deleted['files'] += $result['files'];
            $deleted['size'] += $result['size'];
        }

        // Clean up public temp uploads
        $publicTempPath = public_path('temp');
        if (File::exists($publicTempPath)) {
            $result = $this->cleanupDirectory($publicTempPath, $cutoffDate, $dryRun);
            $deleted['files'] += $result['files'];
            $deleted['size'] += $result['size'];
        }

        // Clean up old export files
        $exportPath = storage_path('exports');
        if (File::exists($exportPath)) {
            $result = $this->cleanupDirectory($exportPath, $cutoffDate, $dryRun);
            $deleted['files'] += $result['files'];
            $deleted['size'] += $result['size'];
        }

        $this->newLine();
        $this->info('✅ Cleanup completed!');
        $this->table(
            ['Metric', 'Value'],
            [
                ['Files Deleted', $deleted['files']],
                ['Space Freed', $this->formatSize($deleted['size'])],
            ]
        );

        Log::info('Temp files cleanup completed', $deleted);

        return Command::SUCCESS;
    }

    private function cleanupDirectory(string $path, $cutoffDate, bool $dryRun): array
    {
        $result = ['files' => 0, 'size' => 0];

        if (!File::exists($path)) {
            return $result;
        }

        $files = File::allFiles($path);
        
        foreach ($files as $file) {
            if ($file->getMTime() < $cutoffDate->timestamp) {
                $size = $file->getSize();
                
                if (!$dryRun) {
                    File::delete($file->getRealPath());
                }
                
                $result['files']++;
                $result['size'] += $size;
                
                $this->line("  Removed: {$file->getRelativePathname()} ({$this->formatSize($size)})");
            }
        }

        // Remove empty directories
        if (!$dryRun) {
            $directories = File::directories($path);
            foreach ($directories as $directory) {
                if (File::isEmptyDirectory($directory)) {
                    File::deleteDirectory($directory);
                }
            }
        }

        return $result;
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