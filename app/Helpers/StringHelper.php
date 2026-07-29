<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class StringHelper
{
    /**
     * Limit string
     */
    public static function limit($string, $limit = 100, $end = '...'): string
    {
        return Str::limit($string, $limit, $end);
    }

    /**
     * Create URL slug
     */
    public static function slug($string): string
    {
        return Str::slug($string);
    }

    /**
     * Generate random string
     */
    public static function random($length = 16): string
    {
        return Str::random($length);
    }

    /**
     * Convert to camel case
     */
    public static function camel($string): string
    {
        return Str::camel($string);
    }

    /**
     * Convert to snake case
     */
    public static function snake($string): string
    {
        return Str::snake($string);
    }

    /**
     * Convert to studly case
     */
    public static function studly($string): string
    {
        return Str::studly($string);
    }

    /**
     * Convert to kebab case
     */
    public static function kebab($string): string
    {
        return Str::kebab($string);
    }

    /**
     * Check if string contains substring
     */
    public static function contains($haystack, $needle): bool
    {
        return Str::contains($haystack, $needle);
    }

    /**
     * Check if string starts with substring
     */
    public static function startsWith($haystack, $needle): bool
    {
        return Str::startsWith($haystack, $needle);
    }

    /**
     * Check if string ends with substring
     */
    public static function endsWith($haystack, $needle): bool
    {
        return Str::endsWith($haystack, $needle);
    }

    /**
     * Extract first word
     */
    public static function firstWord($string): string
    {
        return explode(' ', $string)[0] ?? $string;
    }

    /**
     * Extract last word
     */
    public static function lastWord($string): string
    {
        $words = explode(' ', $string);
        return end($words);
    }

    /**
     * Count words
     */
    public static function wordCount($string): int
    {
        return str_word_count($string);
    }

    /**
     * Truncate with word boundary
     */
    public static function truncate($string, $limit = 100, $end = '...'): string
    {
        if (strlen($string) <= $limit) {
            return $string;
        }

        $string = substr($string, 0, $limit);
        $lastSpace = strrpos($string, ' ');

        if ($lastSpace !== false) {
            $string = substr($string, 0, $lastSpace);
        }

        return $string . $end;
    }

    /**
     * Generate UUID
     */
    public static function uuid(): string
    {
        return (string) Str::uuid();
    }

    /**
     * Generate unique string
     */
    public static function unique($length = 32): string
    {
        return Str::random($length);
    }

    /**
     * Mask string (hide parts)
     */
    public static function mask($string, $start = 0, $length = null, $mask = '*'): string
    {
        if (empty($string)) {
            return $string;
        }

        $length = $length ?? strlen($string) - $start - 4;

        return substr($string, 0, $start) .
               str_repeat($mask, $length) .
               substr($string, $start + $length);
    }

    /**
     * Mask email
     */
    public static function maskEmail($email): string
    {
        $parts = explode('@', $email);
        $name = $parts[0];
        $domain = $parts[1] ?? '';

        $maskedName = self::mask($name, 0, max(1, strlen($name) - 2));

        return $maskedName . '@' . $domain;
    }
}