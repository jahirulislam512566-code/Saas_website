<?php

namespace App\Helpers;

use Illuminate\Support\Facades\App;

class MoneyHelper
{
    /**
     * Format money/currency
     */
    public static function format($amount, $currency = null, $locale = null): string
    {
        if (is_null($amount)) {
            return '0.00';
        }

        $currency = $currency ?? config('app.currency', 'USD');
        $locale = $locale ?? App::getLocale();

        // Get currency symbol
        $symbol = self::getCurrencySymbol($currency);

        // Format based on locale
        $formatted = number_format(
            (float) $amount,
            2,
            config('app.decimal_separator', '.'),
            config('app.thousand_separator', ',')
        );

        // Position of currency symbol
        $position = config('app.currency_position', 'before');

        if ($position === 'before') {
            return $symbol . $formatted;
        }

        return $formatted . ' ' . $symbol;
    }

    /**
     * Get currency symbol
     */
    public static function getCurrencySymbol($currency): string
    {
        $symbols = [
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'JPY' => '¥',
            'CAD' => 'C$',
            'AUD' => 'A$',
            'CHF' => 'Fr',
            'CNY' => '¥',
            'INR' => '₹',
            'BRL' => 'R$',
            'ZAR' => 'R',
            'NZD' => 'NZ$',
            'SEK' => 'kr',
            'NOK' => 'kr',
            'DKK' => 'kr',
            'MXN' => 'Mex$',
            'SGD' => 'S$',
            'HKD' => 'HK$',
            'MYR' => 'RM',
            'PHP' => '₱',
            'THB' => '฿',
            'VND' => '₫',
            'IDR' => 'Rp',
            'KRW' => '₩',
            'RUB' => '₽',
            'TRY' => '₺',
            'AED' => 'د.إ',
            'SAR' => '﷼',
            'EGP' => 'E£',
            'NGN' => '₦',
            'KES' => 'KSh',
        ];

        return $symbols[strtoupper($currency)] ?? $currency . ' ';
    }

    /**
     * Convert amount between currencies
     */
    public static function convert($amount, $fromCurrency, $toCurrency): float
    {
        // This would typically call an external API
        // For now, return the same amount
        return (float) $amount;
    }

    /**
     * Format as percentage
     */
    public static function formatPercentage($value, $total, $decimals = 2): string
    {
        if ($total == 0) {
            return '0%';
        }

        $percentage = ($value / $total) * 100;
        return number_format($percentage, $decimals) . '%';
    }

    /**
     * Format as currency with cents
     */
    public static function formatCents($amount): string
    {
        return number_format($amount / 100, 2);
    }
}