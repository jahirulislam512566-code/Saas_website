<?php

namespace App\Helpers;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingHelper
{
    /**
     * Settings cache
     */
    protected static $settings = null;

    /**
     * Get setting value
     */
    public static function get($key, $default = null)
    {
        $settings = self::loadSettings();

        return $settings[$key] ?? $default;
    }

    /**
     * Set setting value
     */
    public static function set($key, $value): bool
    {
        try {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );

            self::clearCache();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Load all settings
     */
    protected static function loadSettings()
    {
        if (self::$settings !== null) {
            return self::$settings;
        }

        self::$settings = Cache::remember('app_settings', 3600, function () {
            $settings = Setting::all();
            $result = [];

            foreach ($settings as $setting) {
                $result[$setting->key] = $setting->value;
            }

            return $result;
        });

        return self::$settings;
    }

    /**
     * Clear settings cache
     */
    public static function clearCache(): void
    {
        Cache::forget('app_settings');
        self::$settings = null;
    }

    /**
     * Get all settings
     */
    public static function all(): array
    {
        return self::loadSettings();
    }

    /**
     * Get settings by group
     */
    public static function group($group): array
    {
        $settings = self::loadSettings();
        $result = [];

        foreach ($settings as $key => $value) {
            if (strpos($key, $group . '.') === 0) {
                $result[str_replace($group . '.', '', $key)] = $value;
            }
        }

        return $result;
    }
}