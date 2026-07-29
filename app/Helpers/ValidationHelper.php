<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ValidationHelper
{
    /**
     * Validate email
     */
    public static function isEmail($email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validate phone number
     */
    public static function isPhone($phone): bool
    {
        // Basic phone validation
        $pattern = '/^\+?[0-9\-\(\)\s]{10,20}$/';
        return preg_match($pattern, $phone) === 1;
    }

    /**
     * Validate URL
     */
    public static function isUrl($url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Validate IP address
     */
    public static function isIp($ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP) !== false;
    }

    /**
     * Validate domain
     */
    public static function isDomain($domain): bool
    {
        return preg_match('/^(?!\-)(?:[a-zA-Z\d\-]{0,62}[a-zA-Z\d]\.){1,126}(?!\d+)[a-zA-Z\d]{1,63}$/', $domain) === 1;
    }

    /**
     * Validate password strength
     */
    public static function passwordStrength($password): array
    {
        $strength = 0;
        $checks = [];

        if (strlen($password) >= 8) {
            $strength++;
            $checks['length'] = true;
        } else {
            $checks['length'] = false;
        }

        if (preg_match('/[a-z]/', $password)) {
            $strength++;
            $checks['lowercase'] = true;
        } else {
            $checks['lowercase'] = false;
        }

        if (preg_match('/[A-Z]/', $password)) {
            $strength++;
            $checks['uppercase'] = true;
        } else {
            $checks['uppercase'] = false;
        }

        if (preg_match('/[0-9]/', $password)) {
            $strength++;
            $checks['number'] = true;
        } else {
            $checks['number'] = false;
        }

        if (preg_match('/[^a-zA-Z0-9]/', $password)) {
            $strength++;
            $checks['special'] = true;
        } else {
            $checks['special'] = false;
        }

        $level = ['weak', 'medium', 'good', 'strong', 'very_strong'][$strength] ?? 'weak';

        return [
            'strength' => $strength,
            'level' => $level,
            'checks' => $checks,
            'percentage' => ($strength / 5) * 100,
        ];
    }

    /**
     * Validate data against rules
     */
    public static function validate($data, $rules, $messages = []): array
    {
        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    /**
     * Check if value is in list
     */
    public static function inList($value, $list): bool
    {
        return in_array($value, $list);
    }

    /**
     * Validate date format
     */
    public static function isDate($date, $format = 'Y-m-d'): bool
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    /**
     * Validate JSON
     */
    public static function isJson($string): bool
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * Validate base64
     */
    public static function isBase64($string): bool
    {
        return base64_encode(base64_decode($string, true)) === $string;
    }

    /**
     * Validate hex color
     */
    public static function isHexColor($color): bool
    {
        return preg_match('/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/', $color) === 1;
    }

    /**
     * Validate credit card (Luhn algorithm)
     */
    public static function isCreditCard($number): bool
    {
        $number = preg_replace('/\D/', '', $number);
        $length = strlen($number);

        if ($length < 13 || $length > 19) {
            return false;
        }

        $sum = 0;
        $alternate = false;

        for ($i = $length - 1; $i >= 0; $i--) {
            $digit = (int) $number[$i];

            if ($alternate) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }

            $sum += $digit;
            $alternate = !$alternate;
        }

        return $sum % 10 === 0;
    }

    /**
     * Validate postal/ZIP code
     */
    public static function isPostalCode($code, $country = 'US'): bool
    {
        $patterns = [
            'US' => '/^\d{5}(-\d{4})?$/',
            'UK' => '/^[A-Z]{1,2}[0-9][A-Z0-9]? ?[0-9][A-Z]{2}$/',
            'CA' => '/^[A-Z]\d[A-Z] ?\d[A-Z]\d$/',
            'DE' => '/^\d{5}$/',
            'FR' => '/^\d{5}$/',
            'IT' => '/^\d{5}$/',
            'ES' => '/^\d{5}$/',
            'AU' => '/^\d{4}$/',
            'NZ' => '/^\d{4}$/',
            'JP' => '/^\d{3}-\d{4}$/',
        ];

        $pattern = $patterns[strtoupper($country)] ?? '/^.{3,10}$/';
        return preg_match($pattern, $code) === 1;
    }
}