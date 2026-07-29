<?php

namespace App\Helpers;

class NumberHelper
{
    /**
     * Format number
     */
    public static function format($number, $decimals = 2, $decPoint = '.', $thousandsSep = ','): string
    {
        return number_format((float) $number, $decimals, $decPoint, $thousandsSep);
    }

    /**
     * Calculate percentage
     */
    public static function percentage($value, $total, $decimals = 2): float
    {
        if ($total == 0) {
            return 0;
        }

        return round(($value / $total) * 100, $decimals);
    }

    /**
     * Format as ordinal number (1st, 2nd, 3rd)
     */
    public static function ordinal($number): string
    {
        $suffixes = ['th', 'st', 'nd', 'rd'];

        $lastDigit = $number % 10;
        $lastTwoDigits = $number % 100;

        if ($lastTwoDigits >= 11 && $lastTwoDigits <= 13) {
            $suffix = 'th';
        } else {
            $suffix = $suffixes[$lastDigit] ?? 'th';
        }

        return $number . $suffix;
    }

    /**
     * Format as currency
     */
    public static function currency($amount, $currency = 'USD', $locale = 'en_US'): string
    {
        $formatter = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
        return $formatter->formatCurrency($amount, $currency);
    }

    /**
     * Round to nearest multiple
     */
    public static function roundToMultiple($number, $multiple): float
    {
        return round($number / $multiple) * $multiple;
    }

    /**
     * Check if number is even
     */
    public static function isEven($number): bool
    {
        return $number % 2 === 0;
    }

    /**
     * Check if number is odd
     */
    public static function isOdd($number): bool
    {
        return $number % 2 !== 0;
    }

    /**
     * Generate random number between min and max
     */
    public static function random($min = 0, $max = 100): int
    {
        return rand($min, $max);
    }

    /**
     * Convert number to words
     */
    public static function toWords($number): string
    {
        $words = [
            0 => 'zero', 1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four',
            5 => 'five', 6 => 'six', 7 => 'seven', 8 => 'eight', 9 => 'nine',
            10 => 'ten', 11 => 'eleven', 12 => 'twelve', 13 => 'thirteen',
            14 => 'fourteen', 15 => 'fifteen', 16 => 'sixteen', 17 => 'seventeen',
            18 => 'eighteen', 19 => 'nineteen', 20 => 'twenty',
            30 => 'thirty', 40 => 'forty', 50 => 'fifty',
            60 => 'sixty', 70 => 'seventy', 80 => 'eighty', 90 => 'ninety'
        ];

        if ($number <= 20) {
            return $words[$number] ?? '';
        }

        if ($number < 100) {
            $tens = floor($number / 10) * 10;
            $units = $number % 10;
            return $words[$tens] . ($units ? '-' . $words[$units] : '');
        }

        if ($number < 1000) {
            $hundreds = floor($number / 100);
            $remainder = $number % 100;
            return $words[$hundreds] . ' hundred' . ($remainder ? ' and ' . self::toWords($remainder) : '');
        }

        if ($number < 1000000) {
            $thousands = floor($number / 1000);
            $remainder = $number % 1000;
            return self::toWords($thousands) . ' thousand' . ($remainder ? ' ' . self::toWords($remainder) : '');
        }

        return (string) $number;
    }

    /**
     * Get number abbreviation (K, M, B)
     */
    public static function abbreviate($number, $precision = 1): string
    {
        if ($number < 1000) {
            return (string) $number;
        }

        if ($number < 1000000) {
            return round($number / 1000, $precision) . 'K';
        }

        if ($number < 1000000000) {
            return round($number / 1000000, $precision) . 'M';
        }

        return round($number / 1000000000, $precision) . 'B';
    }
}