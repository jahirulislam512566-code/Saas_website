<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;

class UrlHelper
{
    /**
     * Get current URL
     */
    public static function current(): string
    {
        return URL::current();
    }

    /**
     * Get full current URL
     */
    public static function full(): string
    {
        return URL::full();
    }

    /**
     * Get previous URL
     */
    public static function previous($fallback = '/'): string
    {
        return URL::previous($fallback);
    }

    /**
     * Get route URL
     */
    public static function route($name, $parameters = [], $absolute = true): string
    {
        return route($name, $parameters, $absolute);
    }

    /**
     * Generate signed URL
     */
    public static function signed($name, $parameters = [], $expiration = null): string
    {
        return URL::signedRoute($name, $parameters, $expiration);
    }

    /**
     * Check if URL is active
     */
    public static function isActive($url, $exact = false): bool
    {
        $current = self::current();

        if ($exact) {
            return $current === $url;
        }

        return strpos($current, $url) !== false;
    }

    /**
     * Add query parameters to URL
     */
    public static function addParams($url, $params): string
    {
        $parsed = parse_url($url);
        $query = [];

        if (isset($parsed['query'])) {
            parse_str($parsed['query'], $query);
        }

        $query = array_merge($query, $params);

        $newQuery = http_build_query($query);

        return $parsed['scheme'] . '://' .
               $parsed['host'] .
               (isset($parsed['port']) ? ':' . $parsed['port'] : '') .
               ($parsed['path'] ?? '') .
               ($newQuery ? '?' . $newQuery : '') .
               (isset($parsed['fragment']) ? '#' . $parsed['fragment'] : '');
    }

    /**
     * Remove query parameters from URL
     */
    public static function removeParams($url, $params): string
    {
        $parsed = parse_url($url);
        $query = [];

        if (isset($parsed['query'])) {
            parse_str($parsed['query'], $query);
        }

        foreach ($params as $param) {
            unset($query[$param]);
        }

        $newQuery = http_build_query($query);

        return $parsed['scheme'] . '://' .
               $parsed['host'] .
               (isset($parsed['port']) ? ':' . $parsed['port'] : '') .
               ($parsed['path'] ?? '') .
               ($newQuery ? '?' . $newQuery : '') .
               (isset($parsed['fragment']) ? '#' . $parsed['fragment'] : '');
    }

    /**
     * Get URL segments
     */
    public static function segments(): array
    {
        return Request::segments();
    }

    /**
     * Get specific URL segment
     */
    public static function segment($index, $default = null)
    {
        return Request::segment($index, $default);
    }

    /**
     * Check if URL has segment
     */
    public static function hasSegment($segment): bool
    {
        return in_array($segment, self::segments());
    }

    /**
     * Get query parameter
     */
    public static function query($key, $default = null)
    {
        return Request::query($key, $default);
    }

    /**
     * Check if URL is secure
     */
    public static function isSecure(): bool
    {
        return Request::secure();
    }

    /**
     * Get URL without protocol
     */
    public static function withoutProtocol($url): string
    {
        return preg_replace('/^https?:\/\//', '', $url);
    }

    /**
     * Convert to absolute URL
     */
    public static function toAbsolute($path): string
    {
        return URL::to($path);
    }

    /**
     * Convert to asset URL
     */
    public static function asset($path): string
    {
        return asset($path);
    }
}