<?php

use App\Helpers\ArrayHelper;
use App\Helpers\CompanyHelper;
use App\Helpers\DateHelper;
use App\Helpers\FileHelper;
use App\Helpers\ImageHelper;
use App\Helpers\MenuHelper;
use App\Helpers\MoneyHelper;
use App\Helpers\NumberHelper;
use App\Helpers\SeoHelper;
use App\Helpers\SettingHelper;
use App\Helpers\StringHelper;
use App\Helpers\UrlHelper;
use App\Helpers\ValidationHelper;

/*
|--------------------------------------------------------------------------
| Helper Functions
|--------------------------------------------------------------------------
|
| This file contains global helper functions for the application.
| All functions are registered only if they don't already exist.
|
*/

// =========================================================================
// ACTIVE MENU HELPERS
// =========================================================================

if (!function_exists('active')) {
    /**
     * Check if the current route matches the given route name or pattern.
     * Used for active navigation menu items.
     * 
     * @param string|array $route The route name or pattern to match
     * @param string $activeClass The class to return when active (default: 'active')
     * @param string $inactiveClass The class to return when not active
     * @return string
     */
    function active($route, $activeClass = 'active', $inactiveClass = '')
    {
        if (is_array($route)) {
            foreach ($route as $r) {
                if (request()->routeIs($r) || request()->is($r)) {
                    return $activeClass;
                }
            }
            return $inactiveClass;
        }

        return request()->routeIs($route) || request()->is($route) 
            ? $activeClass 
            : $inactiveClass;
    }
}

if (!function_exists('isActive')) {
    /**
     * Check if current route is active (alias for active())
     */
    function isActive($route, $activeClass = 'active')
    {
        return active($route, $activeClass);
    }
}

if (!function_exists('activeMenu')) {
    /**
     * Check if menu is active (alias for active())
     */
    function activeMenu($route, $activeClass = 'active'): string
    {
        return active($route, $activeClass);
    }
}

if (!function_exists('setActive')) {
    /**
     * Set active class for navigation
     */
    function setActive($route, $activeClass = 'active', $inactiveClass = '')
    {
        return active($route, $activeClass, $inactiveClass);
    }
}

// =========================================================================
// MONEY HELPERS
// =========================================================================

if (!function_exists('money')) {
    /**
     * Format money/currency
     */
    function money($amount, $currency = null, $locale = null): string
    {
        return MoneyHelper::format($amount, $currency, $locale);
    }
}

if (!function_exists('currency_symbol')) {
    /**
     * Get currency symbol
     */
    function currency_symbol($currency = null): string
    {
        return MoneyHelper::getCurrencySymbol($currency ?? config('app.currency', 'USD'));
    }
}

// =========================================================================
// SETTING HELPERS
// =========================================================================

if (!function_exists('setting')) {
    /**
     * Get application setting
     */
    function setting($key, $default = null)
    {
        return SettingHelper::get($key, $default);
    }
}

// =========================================================================
// COMPANY HELPERS
// =========================================================================

if (!function_exists('company')) {
    /**
     * Get company information
     */
    function company($key = null)
    {
        return CompanyHelper::get($key);
    }
}

if (!function_exists('company_name')) {
    /**
     * Get company name
     */
    function company_name(): string
    {
        return CompanyHelper::name();
    }
}

if (!function_exists('company_logo')) {
    /**
     * Get company logo URL
     */
    function company_logo(): string
    {
        return CompanyHelper::logo();
    }
}

if (!function_exists('company_address')) {
    /**
     * Get company address
     */
    function company_address(): string
    {
        return CompanyHelper::fullAddress();
    }
}

// =========================================================================
// SEO HELPERS
// =========================================================================

if (!function_exists('seo')) {
    /**
     * Get SEO data
     */
    function seo($key = null)
    {
        return SeoHelper::get($key);
    }
}

if (!function_exists('seo_meta_tags')) {
    /**
     * Generate SEO meta tags
     */
    function seo_meta_tags(): string
    {
        return SeoHelper::generate();
    }
}

// =========================================================================
// IMAGE HELPERS
// =========================================================================

if (!function_exists('uploadImage')) {
    /**
     * Upload and process image
     */
    function uploadImage($file, $path = 'uploads', $options = [])
    {
        return ImageHelper::upload($file, $path, $options);
    }
}

if (!function_exists('image_url')) {
    /**
     * Get image URL
     */
    function image_url($path, $disk = 'public'): ?string
    {
        if (!$path) {
            return null;
        }
        return asset($path);
    }
}

