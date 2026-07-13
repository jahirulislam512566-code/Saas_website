<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

if (!function_exists('setting')) {
    /**
     * Get a setting value by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting($key, $default = null)
    {
        try {
            $tenantId = auth()->check() ? auth()->user()->tenant_id : 1;
            $cacheKey = "setting_{$tenantId}_{$key}";
            
            return Cache::remember($cacheKey, 3600, function () use ($key, $default, $tenantId) {
                $setting = Setting::where('tenant_id', $tenantId)
                    ->where('key', $key)
                    ->first();
                
                return $setting ? $setting->value : $default;
            });
        } catch (\Exception $e) {
            return $default;
        }
    }
}

if (!function_exists('settings')) {
    /**
     * Get all settings for a group.
     *
     * @param string|null $group
     * @return array
     */
    function settings($group = null)
    {
        try {
            $tenantId = auth()->check() ? auth()->user()->tenant_id : 1;
            $cacheKey = $group ? "settings_group_{$group}_{$tenantId}" : "settings_all_{$tenantId}";
            
            return Cache::remember($cacheKey, 3600, function () use ($group, $tenantId) {
                $query = Setting::where('tenant_id', $tenantId);
                
                if ($group) {
                    $query->where('group', $group);
                }
                
                return $query->pluck('value', 'key')->toArray();
            });
        } catch (\Exception $e) {
            return [];
        }
    }
}

if (!function_exists('setting_update')) {
    /**
     * Update a setting value.
     *
     * @param string $key
     * @param mixed $value
     * @param string $group
     * @return bool
     */
    function setting_update($key, $value, $group = 'general')
    {
        try {
            $tenantId = auth()->check() ? auth()->user()->tenant_id : 1;
            
            Setting::updateOrCreate(
                [
                    'tenant_id' => $tenantId,
                    'key' => $key,
                ],
                [
                    'value' => $value,
                    'group' => $group,
                    'type' => gettype($value),
                ]
            );
            
            // Clear cache
            Cache::forget("setting_{$tenantId}_{$key}");
            Cache::forget("settings_group_{$group}_{$tenantId}");
            Cache::forget("settings_all_{$tenantId}");
            
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}