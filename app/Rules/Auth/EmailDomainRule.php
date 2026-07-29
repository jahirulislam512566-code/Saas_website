<?php

namespace App\Rules\Auth;

use App\Rules\BaseRule;

class EmailDomainRule extends BaseRule
{
    protected array $allowedDomains;
    protected array $blockedDomains;
    protected bool $allowSubdomains;

    public function __construct(
        array $allowedDomains = [],
        array $blockedDomains = ['tempmail.com', 'guerrillamail.com'],
        bool $allowSubdomains = true
    ) {
        $this->allowedDomains = $allowedDomains;
        $this->blockedDomains = $blockedDomains;
        $this->allowSubdomains = $allowSubdomains;
    }

    public function passes($attribute, $value): bool
    {
        $domain = substr(strrchr($value, "@"), 1);
        
        if (!$domain) {
            return false;
        }

        // Check blocked domains
        foreach ($this->blockedDomains as $blocked) {
            if (str_ends_with($domain, $blocked)) {
                return false;
            }
        }

        // Check allowed domains
        if (!empty($this->allowedDomains)) {
            foreach ($this->allowedDomains as $allowed) {
                if ($this->allowSubdomains && str_ends_with($domain, $allowed)) {
                    return true;
                }
                if ($domain === $allowed) {
                    return true;
                }
            }
            return false;
        }

        return true;
    }

    public function message(): string
    {
        if (!empty($this->allowedDomains)) {
            return 'The email domain must be from: ' . implode(', ', $this->allowedDomains);
        }

        return 'The email domain is not allowed.';
    }
}