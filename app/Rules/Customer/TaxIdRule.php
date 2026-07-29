<?php

namespace App\Rules\Customer;

use App\Rules\BaseRule;

class TaxIdRule extends BaseRule
{
    protected string $country;

    public function __construct(string $country = 'US')
    {
        $this->country = $country;
    }

    public function passes($attribute, $value): bool
    {
        if (empty($value)) {
            return true;
        }

        // Remove non-alphanumeric
        $taxId = preg_replace('/[^a-zA-Z0-9]/', '', $value);

        $patterns = [
            'US' => '/^[0-9]{9}$/', // EIN or SSN
            'UK' => '/^[A-Z0-9]{5,10}$/',
            'CA' => '/^[0-9]{9}$/',
            'DE' => '/^[0-9]{11}$/',
            'FR' => '/^[0-9]{11}$/',
            'IT' => '/^[0-9]{11}$/',
            'ES' => '/^[A-Z0-9]{9}$/',
            'AU' => '/^[0-9]{11}$/',
        ];

        $pattern = $patterns[strtoupper($this->country)] ?? '/^[a-zA-Z0-9]{5,20}$/';

        return preg_match($pattern, $taxId) === 1;
    }

    public function message(): string
    {
        return "The tax ID must be a valid {$this->country} tax ID number.";
    }
}