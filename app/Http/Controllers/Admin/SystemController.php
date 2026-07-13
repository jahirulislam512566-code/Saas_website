<?php
// app/Http/Controllers/Admin/SystemController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class SystemController extends Controller
{
    /**
     * System dashboard.
     */
    public function index()
    {
        try {
            $systemInfo = $this->getSystemInfo();
            $phpInfo = $this->getPhpInfo();
            $databaseInfo = $this->getDatabaseInfo();
            $cacheInfo = $this->getCacheInfo();

            return view('admin.system.index', compact('systemInfo', 'phpInfo', 'databaseInfo', 'cacheInfo'));
        } catch (\Exception $e) {
            Log::error('Error loading system dashboard: ' . $e->getMessage());
            return back()->with('error', 'Unable to load system dashboard.');
        }
    }

    /**
     * System information page.
     */
    public function info()
    {
        try {
            $systemInfo = $this->getSystemInfo();
            $phpInfo = $this->getPhpInfo();
            $databaseInfo = $this->getDatabaseInfo();
            
            return view('admin.system.info', compact('systemInfo', 'phpInfo', 'databaseInfo'));
        } catch (\Exception $e) {
            Log::error('Error loading system info: ' . $e->getMessage());
            return back()->with('error', 'Unable to load system information.');
        }
    }

    /**
     * PHP Info page.
     */
    public function phpInfo()
    {
        try {
            ob_start();
            phpinfo(INFO_ALL);
            $phpInfo = ob_get_clean();
            
            // Clean up the output to make it look better
            $phpInfo = preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $phpInfo);
            
            return view('admin.system.phpinfo', compact('phpInfo'));
        } catch (\Exception $e) {
            Log::error('Error loading PHP info: ' . $e->getMessage());
            return back()->with('error', 'Unable to load PHP information.');
        }
    }

    /**
     * Environment page.
     */
    public function environment()
    {
        try {
            $envVariables = [
                'APP_NAME' => env('APP_NAME'),
                'APP_ENV' => env('APP_ENV'),
                'APP_DEBUG' => env('APP_DEBUG'),
                'APP_URL' => env('APP_URL'),
                'DB_CONNECTION' => env('DB_CONNECTION'),
                'DB_HOST' => env('DB_HOST'),
                'DB_PORT' => env('DB_PORT'),
                'DB_DATABASE' => env('DB_DATABASE'),
                'MAIL_MAILER' => env('MAIL_MAILER'),
                'MAIL_HOST' => env('MAIL_HOST'),
                'MAIL_PORT' => env('MAIL_PORT'),
                'MAIL_USERNAME' => env('MAIL_USERNAME'),
                'MAIL_ENCRYPTION' => env('MAIL_ENCRYPTION'),
                'SESSION_DRIVER' => env('SESSION_DRIVER'),
                'CACHE_DRIVER' => env('CACHE_DRIVER'),
                'QUEUE_CONNECTION' => env('QUEUE_CONNECTION'),
            ];

            $envEditable = [
                'APP_NAME',
                'APP_DEBUG',
                'APP_URL',
                'MAIL_HOST',
                'MAIL_PORT',
                'MAIL_USERNAME',
                'MAIL_PASSWORD',
                'MAIL_ENCRYPTION',
            ];

            return view('admin.system.environment', compact('envVariables', 'envEditable'));
        } catch (\Exception $e) {
            Log::error('Error loading environment page: ' . $e->getMessage());
            return back()->with('error', 'Unable to load environment settings.');
        }
    }

    /**
     * Update environment settings.
     */
    public function updateEnvironment(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'APP_NAME' => ['nullable', 'string', 'max:255'],
                'APP_DEBUG' => ['nullable', 'boolean'],
                'APP_URL' => ['nullable', 'url'],
                'MAIL_HOST' => ['nullable', 'string'],
                'MAIL_PORT' => ['nullable', 'numeric'],
                'MAIL_USERNAME' => ['nullable', 'string'],
                'MAIL_PASSWORD' => ['nullable', 'string'],
                'MAIL_ENCRYPTION' => ['nullable', 'string', 'in:ssl,tls,null'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $this->updateEnvFile($request->only([
                'APP_NAME',
                'APP_DEBUG',
                'APP_URL',
                'MAIL_HOST',
                'MAIL_PORT',
                'MAIL_USERNAME',
                'MAIL_PASSWORD',
                'MAIL_ENCRYPTION',
            ]));

            return redirect()->route('admin.system.environment')
                ->with('success', 'Environment settings updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating environment: ' . $e->getMessage());
            return back()->with('error', 'Failed to update environment settings.');
        }
    }

    /**
     * Clear cache.
     */
    public function clearCache()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            
            return back()->with('success', 'All caches cleared successfully.');
        } catch (\Exception $e) {
            Log::error('Error clearing cache: ' . $e->getMessage());
            return back()->with('error', 'Failed to clear cache.');
        }
    }

    /**
     * Optimize application.
     */
    public function optimize()
    {
        try {
            Artisan::call('optimize');
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');
            
            return back()->with('success', 'Application optimized successfully.');
        } catch (\Exception $e) {
            Log::error('Error optimizing application: ' . $e->getMessage());
            return back()->with('error', 'Failed to optimize application.');
        }
    }

    /**
     * Show queue management page.
     */
    public function queue()
    {
        try {
            $queueInfo = [
                'connection' => env('QUEUE_CONNECTION', 'sync'),
                'driver' => config('queue.default'),
                'failed_count' => DB::table('failed_jobs')->count(),
                'pending_jobs' => $this->getPendingJobs(),
            ];

            return view('admin.system.queue', compact('queueInfo'));
        } catch (\Exception $e) {
            Log::error('Error loading queue page: ' . $e->getMessage());
            return back()->with('error', 'Unable to load queue management.');
        }
    }

    /**
     * Retry failed queue jobs.
     */
    public function retryQueue(Request $request)
    {
        try {
            $request->validate([
                'id' => ['required', 'exists:failed_jobs,id'],
            ]);

            Artisan::call('queue:retry', ['id' => $request->id]);

            return back()->with('success', 'Failed job retried successfully.');
        } catch (\Exception $e) {
            Log::error('Error retrying queue job: ' . $e->getMessage());
            return back()->with('error', 'Failed to retry job.');
        }
    }

    /**
     * Retry all failed queue jobs.
     */
    public function retryAll()
    {
        try {
            Artisan::call('queue:retry', ['id' => 'all']);

            return back()->with('success', 'All failed jobs retried successfully.');
        } catch (\Exception $e) {
            Log::error('Error retrying all queue jobs: ' . $e->getMessage());
            return back()->with('error', 'Failed to retry jobs.');
        }
    }

    /**
     * Flush the queue.
     */
    public function flushQueue()
    {
        try {
            Artisan::call('queue:flush');

            return back()->with('success', 'Queue flushed successfully.');
        } catch (\Exception $e) {
            Log::error('Error flushing queue: ' . $e->getMessage());
            return back()->with('error', 'Failed to flush queue.');
        }
    }

    /**
     * Show logs page.
     */
    public function logs(Request $request)
    {
        try {
            $logFiles = $this->getLogFiles();
            $currentFile = $request->get('file', 'laravel.log');
            $logContent = $this->getLogContent($currentFile);

            return view('admin.system.logs', compact('logFiles', 'currentFile', 'logContent'));
        } catch (\Exception $e) {
            Log::error('Error loading logs: ' . $e->getMessage());
            return back()->with('error', 'Unable to load logs.');
        }
    }

    /**
     * View a specific log file.
     */
    public function logView(Request $request, $file)
    {
        try {
            $logContent = $this->getLogContent($file);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'content' => $logContent,
                ]);
            }

            return view('admin.system.log-viewer', compact('logContent', 'file'));
        } catch (\Exception $e) {
            Log::error('Error viewing log: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to load log file.',
                ], 500);
            }

            return back()->with('error', 'Failed to load log file.');
        }
    }

    /**
     * Clear logs.
     */
    public function clearLogs()
    {
        try {
            $logPath = storage_path('logs');
            $files = File::files($logPath);
            
            foreach ($files as $file) {
                if ($file->getFilename() !== '.gitignore') {
                    File::delete($file);
                }
            }

            return back()->with('success', 'Logs cleared successfully.');
        } catch (\Exception $e) {
            Log::error('Error clearing logs: ' . $e->getMessage());
            return back()->with('error', 'Failed to clear logs.');
        }
    }

    /**
     * Delete a specific log file.
     */
    public function deleteLog(Request $request, $file)
    {
        try {
            $logPath = storage_path('logs/' . $file);
            
            if (File::exists($logPath) && $file !== '.gitignore') {
                File::delete($logPath);
                
                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Log file deleted successfully.',
                    ]);
                }
            }

            return redirect()->route('admin.system.logs')
                ->with('success', 'Log file deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting log: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete log file.',
                ], 500);
            }

            return back()->with('error', 'Failed to delete log file.');
        }
    }

    /**
     * Download a log file.
     */
    public function downloadLog(Request $request, $file)
    {
        try {
            $logPath = storage_path('logs/' . $file);
            
            if (!File::exists($logPath)) {
                abort(404, 'Log file not found.');
            }

            return response()->download($logPath, $file);
        } catch (\Exception $e) {
            Log::error('Error downloading log: ' . $e->getMessage());
            return back()->with('error', 'Failed to download log file.');
        }
    }

    /**
     * Show dependencies page.
     */
    public function dependencies()
    {
        try {
            $composerPackages = $this->getComposerPackages();
            $npmPackages = $this->getNpmPackages();

            return view('admin.system.dependencies', compact('composerPackages', 'npmPackages'));
        } catch (\Exception $e) {
            Log::error('Error loading dependencies: ' . $e->getMessage());
            return back()->with('error', 'Unable to load dependencies.');
        }
    }

    /**
     * Show backups page.
     */
    public function backups(Request $request)
    {
        try {
            $backups = $this->getBackupFiles();
            
            return view('admin.system.backups', compact('backups'));
        } catch (\Exception $e) {
            Log::error('Error loading backups: ' . $e->getMessage());
            return back()->with('error', 'Unable to load backups.');
        }
    }

    /**
     * Create a backup.
     */
    public function createBackup(Request $request)
    {
        try {
            $type = $request->get('type', 'database');
            
            if ($type === 'database') {
                $this->createDatabaseBackup();
            } else {
                $this->createFullBackup();
            }

            return back()->with('success', 'Backup created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating backup: ' . $e->getMessage());
            return back()->with('error', 'Failed to create backup.');
        }
    }

    /**
     * Download a backup file.
     */
    public function downloadBackup(Request $request, $file)
    {
        try {
            $backupPath = storage_path('backups/' . $file);
            
            if (!File::exists($backupPath)) {
                abort(404, 'Backup file not found.');
            }

            return response()->download($backupPath, $file);
        } catch (\Exception $e) {
            Log::error('Error downloading backup: ' . $e->getMessage());
            return back()->with('error', 'Failed to download backup.');
        }
    }

    /**
     * Delete a backup file.
     */
    public function deleteBackup(Request $request, $file)
    {
        try {
            $backupPath = storage_path('backups/' . $file);
            
            if (File::exists($backupPath)) {
                File::delete($backupPath);
                
                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Backup deleted successfully.',
                    ]);
                }
            }

            return redirect()->route('admin.system.backups')
                ->with('success', 'Backup deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting backup: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete backup.',
                ], 500);
            }

            return back()->with('error', 'Failed to delete backup.');
        }
    }

    /**
     * Toggle maintenance mode.
     */
    public function toggleMaintenance(Request $request)
    {
        try {
            $request->validate([
                'status' => ['required', 'boolean'],
            ]);

            if ($request->status) {
                Artisan::call('down', ['--retry' => 60, '--message' => 'Site is currently undergoing maintenance.']); // Fixed: using --message instead of --render
            } else {
                Artisan::call('up');
            }

            $status = $request->status ? 'enabled' : 'disabled';

            return response()->json([
                'success' => true,
                'message' => "Maintenance mode {$status} successfully.",
                'status' => $request->status,
            ]);
        } catch (\Exception $e) {
            Log::error('Error toggling maintenance mode: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle maintenance mode.',
            ], 500);
        }
    }

    /**
     * Get maintenance status.
     */
    public function maintenanceStatus()
    {
        try {
            $isDown = app()->isDownForMaintenance();

            return response()->json([
                'success' => true,
                'maintenance' => $isDown,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get maintenance status.',
            ], 500);
        }
    }

    // ============================================
    // PRIVATE HELPER METHODS
    // ============================================

    /**
     * Get system information.
     */
    private function getSystemInfo()
    {
        return [
            'os' => php_uname(),
            'php_version' => phpversion(),
            'laravel_version' => app()->version(),
            'server' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'server_ip' => $_SERVER['SERVER_ADDR'] ?? 'Unknown',
            'server_name' => $_SERVER['SERVER_NAME'] ?? 'Unknown',
            'server_port' => $_SERVER['SERVER_PORT'] ?? '80',
            'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown',
            'server_time' => now()->format('Y-m-d H:i:s'),
            'timezone' => config('app.timezone'),
            'locale' => config('app.locale'),
            'environment' => app()->environment(),
            'debug' => config('app.debug') ? 'Enabled' : 'Disabled',
            'url' => config('app.url'),
        ];
    }

    /**
     * Get PHP information.
     */
    private function getPhpInfo()
    {
        return [
            'version' => phpversion(),
            'extensions' => get_loaded_extensions(),
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'max_input_time' => ini_get('max_input_time'),
            'max_input_vars' => ini_get('max_input_vars'),
            'display_errors' => ini_get('display_errors'),
            'error_reporting' => ini_get('error_reporting'),
            'allow_url_fopen' => ini_get('allow_url_fopen'),
            'allow_url_include' => ini_get('allow_url_include'),
            'disable_functions' => ini_get('disable_functions'),
            'disable_classes' => ini_get('disable_classes'),
        ];
    }

    /**
     * Get database information.
     */
    private function getDatabaseInfo()
    {
        try {
            $connection = DB::connection();
            $driver = $connection->getDriverName();
            $name = $connection->getDatabaseName();
            
            // Get table count
            $tables = DB::select('SHOW TABLES');
            $tableCount = count($tables);
            
            // Get database size (for MySQL)
            $dbSize = 'Unknown';
            if ($driver === 'mysql') {
                $dbSizeResult = DB::select("
                    SELECT SUM(data_length + index_length) / 1024 / 1024 AS size 
                    FROM information_schema.TABLES 
                    WHERE table_schema = ?
                ", [$name]);
                
                if (!empty($dbSizeResult)) {
                    $dbSize = round($dbSizeResult[0]->size, 2) . ' MB';
                }
            }

            return [
                'driver' => $driver,
                'name' => $name,
                'host' => config('database.connections.' . $driver . '.host', 'Unknown'),
                'port' => config('database.connections.' . $driver . '.port', 'Unknown'),
                'tables' => $tableCount,
                'size' => $dbSize,
                'charset' => config('database.connections.' . $driver . '.charset', 'Unknown'),
                'collation' => config('database.connections.' . $driver . '.collation', 'Unknown'),
                'prefix' => config('database.connections.' . $driver . '.prefix', ''),
            ];
        } catch (\Exception $e) {
            return [
                'driver' => 'Unknown',
                'name' => 'Unknown',
                'host' => 'Unknown',
                'port' => 'Unknown',
                'tables' => 0,
                'size' => 'Unknown',
                'charset' => 'Unknown',
                'collation' => 'Unknown',
                'prefix' => '',
            ];
        }
    }

    /**
     * Get cache information.
     */
    private function getCacheInfo()
    {
        return [
            'driver' => config('cache.default', 'file'),
            'stores' => array_keys(config('cache.stores', [])),
            'prefix' => config('cache.prefix', ''),
            'view_cache' => app()->runningInConsole() ? 'N/A' : (Cache::get('cache_test', false) ? 'Working' : 'Not working'),
        ];
    }

    /**
     * Get pending queue jobs.
     */
    private function getPendingJobs()
    {
        try {
            // This is a simplified example - actual implementation depends on your queue driver
            return DB::table('jobs')->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get log files.
     */
    private function getLogFiles()
    {
        try {
            $logPath = storage_path('logs');
            $files = File::files($logPath);
            $logFiles = [];

            foreach ($files as $file) {
                $filename = $file->getFilename();
                if ($filename !== '.gitignore') {
                    $logFiles[] = [
                        'name' => $filename,
                        'size' => $this->formatSize($file->getSize()),
                        'modified' => date('Y-m-d H:i:s', $file->getMTime()),
                    ];
                }
            }

            return $logFiles;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get log content.
     */
    private function getLogContent($file)
    {
        try {
            $logPath = storage_path('logs/' . $file);
            
            if (!File::exists($logPath)) {
                return 'Log file not found.';
            }

            $content = File::get($logPath);
            
            // Get last 1000 lines for performance
            $lines = explode("\n", $content);
            $lines = array_slice($lines, -1000);
            
            return implode("\n", $lines);
        } catch (\Exception $e) {
            return 'Unable to read log file: ' . $e->getMessage();
        }
    }

    /**
     * Get composer packages.
     */
    private function getComposerPackages()
    {
        try {
            $composerLock = base_path('composer.lock');
            
            if (!File::exists($composerLock)) {
                return [];
            }

            $content = json_decode(File::get($composerLock), true);
            $packages = [];

            if (isset($content['packages'])) {
                foreach ($content['packages'] as $package) {
                    $packages[] = [
                        'name' => $package['name'],
                        'version' => $package['version'],
                        'description' => $package['description'] ?? '',
                        'license' => implode(', ', $package['license'] ?? []),
                    ];
                }
            }

            return $packages;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get npm packages.
     */
    private function getNpmPackages()
    {
        try {
            $packageJson = base_path('package.json');
            
            if (!File::exists($packageJson)) {
                return [];
            }

            $content = json_decode(File::get($packageJson), true);
            $packages = [];

            if (isset($content['dependencies'])) {
                foreach ($content['dependencies'] as $name => $version) {
                    $packages[] = [
                        'name' => $name,
                        'version' => $version,
                        'type' => 'dependencies',
                    ];
                }
            }

            if (isset($content['devDependencies'])) {
                foreach ($content['devDependencies'] as $name => $version) {
                    $packages[] = [
                        'name' => $name,
                        'version' => $version,
                        'type' => 'devDependencies',
                    ];
                }
            }

            return $packages;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get backup files.
     */
    private function getBackupFiles()
    {
        try {
            $backupPath = storage_path('backups');
            
            if (!File::exists($backupPath)) {
                File::makeDirectory($backupPath, 0755, true);
                return [];
            }

            $files = File::files($backupPath);
            $backups = [];

            foreach ($files as $file) {
                $backups[] = [
                    'name' => $file->getFilename(),
                    'size' => $this->formatSize($file->getSize()),
                    'modified' => date('Y-m-d H:i:s', $file->getMTime()),
                ];
            }

            // Sort by modified time descending
            usort($backups, function ($a, $b) {
                return strtotime($b['modified']) - strtotime($a['modified']);
            });

            return $backups;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Create database backup.
     */
    private function createDatabaseBackup()
    {
        try {
            $backupPath = storage_path('backups');
            
            if (!File::exists($backupPath)) {
                File::makeDirectory($backupPath, 0755, true);
            }

            $filename = 'database_backup_' . date('Y-m-d_H-i-s') . '.sql';
            $filepath = $backupPath . '/' . $filename;

            $db = DB::connection()->getDatabaseName();
            $username = env('DB_USERNAME');
            $password = env('DB_PASSWORD');
            $host = env('DB_HOST');

            // Create backup using mysqldump
            $command = sprintf(
                'mysqldump --host=%s --user=%s --password=%s %s > %s',
                escapeshellarg($host),
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($db),
                escapeshellarg($filepath)
            );

            exec($command);

            return true;
        } catch (\Exception $e) {
            Log::error('Error creating database backup: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create full backup.
     */
    private function createFullBackup()
    {
        try {
            $backupPath = storage_path('backups');
            
            if (!File::exists($backupPath)) {
                File::makeDirectory($backupPath, 0755, true);
            }

            $filename = 'full_backup_' . date('Y-m-d_H-i-s') . '.zip';
            $filepath = $backupPath . '/' . $filename;

            $zip = new \ZipArchive();
            $zip->open($filepath, \ZipArchive::CREATE);

            // Add database backup
            $this->createDatabaseBackup();
            $dbBackup = storage_path('backups/database_backup_' . date('Y-m-d_H-i-s') . '.sql');
            
            if (File::exists($dbBackup)) {
                $zip->addFile($dbBackup, 'database.sql');
                File::delete($dbBackup);
            }

            // Add storage files (optional - be careful with large files)
            // $this->addDirectoryToZip($zip, storage_path('app'), 'storage');

            $zip->close();

            return true;
        } catch (\Exception $e) {
            Log::error('Error creating full backup: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Add directory to zip.
     */
    private function addDirectoryToZip($zip, $dir, $zipSubDir = '')
    {
        try {
            $files = File::allFiles($dir);
            
            foreach ($files as $file) {
                $relativePath = $zipSubDir . '/' . $file->getRelativePathname();
                $zip->addFile($file->getRealPath(), $relativePath);
            }
        } catch (\Exception $e) {
            // Skip files that can't be added
        }
    }

    /**
     * Update .env file.
     */
    private function updateEnvFile($data)
    {
        try {
            $envPath = base_path('.env');
            
            if (!File::exists($envPath)) {
                throw new \Exception('.env file not found.');
            }

            $content = File::get($envPath);
            
            foreach ($data as $key => $value) {
                if ($value !== null) {
                    $content = preg_replace(
                        '/^' . $key . '=.*$/m',
                        $key . '=' . $this->formatEnvValue($value),
                        $content
                    );
                }
            }

            File::put($envPath, $content);
            
            // Reload config
            Artisan::call('config:clear');
            
            return true;
        } catch (\Exception $e) {
            Log::error('Error updating .env file: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Format .env value.
     */
    private function formatEnvValue($value)
    {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }
        
        if (strpos($value, ' ') !== false || strpos($value, '#') !== false) {
            return '"' . $value . '"';
        }
        
        return $value;
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
}