<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;

class CompanyHelper
{
    /**
     * Get company information
     */
    public static function get($key = null)
    {
        $company = self::loadCompany();

        if ($key) {
            return $company[$key] ?? null;
        }

        return $company;
    }

    /**
     * Load company information
     */
    protected static function loadCompany(): array
    {
        return Cache::remember('company_info', 3600, function () {
            $defaults = [
                'name' => config('app.name', 'My Company'),
                'email' => config('mail.from.address', 'info@company.com'),
                'phone' => '+1 (555) 123-4567',
                'address' => '123 Main Street, City, State 12345',
                'logo' => '/images/logo.png',
                'favicon' => '/favicon.ico',
                'timezone' => 'UTC',
                'currency' => 'USD',
                'tax_id' => null,
                'registration_number' => null,
                'social' => [
                    'facebook' => null,
                    'twitter' => null,
                    'instagram' => null,
                    'linkedin' => null,
                    'youtube' => null,
                ],
            ];

            // Get from database if table exists
            if (self::tableExists()) {
                $dbCompany = \App\Models\Company::first();
                if ($dbCompany) {
                    return array_merge($defaults, $dbCompany->toArray());
                }
            }

            return $defaults;
        });
    }

    /**
     * Check if companies table exists
     */
    protected static function tableExists(): bool
    {
        try {
            return \Illuminate\Support\Facades\Schema::hasTable('companies');
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get company logo URL
     */
    public static function logo(): string
    {
        $logo = self::get('logo');
        if ($logo && filter_var($logo, FILTER_VALIDATE_URL)) {
            return $logo;
        }

        return asset($logo ?? '/images/logo.png');
    }

    /**
     * Get full company address
     */
    public static function fullAddress(): string
    {
        return self::get('address', '');
    }

    /**
     * Get company name
     */
    public static function name(): string
    {
        return self::get('name', config('app.name', 'My Company'));
    }

    /**
     * Get company email
     */
    public static function email(): string
    {
        return self::get('email', config('mail.from.address', 'info@company.com'));
    }

    /**
     * Get company phone
     */
    public static function phone(): string
    {
        return self::get('phone', '+1 (555) 123-4567');
    }

    /**
     * Get social media links
     */
    public static function social($platform = null)
    {
        $social = self::get('social', []);

        if ($platform) {
            return $social[$platform] ?? null;
        }

        return $social;
    }
}