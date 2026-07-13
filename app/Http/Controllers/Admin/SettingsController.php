<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class SettingsController extends Controller
{
    /**
     * Display the settings dashboard.
     */
    public function index()
    {
        // Get all settings grouped by category
        $settings = $this->getAllSettings();
        
        // Get environment info
        $environment = [
            'app_name' => config('app.name'),
            'app_env' => config('app.env'),
            'app_debug' => config('app.debug'),
            'app_url' => config('app.url'),
            'timezone' => config('app.timezone'),
            'locale' => config('app.locale'),
        ];
        
        return view('admin.settings.index', compact('settings', 'environment'));
    }

    /**
     * Show general settings form.
     */
    public function general()
    {
        $settings = $this->getSettingsByGroup('general');
        return view('admin.settings.general', compact('settings'));
    }

    /**
     * Update general settings.
     */
    public function updateGeneral(Request $request)
    {
        $validated = $request->validate([
            'site_name' => ['required', 'string', 'max:255'],
            'site_description' => ['nullable', 'string', 'max:500'],
            'timezone' => ['required', 'string', 'timezone'],
            'locale' => ['required', 'string', 'size:2'],
            'currency' => ['required', 'string', 'size:3'],
            'currency_symbol' => ['required', 'string', 'max:5'],
            'date_format' => ['required', 'string'],
            'time_format' => ['required', 'string'],
        ]);

        foreach ($validated as $key => $value) {
            $this->updateSetting($key, $value, 'general');
        }

        // Clear cache
        $this->clearSettingsCache();

        return redirect()->route('admin.settings.general')
            ->with('success', 'General settings updated successfully.');
    }

    /**
     * Show payment settings form.
     */
    public function payment()
    {
        $settings = $this->getSettingsByGroup('payment');
        return view('admin.settings.payment', compact('settings'));
    }

    /**
     * Update payment settings.
     */
    public function updatePayment(Request $request)
    {
        $validated = $request->validate([
            'stripe_key' => ['nullable', 'string', 'max:255'],
            'stripe_secret' => ['nullable', 'string', 'max:255'],
            'stripe_webhook_secret' => ['nullable', 'string', 'max:255'],
            'paypal_client_id' => ['nullable', 'string', 'max:255'],
            'paypal_secret' => ['nullable', 'string', 'max:255'],
            'paypal_mode' => ['nullable', 'string', 'in:sandbox,live'],
            'razorpay_key' => ['nullable', 'string', 'max:255'],
            'razorpay_secret' => ['nullable', 'string', 'max:255'],
            'default_currency' => ['required', 'string', 'size:3'],
            'tax_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'enable_tax' => ['nullable', 'boolean'],
        ]);

        foreach ($validated as $key => $value) {
            $this->updateSetting($key, $value, 'payment');
        }

        $this->clearSettingsCache();

        return redirect()->route('admin.settings.payment')
            ->with('success', 'Payment settings updated successfully.');
    }

    /**
     * Show SMTP settings form.
     */
    public function smtp()
    {
        $settings = $this->getSettingsByGroup('smtp');
        return view('admin.settings.smtp', compact('settings'));
    }

    /**
     * Update SMTP settings.
     */
    public function updateSmtp(Request $request)
    {
        $validated = $request->validate([
            'mail_host' => ['required', 'string', 'max:255'],
            'mail_port' => ['required', 'integer', 'min:1', 'max:65535'],
            'mail_username' => ['nullable', 'string', 'max:255'],
            'mail_password' => ['nullable', 'string', 'max:255'],
            'mail_encryption' => ['nullable', 'string', 'in:tls,ssl'],
            'mail_from_address' => ['required', 'email', 'max:255'],
            'mail_from_name' => ['required', 'string', 'max:255'],
        ]);

        // Update .env file
        $this->updateEnvFile($validated);

        foreach ($validated as $key => $value) {
            $this->updateSetting($key, $value, 'smtp');
        }

        $this->clearSettingsCache();

        return redirect()->route('admin.settings.smtp')
            ->with('success', 'SMTP settings updated successfully.');
    }

    /**
     * Test SMTP connection.
     */
    public function testSmtp(Request $request)
    {
        try {
            $request->validate([
                'email' => ['required', 'email'],
            ]);

            // Send test email
            \Mail::raw('This is a test email from your application.', function ($message) use ($request) {
                $message->to($request->email)
                        ->subject('SMTP Test Email');
            });

            return response()->json([
                'success' => true,
                'message' => 'SMTP connection successful! Test email sent to ' . $request->email,
            ]);
        } catch (\Exception $e) {
            Log::error('SMTP test failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'SMTP connection failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show SEO settings form.
     */
    public function seo()
    {
        $settings = $this->getSettingsByGroup('seo');
        return view('admin.settings.seo', compact('settings'));
    }

    /**
     * Update SEO settings.
     */
    public function updateSeo(Request $request)
    {
        $validated = $request->validate([
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'og_title' => ['nullable', 'string', 'max:255'],
            'og_description' => ['nullable', 'string', 'max:500'],
            'og_image' => ['nullable', 'string', 'max:255'],
            'twitter_title' => ['nullable', 'string', 'max:255'],
            'twitter_description' => ['nullable', 'string', 'max:500'],
            'twitter_image' => ['nullable', 'string', 'max:255'],
            'robots_txt' => ['nullable', 'string'],
            'sitemap_enabled' => ['nullable', 'boolean'],
            'google_analytics_id' => ['nullable', 'string', 'max:255'],
            'google_tag_manager_id' => ['nullable', 'string', 'max:255'],
            'verification_google' => ['nullable', 'string', 'max:255'],
            'verification_bing' => ['nullable', 'string', 'max:255'],
        ]);

        foreach ($validated as $key => $value) {
            $this->updateSetting($key, $value, 'seo');
        }

        // Generate sitemap if enabled
        if (isset($validated['sitemap_enabled']) && $validated['sitemap_enabled']) {
            Artisan::call('sitemap:generate');
        }

        $this->clearSettingsCache();

        return redirect()->route('admin.settings.seo')
            ->with('success', 'SEO settings updated successfully.');
    }

    /**
     * Show social settings form.
     */
    public function social()
    {
        $settings = $this->getSettingsByGroup('social');
        return view('admin.settings.social', compact('settings'));
    }

    /**
     * Update social settings.
     */
    public function updateSocial(Request $request)
    {
        $validated = $request->validate([
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'twitter_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'youtube_url' => ['nullable', 'url', 'max:255'],
            'pinterest_url' => ['nullable', 'url', 'max:255'],
            'github_url' => ['nullable', 'url', 'max:255'],
            'tiktok_url' => ['nullable', 'url', 'max:255'],
            'whatsapp_number' => ['nullable', 'string', 'max:20'],
            'telegram_username' => ['nullable', 'string', 'max:255'],
            'social_login_enabled' => ['nullable', 'boolean'],
            'social_login_providers' => ['nullable', 'array'],
        ]);

        foreach ($validated as $key => $value) {
            $this->updateSetting($key, $value, 'social');
        }

        $this->clearSettingsCache();

        return redirect()->route('admin.settings.social')
            ->with('success', 'Social settings updated successfully.');
    }

    /**
     * Show system settings form.
     */
    public function system()
    {
        $settings = $this->getSettingsByGroup('system');
        
        // Get system info
        $systemInfo = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
        ];
        
        return view('admin.settings.system', compact('settings', 'systemInfo'));
    }

    /**
     * Update system settings.
     */
    public function updateSystem(Request $request)
    {
        $validated = $request->validate([
            'maintenance_mode' => ['nullable', 'boolean'],
            'maintenance_message' => ['nullable', 'string', 'max:500'],
            'allow_registration' => ['nullable', 'boolean'],
            'verify_email' => ['nullable', 'boolean'],
            'enable_2fa' => ['nullable', 'boolean'],
            'session_lifetime' => ['required', 'integer', 'min:1', 'max:1440'],
            'max_login_attempts' => ['nullable', 'integer', 'min:1', 'max:100'],
            'lockout_time' => ['nullable', 'integer', 'min:1', 'max:1440'],
            'log_level' => ['nullable', 'string', 'in:debug,info,notice,warning,error,critical,alert,emergency'],
            'enable_api' => ['nullable', 'boolean'],
            'rate_limit' => ['nullable', 'integer', 'min:1', 'max:1000'],
        ]);

        foreach ($validated as $key => $value) {
            $this->updateSetting($key, $value, 'system');
        }

        // Handle maintenance mode
        if (isset($validated['maintenance_mode']) && $validated['maintenance_mode']) {
            Artisan::call('down', ['--message' => $validated['maintenance_message'] ?? 'Maintenance mode enabled.']);
        } else {
            Artisan::call('up');
        }

        $this->clearSettingsCache();

        return redirect()->route('admin.settings.system')
            ->with('success', 'System settings updated successfully.');
    }

    /**
     * Show environment settings.
     */
    public function environment()
    {
        $environment = [
            'APP_NAME' => env('APP_NAME'),
            'APP_ENV' => env('APP_ENV'),
            'APP_DEBUG' => env('APP_DEBUG'),
            'APP_URL' => env('APP_URL'),
            'DB_CONNECTION' => env('DB_CONNECTION'),
            'DB_HOST' => env('DB_HOST'),
            'DB_PORT' => env('DB_PORT'),
            'DB_DATABASE' => env('DB_DATABASE'),
            'CACHE_DRIVER' => env('CACHE_DRIVER'),
            'SESSION_DRIVER' => env('SESSION_DRIVER'),
            'QUEUE_CONNECTION' => env('QUEUE_CONNECTION'),
            'MAIL_MAILER' => env('MAIL_MAILER'),
        ];
        
        return view('admin.settings.environment', compact('environment'));
    }

    /**
     * Clear cache.
     */
    public function clearCache(Request $request)
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
            
            $this->clearSettingsCache();

            return response()->json([
                'success' => true,
                'message' => 'All caches cleared successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error('Cache clear failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear caches: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Optimize application.
     */
    public function optimize(Request $request)
    {
        try {
            Artisan::call('optimize');
            
            return response()->json([
                'success' => true,
                'message' => 'Application optimized successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error('Optimize failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to optimize: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Reset settings to default.
     */
    public function resetDefault(Request $request)
    {
        try {
            Setting::where('tenant_id', auth()->user()->tenant_id)->delete();
            $this->clearSettingsCache();
            
            return response()->json([
                'success' => true,
                'message' => 'Settings reset to default successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error('Reset settings failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to reset settings: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get all settings.
     */
    protected function getAllSettings()
    {
        $cacheKey = 'settings_all_' . auth()->user()->tenant_id;
        
        return Cache::remember($cacheKey, 3600, function () {
            return Setting::where('tenant_id', auth()->user()->tenant_id)
                ->get()
                ->groupBy('group')
                ->map(function ($items) {
                    return $items->pluck('value', 'key');
                });
        });
    }

    /**
     * Get settings by group.
     */
    protected function getSettingsByGroup($group)
    {
        $cacheKey = 'settings_group_' . $group . '_' . auth()->user()->tenant_id;
        
        return Cache::remember($cacheKey, 3600, function () use ($group) {
            return Setting::where('tenant_id', auth()->user()->tenant_id)
                ->where('group', $group)
                ->pluck('value', 'key')
                ->toArray();
        });
    }

    /**
     * Update a single setting.
     */
    protected function updateSetting($key, $value, $group = 'general')
    {
        Setting::updateOrCreate(
            [
                'tenant_id' => auth()->user()->tenant_id,
                'key' => $key,
            ],
            [
                'value' => $value,
                'group' => $group,
                'type' => $this->getSettingType($value),
            ]
        );
    }

    /**
     * Get setting type.
     */
    protected function getSettingType($value)
    {
        if (is_bool($value)) return 'boolean';
        if (is_numeric($value)) return 'numeric';
        if (is_array($value)) return 'array';
        if (is_null($value)) return 'null';
        return 'string';
    }

    /**
     * Clear settings cache.
     */
    protected function clearSettingsCache()
    {
        $tenantId = auth()->user()->tenant_id;
        Cache::forget('settings_all_' . $tenantId);
        Cache::forget('settings_group_general_' . $tenantId);
        Cache::forget('settings_group_payment_' . $tenantId);
        Cache::forget('settings_group_smtp_' . $tenantId);
        Cache::forget('settings_group_seo_' . $tenantId);
        Cache::forget('settings_group_social_' . $tenantId);
        Cache::forget('settings_group_system_' . $tenantId);
    }

    /**
     * Update .env file.
     */
    protected function updateEnvFile($data)
    {
        $envFile = base_path('.env');
        $envContent = file_get_contents($envFile);
        
        foreach ($data as $key => $value) {
            $envKey = strtoupper($key);
            $pattern = "/^{$envKey}=.*/m";
            $replacement = "{$envKey}={$value}";
            
            if (preg_match($pattern, $envContent)) {
                $envContent = preg_replace($pattern, $replacement, $envContent);
            } else {
                $envContent .= "\n{$replacement}";
            }
        }
        
        file_put_contents($envFile, $envContent);
    }

    /**
     * Get settings via API.
     */
    public function getSettingsJson(Request $request)
    {
        $settings = $this->getAllSettings();
        return response()->json($settings);
    }

    /**
     * Update settings via API.
     */
    public function updateSettingsApi(Request $request)
    {
        $validated = $request->validate([
            'settings' => ['required', 'array'],
            'group' => ['nullable', 'string', 'default:general'],
        ]);

        foreach ($validated['settings'] as $key => $value) {
            $this->updateSetting($key, $value, $validated['group']);
        }

        $this->clearSettingsCache();

        return response()->json([
            'success' => true,
            'message' => 'Settings updated successfully.',
        ]);
    }

    /**
 * Show security settings form.
 */
public function security()
{
    $settings = $this->getSettingsByGroup('security');
    $securityScore = $this->calculateSecurityScore();
    $failedLoginsToday = $this->getFailedLoginsToday();
    
    return view('admin.settings.security', compact('settings', 'securityScore', 'failedLoginsToday'));
}

/**
 * Update security settings.
 */
public function updateSecurity(Request $request)
{
    $validated = $request->validate([
        'enable_2fa' => ['nullable', 'boolean'],
        'force_2fa_admin' => ['nullable', 'boolean'],
        'max_login_attempts' => ['nullable', 'integer', 'min:1', 'max:20'],
        'lockout_time' => ['nullable', 'integer', 'min:1', 'max:1440'],
        'password_min_length' => ['nullable', 'integer', 'min:6', 'max:20'],
        'password_expiry_days' => ['nullable', 'integer', 'min:0', 'max:365'],
        'require_uppercase' => ['nullable', 'boolean'],
        'require_numbers' => ['nullable', 'boolean'],
        'require_symbols' => ['nullable', 'boolean'],
        'session_lifetime' => ['nullable', 'integer', 'min:5', 'max:1440'],
        'session_idle_timeout' => ['nullable', 'integer', 'min:1', 'max:1440'],
        'single_session' => ['nullable', 'boolean'],
        'session_encryption' => ['nullable', 'boolean'],
    ]);

    foreach ($validated as $key => $value) {
        $this->updateSetting($key, $value, 'security');
    }

    $this->clearSettingsCache();

    return redirect()->route('admin.settings.security')
        ->with('success', 'Security settings updated successfully.');
}

/**
 * Show system settings form.
 */
// public function system()
// {
//     $settings = $this->getSettingsByGroup('system');
//     $systemStatus = $this->getSystemStatus();
//     $uptime = $this->getUptime();
//     $queueWorkers = $this->getQueueWorkers();
    
//     return view('admin.settings.system', compact('settings', 'systemStatus', 'uptime', 'queueWorkers'));
// }

/**
 * Update system settings.
 */
// public function updateSystem(Request $request)
// {
//     $validated = $request->validate([
//         'maintenance_mode' => ['nullable', 'boolean'],
//         'maintenance_message' => ['nullable', 'string', 'max:255'],
//         'maintenance_retry' => ['nullable', 'integer', 'min:0', 'max:3600'],
//         'allow_registration' => ['nullable', 'boolean'],
//         'verify_email' => ['nullable', 'boolean'],
//         'enable_backup' => ['nullable', 'boolean'],
//         'backup_frequency' => ['nullable', 'string', 'in:daily,weekly,monthly'],
//         'backup_retention' => ['nullable', 'integer', 'min:1', 'max:365'],
//         'log_level' => ['nullable', 'string', 'in:debug,info,notice,warning,error,critical,alert,emergency'],
//         'log_retention' => ['nullable', 'integer', 'min:1', 'max:365'],
//     ]);

//     foreach ($validated as $key => $value) {
//         $this->updateSetting($key, $value, 'system');
//     }

//     // Handle maintenance mode
//     if (isset($validated['maintenance_mode']) && $validated['maintenance_mode']) {
//         Artisan::call('down', [
//             '--message' => $validated['maintenance_message'] ?? 'Be right back!',
//             '--retry' => $validated['maintenance_retry'] ?? 60,
//         ]);
//     } else {
//         Artisan::call('up');
//     }

//     $this->clearSettingsCache();

//     return redirect()->route('admin.settings.system')
//         ->with('success', 'System settings updated successfully.');
// }

/**
 * Calculate security score.
 */
protected function calculateSecurityScore()
{
    $score = 0;
    $total = 10;
    
    // Check 2FA
    if (setting('enable_2fa', false)) $score++;
    if (setting('force_2fa_admin', false)) $score++;
    
    // Password policies
    if (setting('password_min_length', 8) >= 8) $score++;
    if (setting('require_uppercase', true)) $score++;
    if (setting('require_numbers', true)) $score++;
    if (setting('require_symbols', false)) $score++;
    
    // Session security
    if (setting('single_session', true)) $score++;
    if (setting('session_encryption', true)) $score++;
    if (request()->secure()) $score++;
    
    return round(($score / $total) * 100);
}

/**
 * Get failed logins today.
 */
protected function getFailedLoginsToday()
{
    // Implement based on your login tracking
    return 0;
}

/**
 * Get system status.
 */
protected function getSystemStatus()
{
    try {
        DB::connection()->getPdo();
        return 'Operational';
    } catch (\Exception $e) {
        return 'Degraded';
    }
}

/**
 * Get uptime.
 */
protected function getUptime()
{
    // For Unix systems
    if (PHP_OS_FAMILY === 'Unix') {
        $uptime = @file_get_contents('/proc/uptime');
        if ($uptime) {
            $seconds = (int) explode(' ', $uptime)[0];
            $days = floor($seconds / 86400);
            $hours = floor(($seconds % 86400) / 3600);
            $minutes = floor(($seconds % 3600) / 60);
            
            if ($days > 0) {
                return "{$days}d {$hours}h {$minutes}m";
            } elseif ($hours > 0) {
                return "{$hours}h {$minutes}m";
            } else {
                return "{$minutes}m";
            }
        }
    }
    return 'N/A';
}

/**
 * Get queue workers.
 */
protected function getQueueWorkers()
{
    // For Unix systems
    if (PHP_OS_FAMILY === 'Unix') {
        $output = shell_exec('ps aux | grep "queue:work" | grep -v grep | wc -l');
        return (int) trim($output);
    }
    return 0;
}

/**
 * Export environment settings.
 */
public function exportEnv()
{
    $env = [
        'APP_NAME' => env('APP_NAME'),
        'APP_ENV' => env('APP_ENV'),
        'APP_DEBUG' => env('APP_DEBUG'),
        'APP_URL' => env('APP_URL'),
        'APP_TIMEZONE' => env('APP_TIMEZONE'),
        'APP_LOCALE' => env('APP_LOCALE'),
        'DB_CONNECTION' => env('DB_CONNECTION'),
        'DB_HOST' => env('DB_HOST'),
        'DB_PORT' => env('DB_PORT'),
        'DB_DATABASE' => env('DB_DATABASE'),
        'CACHE_DRIVER' => env('CACHE_DRIVER'),
        'SESSION_DRIVER' => env('SESSION_DRIVER'),
        'QUEUE_CONNECTION' => env('QUEUE_CONNECTION'),
    ];
    
    $content = "# Environment Configuration\n";
    $content .= "# Generated: " . now() . "\n\n";
    
    foreach ($env as $key => $value) {
        $content .= "{$key}={$value}\n";
    }
    
    return response($content)
        ->header('Content-Type', 'text/plain')
        ->header('Content-Disposition', 'attachment; filename="env_' . date('Y-m-d') . '.txt"');
}

}