// =========================================================================
// DATE HELPERS
// =========================================================================

if (!function_exists('formatDate')) {
    /**
     * Format date
     */
    function formatDate($date, $format = null): string
    {
        return DateHelper::format($date, $format);
    }
}

if (!function_exists('formatDateTime')) {
    /**
     * Format date time
     */
    function formatDateTime($date): string
    {
        return DateHelper::formatDateTime($date);
    }
}

if (!function_exists('timeAgo')) {
    /**
     * Get time ago string
     */
    function timeAgo($date): string
    {
        return DateHelper::timeAgo($date);
    }
}

if (!function_exists('daysBetween')) {
    /**
     * Get days between dates
     */
    function daysBetween($start, $end): int
    {
        return DateHelper::daysBetween($start, $end);
    }
}

if (!function_exists('isToday')) {
    /**
     * Check if date is today
     */
    function isToday($date): bool
    {
        return DateHelper::isToday($date);
    }
}

if (!function_exists('isPast')) {
    /**
     * Check if date is in the past
     */
    function isPast($date): bool
    {
        return DateHelper::isPast($date);
    }
}

// =========================================================================
// STRING HELPERS
// =========================================================================

if (!function_exists('strLimit')) {
    /**
     * Limit string
     */
    function strLimit($string, $limit = 100, $end = '...'): string
    {
        return StringHelper::limit($string, $limit, $end);
    }
}

if (!function_exists('strSlug')) {
    /**
     * Create URL slug
     */
    function strSlug($string): string
    {
        return StringHelper::slug($string);
    }
}

if (!function_exists('strRandom')) {
    /**
     * Generate random string
     */
    function strRandom($length = 16): string
    {
        return StringHelper::random($length);
    }
}

if (!function_exists('strCamel')) {
    /**
     * Convert to camel case
     */
    function strCamel($string): string
    {
        return StringHelper::camel($string);
    }
}

if (!function_exists('strSnake')) {
    /**
     * Convert to snake case
     */
    function strSnake($string): string
    {
        return StringHelper::snake($string);
    }
}

if (!function_exists('strKebab')) {
    /**
     * Convert to kebab case
     */
    function strKebab($string): string
    {
        return StringHelper::kebab($string);
    }
}

if (!function_exists('strContains')) {
    /**
     * Check if string contains substring
     */
    function strContains($haystack, $needle): bool
    {
        return StringHelper::contains($haystack, $needle);
    }
}

if (!function_exists('maskEmail')) {
    /**
     * Mask email address
     */
    function maskEmail($email): string
    {
        return StringHelper::maskEmail($email);
    }
}

// =========================================================================
// ARRAY HELPERS
// =========================================================================

if (!function_exists('arrayGet')) {
    /**
     * Get array value using dot notation
     */
    function arrayGet($array, $key, $default = null)
    {
        return ArrayHelper::get($array, $key, $default);
    }
}

if (!function_exists('arrayExcept')) {
    /**
     * Remove keys from array
     */
    function arrayExcept($array, $keys)
    {
        return ArrayHelper::except($array, $keys);
    }
}

if (!function_exists('arrayOnly')) {
    /**
     * Get only specified keys from array
     */
    function arrayOnly($array, $keys)
    {
        return ArrayHelper::only($array, $keys);
    }
}

if (!function_exists('arrayHas')) {
    /**
     * Check if array has key
     */
    function arrayHas($array, $key): bool
    {
        return ArrayHelper::has($array, $key);
    }
}

if (!function_exists('arraySortBy')) {
    /**
     * Sort array by key
     */
    function arraySortBy($array, $key)
    {
        return ArrayHelper::sortBy($array, $key);
    }
}

if (!function_exists('arrayGroupBy')) {
    /**
     * Group array by key
     */
    function arrayGroupBy($array, $key)
    {
        return ArrayHelper::groupBy($array, $key);
    }
}

// =========================================================================
// FILE HELPERS
// =========================================================================

if (!function_exists('fileSize')) {
    /**
     * Format file size
     */
    function fileSize($bytes, $precision = 2): string
    {
        return FileHelper::formatSize($bytes, $precision);
    }
}

if (!function_exists('isImage')) {
    /**
     * Check if file is image
     */
    function isImage($filename): bool
    {
        return FileHelper::isImage($filename);
    }
}

