<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

class DateHelper
{
    /**
     * Format date
     */
    public static function format($date, $format = null): string
    {
        if (!$date) {
            return '';
        }

        $date = self::parse($date);
        $format = $format ?? config('app.date_format', 'Y-m-d');

        return $date->format($format);
    }

    /**
     * Format date and time
     */
    public static function formatDateTime($date): string
    {
        if (!$date) {
            return '';
        }

        $date = self::parse($date);
        return $date->format(config('app.datetime_format', 'Y-m-d H:i:s'));
    }

    /**
     * Format time
     */
    public static function formatTime($date): string
    {
        if (!$date) {
            return '';
        }

        $date = self::parse($date);
        return $date->format(config('app.time_format', 'H:i:s'));
    }

    /**
     * Get time ago string
     */
    public static function timeAgo($date): string
    {
        if (!$date) {
            return '';
        }

        $date = self::parse($date);
        return $date->diffForHumans();
    }

    /**
     * Parse date
     */
    public static function parse($date): Carbon
    {
        if ($date instanceof Carbon) {
            return $date;
        }

        return Carbon::parse($date);
    }

    /**
     * Get days between dates
     */
    public static function daysBetween($start, $end): int
    {
        $start = self::parse($start);
        $end = self::parse($end);

        return $start->diffInDays($end);
    }

    /**
     * Check if date is today
     */
    public static function isToday($date): bool
    {
        if (!$date) {
            return false;
        }

        return self::parse($date)->isToday();
    }

    /**
     * Check if date is in the past
     */
    public static function isPast($date): bool
    {
        if (!$date) {
            return false;
        }

        return self::parse($date)->isPast();
    }

    /**
     * Check if date is in the future
     */
    public static function isFuture($date): bool
    {
        if (!$date) {
            return false;
        }

        return self::parse($date)->isFuture();
    }

    /**
     * Format for JavaScript
     */
    public static function toJs($date): string
    {
        if (!$date) {
            return '';
        }

        return self::parse($date)->toISOString();
    }

    /**
     * Get start of day
     */
    public static function startOfDay($date): Carbon
    {
        return self::parse($date)->startOfDay();
    }

    /**
     * Get end of day
     */
    public static function endOfDay($date): Carbon
    {
        return self::parse($date)->endOfDay();
    }

    /**
     * Get age from birthdate
     */
    public static function age($birthdate): int
    {
        if (!$birthdate) {
            return 0;
        }

        return self::parse($birthdate)->age;
    }

    /**
     * Format for different locales
     */
    public static function formatLocale($date, $locale = null): string
    {
        $locale = $locale ?? App::getLocale();
        App::setLocale($locale);

        $date = self::parse($date);
        return $date->isoFormat('LLLL');
    }
}