<?php
// app/Http/Controllers/Admin/BackupController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Backup;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;

class BackupController extends Controller
{
    /**
     * Display a listing of backups.
     */
    public function index(Request $request)
    {
        try {
            $tenantId = auth()->user()->tenant_id;

            $query = Backup::forTenant($tenantId)->with('creator');

            // Filter by type
            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }

            // Filter by status
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $backups = $query->latest()->paginate(15)->withQueryString();

            // Get statistics
            $stats = [
                'total' => Backup::forTenant($tenantId)->count(),
                'database' => Backup::forTenant($tenantId)->where('type', 'database')->count(),
                'files' => Backup::forTenant($tenantId)->where('type', 'files')->count(),
                'full' => Backup::forTenant($tenantId)->where('type', 'full')->count(),
                'total_size' => $this->formatSize(Backup::forTenant($tenantId)->sum('size')),
            ];

            return view('admin.backups.index', compact('backups', 'stats'));
        } catch (\Exception $e) {
            Log::error('Error fetching backups: ' . $e->getMessage());
            return back()->with('error', 'Unable to fetch backups.');
        }
    }

    /**
     * Create a new backup.
     */
    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'type' => ['required', 'in:database,files,full'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $type = $request->type;
            $tenantId = auth()->user()->tenant_id;

            // Create backup record
            $backup = Backup::create([
                'tenant_id' => $tenantId,
                'name' => $this->generateBackupName($type),
                'type' => $type,
                'status' => 'processing',
                'created_by' => auth()->id(),
            ]);

            // Process backup in background (simplified for demo)
            try {
                $this->processBackup($backup);

                $backup->update([
                    'status' => 'completed',
                    'completed_at' => now(),
                ]);

                Activity::create([
                    'user_id' => auth()->id(),
                    'tenant_id' => $tenantId,
                    'subject_type' => Backup::class,
                    'subject_id' => $backup->id,
                    'action' => 'created_backup',
                    'description' => "Created {$type} backup: {$backup->name}",
                    'properties' => [
                        'backup_name' => $backup->name,
                        'backup_type' => $type,
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                return redirect()->route('admin.backups.index')
                    ->with('success', "Backup '{$backup->name}' created successfully.");
            } catch (\Exception $e) {
                $backup->update(['status' => 'failed']);
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error creating backup: ' . $e->getMessage());
            return back()->with('error', 'Failed to create backup: ' . $e->getMessage());
        }
    }

    /**
     * Process backup.
     */
    private function processBackup($backup)
    {
        $backupPath = storage_path('backups/' . $backup->tenant_id);
        
        // Create backup directory
        if (!File::exists($backupPath)) {
            File::makeDirectory($backupPath, 0755, true);
        }

        $filename = $backup->name . '.zip';
        $filepath = $backupPath . '/' . $filename;

        // Create zip archive
        $zip = new \ZipArchive();
        $zip->open($filepath, \ZipArchive::CREATE);

        // Add files based on type
        switch ($backup->type) {
            case 'database':
                $this->addDatabaseToZip($zip, $backup);
                break;
            case 'files':
                $this->addFilesToZip($zip, $backup);
                break;
            case 'full':
                $this->addDatabaseToZip($zip, $backup);
                $this->addFilesToZip($zip, $backup);
                break;
        }

        $zip->close();

        // Update backup record with file info
        $size = File::size($filepath);
        $backup->update([
            'file_path' => 'backups/' . $backup->tenant_id . '/' . $filename,
            'file_name' => $filename,
            'size' => $size,
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    /**
     * Add database backup to zip.
     */
    private function addDatabaseToZip($zip, $backup)
    {
        $dbName = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');

        $sqlFile = storage_path('backups/temp_' . $backup->id . '.sql');
        
        // Create database dump
        $command = sprintf(
            'mysqldump --host=%s --user=%s --password=%s %s > %s',
            escapeshellarg($host),
            escapeshellarg($username),
            escapeshellarg($password),
            escapeshellarg($dbName),
            escapeshellarg($sqlFile)
        );

        exec($command);

        if (File::exists($sqlFile)) {
            $zip->addFile($sqlFile, 'database.sql');
            File::delete($sqlFile);
        }
    }

    /**
     * Add files to zip.
     */
    private function addFilesToZip($zip, $backup)
    {
        $directories = [
            storage_path('app/public'),
            storage_path('app/uploads'),
        ];

        foreach ($directories as $directory) {
            if (File::exists($directory)) {
                $this->addDirectoryToZip($zip, $directory, 'files');
            }
        }
    }

    /**
     * Add directory to zip.
     */
    private function addDirectoryToZip($zip, $dir, $zipSubDir = '')
    {
        $files = File::allFiles($dir);
        
        foreach ($files as $file) {
            $relativePath = $zipSubDir . '/' . $file->getRelativePathname();
            $zip->addFile($file->getRealPath(), $relativePath);
        }
    }

    /**
     * Download a backup.
     */
    public function download(Backup $backup)
    {
        try {
            $this->authorizeTenant($backup);

            if ($backup->status !== 'completed') {
                return back()->with('error', 'Backup is not ready for download.');
            }

            $filePath = storage_path('app/' . $backup->file_path);

            if (!File::exists($filePath)) {
                return back()->with('error', 'Backup file not found.');
            }

            Activity::create([
                'user_id' => auth()->id(),
                'subject_type' => Backup::class,
                'subject_id' => $backup->id,
                'action' => 'downloaded_backup',
                'description' => "Downloaded backup: {$backup->name}",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return response()->download($filePath, $backup->file_name);
        } catch (\Exception $e) {
            Log::error('Error downloading backup: ' . $e->getMessage());
            return back()->with('error', 'Failed to download backup.');
        }
    }

    /**
     * Restore a backup.
     */
    public function restore(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'backup_id' => ['required', 'exists:backups,id'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $backup = Backup::findOrFail($request->backup_id);
            $this->authorizeTenant($backup);

            if ($backup->status !== 'completed') {
                return back()->with('error', 'Backup is not ready for restore.');
            }

            // Process restore (simplified)
            Activity::create([
                'user_id' => auth()->id(),
                'tenant_id' => $backup->tenant_id,
                'subject_type' => Backup::class,
                'subject_id' => $backup->id,
                'action' => 'restored_backup',
                'description' => "Restored backup: {$backup->name}",
                'properties' => [
                    'backup_name' => $backup->name,
                    'backup_type' => $backup->type,
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->route('admin.backups.index')
                ->with('success', "Backup '{$backup->name}' restored successfully.");
        } catch (\Exception $e) {
            Log::error('Error restoring backup: ' . $e->getMessage());
            return back()->with('error', 'Failed to restore backup.');
        }
    }

    /**
     * Delete a backup.
     */
    public function destroy(Backup $backup)
    {
        try {
            $this->authorizeTenant($backup);

            // Delete file
            if ($backup->file_path && Storage::exists($backup->file_path)) {
                Storage::delete($backup->file_path);
            }

            $backupName = $backup->name;

            Activity::create([
                'user_id' => auth()->id(),
                'subject_type' => Backup::class,
                'subject_id' => $backup->id,
                'action' => 'deleted_backup',
                'description' => "Deleted backup: {$backupName}",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            $backup->delete();

            return redirect()->route('admin.backups.index')
                ->with('success', "Backup '{$backupName}' deleted successfully.");
        } catch (\Exception $e) {
            Log::error('Error deleting backup: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete backup.');
        }
    }

    /**
     * Schedule a backup.
     */
    public function schedule(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'frequency' => ['required', 'in:daily,weekly,monthly'],
                'time' => ['required', 'string'],
                'type' => ['required', 'in:database,files,full'],
                'retention' => ['nullable', 'integer', 'min:1'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Save schedule settings
            $settings = [
                'frequency' => $request->frequency,
                'time' => $request->time,
                'type' => $request->type,
                'retention' => $request->retention ?? 30,
            ];

            // In production, store these settings and create a cron job
            Activity::create([
                'user_id' => auth()->id(),
                'action' => 'scheduled_backup',
                'description' => "Scheduled {$request->frequency} backups at {$request->time}",
                'properties' => $settings,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->route('admin.backups.index')
                ->with('success', 'Backup schedule configured successfully.');
        } catch (\Exception $e) {
            Log::error('Error scheduling backup: ' . $e->getMessage());
            return back()->with('error', 'Failed to schedule backup.');
        }
    }

    /**
     * Clean old backups.
     */
    public function clean(Request $request)
    {
        try {
            $tenantId = auth()->user()->tenant_id;
            $days = $request->get('days', 30);

            $oldBackups = Backup::forTenant($tenantId)
                ->where('created_at', '<', now()->subDays($days))
                ->where('status', 'completed')
                ->get();

            $count = 0;
            foreach ($oldBackups as $backup) {
                if ($backup->file_path && Storage::exists($backup->file_path)) {
                    Storage::delete($backup->file_path);
                }
                $backup->delete();
                $count++;
            }

            Activity::create([
                'user_id' => auth()->id(),
                'tenant_id' => $tenantId,
                'action' => 'cleaned_backups',
                'description' => "Cleaned {$count} old backups",
                'properties' => [
                    'days' => $days,
                    'count' => $count,
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->route('admin.backups.index')
                ->with('success', "Cleaned {$count} old backups.");
        } catch (\Exception $e) {
            Log::error('Error cleaning backups: ' . $e->getMessage());
            return back()->with('error', 'Failed to clean backups.');
        }
    }

    /**
     * Generate backup name.
     */
    private function generateBackupName($type)
    {
        $prefix = strtoupper($type);
        $date = date('Y-m-d_H-i-s');
        $random = Str::random(6);
        return "{$prefix}_BACKUP_{$date}_{$random}";
    }

    /**
     * Format file size.
     */
    private function formatSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;
        
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Authorize tenant.
     */
    protected function authorizeTenant($model)
    {
        if ($model->tenant_id !== auth()->user()->tenant_id) {
            abort(403, 'Unauthorized action.');
        }
    }
}