<?php

namespace App\Rules\Customer;

use App\Rules\BaseRule;

class PhoneNumberRule extends BaseRule
{
    protected string $country;
    protected bool $allowExtension;

    public function __construct(string $country = 'US', bool $allowExtension = false)
    {
        $this->country = $country;
        $this->allowExtension = $allowExtension;
    }

    public function passes($attribute, $value): bool
    {
        if (empty($value)) {
            return true;
        }

        // Remove all non-digit characters
        $phone = preg_replace('/[^0-9+]/', '', $value);

        // Check if extension is allowed
        if ($this->allowExtension) {
            $phone = preg_replace('/[^0-9+]/', '', $value);
        } else {
            $phone = preg_replace('/\s*[xX]?\s*\d+$/', '', $phone);
        }

        $patterns = [
            'US' => '/^\+?1?[2-9]\d{2}[2-9]\d{6}$/',
            'UK' => '/^\+?44[1-9]\d{9}$/',
            'CA' => '/^\+?1[2-9]\d{2}[2-9]\d{6}$/',
            'AU' => '/^\+?61[2-9]\d{8}$/',
            'IN' => '/^\+?91[6-9]\d{9}$/',
            'DE' => '/^\+?49[1-9]\d{10}$/',
            'FR' => '/^\+?33[1-9]\d{8}$/',
            'BR' => '/^\+?55[1-9]{2}\d{8,9}$/',
        ];

        $pattern = $patterns[strtoupper($this->country)] ?? '/^\+?[1-9]\d{7,14}$/';

        return preg_match($pattern, $phone) === 1;
    }

    public function message(): string
    {
        return "The phone number must be a valid {$this->country} phone number.";
    }

    /**
     * Format phone number
     */
    public function format(string $phone): string
    {
        $phone = preg_replace('/[^0-9+]/', '', $phone);
        
        if ($this->country === 'US' && strlen($phone) === 10) {
            return '(' . substr($phone, 0, 3) . ') ' . 
                   substr($phone, 3, 3) . '-' . 
                   substr($phone, 6, 4);
        }

        return $phone;
    }
}