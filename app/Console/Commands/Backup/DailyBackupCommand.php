<?php

namespace App\Console\Commands\Backup;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DailyBackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:daily 
                            {--include-db : Include database backup}
                            {--include-files : Include files backup}
                            {--compress : Compress the backup}
                            {--destination=local : Backup destination (local,s3,ftp)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a daily backup of the application';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting daily backup...');
        $this->newLine();

        $backupPath = storage_path('backups/' . date('Y/m/d'));
        $this->ensureBackupDirectoryExists($backupPath);

        $backupData = [
            'timestamp' => now()->toDateTimeString(),
            'files' => [],
            'database' => null,
            'size' => 0,
        ];

        // Backup files
        if ($this->option('include-files') || $this->option('include-db')) {
            $this->info('📁 Backing up files...');
            $filesBackup = $this->backupFiles($backupPath);
            $backupData['files'] = $filesBackup;
            $backupData['size'] += $filesBackup['size'] ?? 0;
        }

        // Backup database
        if ($this->option('include-db')) {
            $this->info('🗄️ Backing up database...');
            $dbBackup = $this->backupDatabase($backupPath);
            $backupData['database'] = $dbBackup;
            $backupData['size'] += $dbBackup['size'] ?? 0;
        }

        // Create backup manifest
        $this->createBackupManifest($backupPath, $backupData);

        // Compress backup
        if ($this->option('compress')) {
            $this->info('📦 Compressing backup...');
            $this->compressBackup($backupPath);
        }

        // Upload to destination
        $destination = $this->option('destination');
        if ($destination !== 'local') {
            $this->uploadBackup($backupPath, $destination);
        }

        // Cleanup old backups (keep last 30 days)
        $this->cleanupOldBackups();

        // Log backup completion
        Log::info('Daily backup completed', [
            'backup_path' => $backupPath,
            'size' => $backupData['size'],
            'destination' => $destination,
            'included' => [
                'files' => $this->option('include-files'),
                'database' => $this->option('include-db'),
            ]
        ]);

        $this->newLine();
        $this->info('✅ Daily backup completed successfully!');
        $this->table(
            ['Item', 'Status', 'Size'],
            [
                ['Files Backup', $backupData['files'] ? '✅' : '❌', $this->formatSize($backupData['files']['size'] ?? 0)],
                ['Database Backup', $backupData['database'] ? '✅' : '❌', $this->formatSize($backupData['database']['size'] ?? 0)],
                ['Total Size', '', $this->formatSize($backupData['size'])],
            ]
        );

        return Command::SUCCESS;
    }

    private function ensureBackupDirectoryExists(string $path): void
    {
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
            $this->info("Created backup directory: {$path}");
        }
    }

    private function backupFiles(string $backupPath): array
    {
        $filesToBackup = [
            'app' => base_path('app'),
            'config' => base_path('config'),
            'resources' => base_path('resources'),
            'routes' => base_path('routes'),
            'public' => base_path('public'),
        ];

        $backupFile = $backupPath . '/files_backup_' . date('Y-m-d_H-i-s') . '.zip';
        $zip = new \ZipArchive();

        if ($zip->open($backupFile, \ZipArchive::CREATE) !== true) {
            throw new \Exception('Could not create backup file.');
        }

        $totalSize = 0;
        foreach ($filesToBackup as $folder => $path) {
            if (File::exists($path)) {
                $this->addFolderToZip($zip, $path, $folder);
                $totalSize += $this->getFolderSize($path);
            }
        }

        $zip->close();

        return [
            'path' => $backupFile,
            'size' => File::size($backupFile),
        ];
    }

    private function addFolderToZip(\ZipArchive $zip, string $folder, string $zipPath): void
    {
        $files = File::allFiles($folder);
        
        foreach ($files as $file) {
            $relativePath = $zipPath . '/' . $file->getRelativePathname();
            $zip->addFile($file->getRealPath(), $relativePath);
        }
    }

    private function getFolderSize(string $folder): int
    {
        $size = 0;
        foreach (File::allFiles($folder) as $file) {
            $size += $file->getSize();
        }
        return $size;
    }

    private function backupDatabase(string $backupPath): array
    {
        $database = config('database.connections.' . config('database.default'));
        $backupFile = $backupPath . '/database_backup_' . date('Y-m-d_H-i-s') . '.sql';

        $command = sprintf(
            'mysqldump --host=%s --port=%s --user=%s --password=%s %s > %s',
            $database['host'],
            $database['port'] ?? 3306,
            $database['username'],
            $database['password'],
            $database['database'],
            $backupFile
        );

        exec($command, $output, $returnCode);

        if ($returnCode !== 0) {
            throw new \Exception('Database backup failed.');
        }

        return [
            'path' => $backupFile,
            'size' => File::size($backupFile),
        ];
    }

    private function createBackupManifest(string $backupPath, array $data): void
    {
        $manifest = [
            'backup_date' => $data['timestamp'],
            'version' => config('app.version', '1.0.0'),
            'environment' => config('app.env'),
            'files_backup' => $data['files']['path'] ?? null,
            'database_backup' => $data['database']['path'] ?? null,
            'total_size' => $data['size'],
            'files_count' => 0,
        ];

        File::put($backupPath . '/manifest.json', json_encode($manifest, JSON_PRETTY_PRINT));
    }

    private function compressBackup(string $backupPath): void
    {
        $files = File::files($backupPath);
        $zipPath = $backupPath . '/backup_' . date('Y-m-d_H-i-s') . '.zip';
        
        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE) === true) {
            foreach ($files as $file) {
                if ($file->getExtension() !== 'zip') {
                    $zip->addFile($file->getRealPath(), $file->getFilename());
                }
            }
            $zip->close();

            // Remove original files after compression
            foreach ($files as $file) {
                if ($file->getExtension() !== 'zip') {
                    File::delete($file->getRealPath());
                }
            }
        }
    }

    private function uploadBackup(string $backupPath, string $destination): void
    {
        $disk = Storage::disk($destination);
        
        if (!$disk) {
            $this->error("Destination '{$destination}' not configured.");
            return;
        }

        $files = File::files($backupPath);
        $remotePath = 'backups/' . date('Y') . '/' . date('m');

        foreach ($files as $file) {
            $disk->putFileAs($remotePath, $file, $file->getFilename());
            $this->info("Uploaded: {$file->getFilename()} to {$destination}");
        }
    }

    private function cleanupOldBackups(): void
    {
        $backupPath = storage_path('backups');
        $days = 30;
        
        $folders = File::directories($backupPath);
        foreach ($folders as $folder) {
            $date = basename($folder);
            if (strtotime($date) < strtotime("-{$days} days")) {
                File::deleteDirectory($folder);
                $this->info("Removed old backup: {$folder}");
            }
        }
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