if (!function_exists('isVideo')) {
    /**
     * Check if file is video
     */
    function isVideo($filename): bool
    {
        return FileHelper::isVideo($filename);
    }
}

if (!function_exists('isDocument')) {
    /**
     * Check if file is document
     */
    function isDocument($filename): bool
    {
        return FileHelper::isDocument($filename);
    }
}

if (!function_exists('fileExtension')) {
    /**
     * Get file extension
     */
    function fileExtension($filename): string
    {
        return FileHelper::extension($filename);
    }
}

// =========================================================================
// NUMBER HELPERS
// =========================================================================

if (!function_exists('numberFormat')) {
    /**
     * Format number
     */
    function numberFormat($number, $decimals = 2, $decPoint = '.', $thousandsSep = ','): string
    {
        return NumberHelper::format($number, $decimals, $decPoint, $thousandsSep);
    }
}

if (!function_exists('percentage')) {
    /**
     * Calculate percentage
     */
    function percentage($value, $total, $decimals = 2): float
    {
        return NumberHelper::percentage($value, $total, $decimals);
    }
}

if (!function_exists('ordinal')) {
    /**
     * Format as ordinal number (1st, 2nd, 3rd)
     */
    function ordinal($number): string
    {
        return NumberHelper::ordinal($number);
    }
}

if (!function_exists('abbreviateNumber')) {
    /**
     * Get number abbreviation (K, M, B)
     */
    function abbreviateNumber($number, $precision = 1): string
    {
        return NumberHelper::abbreviate($number, $precision);
    }
}

// =========================================================================
// URL HELPERS
// =========================================================================

if (!function_exists('urlCurrent')) {
    /**
     * Get current URL
     */
    function urlCurrent(): string
    {
        return UrlHelper::current();
    }
}

if (!function_exists('urlPrevious')) {
    /**
     * Get previous URL
     */
    function urlPrevious($fallback = '/'): string
    {
        return UrlHelper::previous($fallback);
    }
}

if (!function_exists('urlFull')) {
    /**
     * Get full current URL
     */
    function urlFull(): string
    {
        return UrlHelper::full();
    }
}

if (!function_exists('urlAddParams')) {
    /**
     * Add query parameters to URL
     */
    function urlAddParams($url, $params): string
    {
        return UrlHelper::addParams($url, $params);
    }
}

if (!function_exists('urlRemoveParams')) {
    /**
     * Remove query parameters from URL
     */
    function urlRemoveParams($url, $params): string
    {
        return UrlHelper::removeParams($url, $params);
    }
}

// =========================================================================
// VALIDATION HELPERS
// =========================================================================

if (!function_exists('validateEmail')) {
    /**
     * Validate email
     */
    function validateEmail($email): bool
    {
        return ValidationHelper::isEmail($email);
    }
}

if (!function_exists('validatePhone')) {
    /**
     * Validate phone number
     */
    function validatePhone($phone): bool
    {
        return ValidationHelper::isPhone($phone);
    }
}

if (!function_exists('validateUrl')) {
    /**
     * Validate URL
     */
    function validateUrl($url): bool
    {
        return ValidationHelper::isUrl($url);
    }
}

if (!function_exists('validateDomain')) {
    /**
     * Validate domain
     */
    function validateDomain($domain): bool
    {
        return ValidationHelper::isDomain($domain);
    }
}

if (!function_exists('validateDate')) {
    /**
     * Validate date format
     */
    function validateDate($date, $format = 'Y-m-d'): bool
    {
        return ValidationHelper::isDate($date, $format);
    }
}

if (!function_exists('validateJson')) {
    /**
     * Validate JSON
     */
    function validateJson($string): bool
    {
        return ValidationHelper::isJson($string);
    }
}

if (!function_exists('validateHexColor')) {
    /**
     * Validate hex color
     */
    function validateHexColor($color): bool
    {
        return ValidationHelper::isHexColor($color);
    }
}

// =========================================================================
// MISC HELPERS
// =========================================================================

if (!function_exists('env')) {
    /**
     * Get environment variable (Laravel already has this)
     * This is just a fallback
     */
    function env($key, $default = null)
    {
        return \Illuminate\Support\Env::get($key, $default);
    }
}

if (!function_exists('isProduction')) {
    /**
     * Check if application is in production
     */
    function isProduction(): bool
    {
        return app()->environment('production');
    }
}

if (!function_exists('isLocal')) {
    /**
     * Check if application is in local environment
     */
    function isLocal(): bool
    {
        return app()->environment('local');
    }
}