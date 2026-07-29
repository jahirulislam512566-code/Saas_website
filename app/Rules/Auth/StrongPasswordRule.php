<?php

namespace App\Rules\Auth;

use App\Rules\BaseRule;

class StrongPasswordRule extends BaseRule
{
    protected bool $requireUppercase = true;
    protected bool $requireLowercase = true;
    protected bool $requireNumber = true;
    protected bool $requireSpecial = true;
    protected int $minLength = 8;

    public function __construct(array $options = [])
    {
        $this->requireUppercase = $options['uppercase'] ?? true;
        $this->requireLowercase = $options['lowercase'] ?? true;
        $this->requireNumber = $options['number'] ?? true;
        $this->requireSpecial = $options['special'] ?? true;
        $this->minLength = $options['min_length'] ?? 8;
    }

    public function passes($attribute, $value): bool
    {
        $checks = [];

        // Check minimum length
        if (strlen($value) < $this->minLength) {
            return false;
        }

        // Check uppercase
        if ($this->requireUppercase && !preg_match('/[A-Z]/', $value)) {
            return false;
        }

        // Check lowercase
        if ($this->requireLowercase && !preg_match('/[a-z]/', $value)) {
            return false;
        }

        // Check number
        if ($this->requireNumber && !preg_match('/[0-9]/', $value)) {
            return false;
        }

        // Check special character
        if ($this->requireSpecial && !preg_match('/[^a-zA-Z0-9]/', $value)) {
            return false;
        }

        // Check for common patterns
        $commonPatterns = [
            'password',
            '123456',
            'qwerty',
            'admin',
            'letmein',
            'welcome',
            'monkey',
            'dragon',
            'master',
            'hello',
        ];

        foreach ($commonPatterns as $pattern) {
            if (stripos($value, $pattern) !== false) {
                return false;
            }
        }

        return true;
    }

    public function message(): string
    {
        $requirements = [];

        if ($this->minLength > 0) {
            $requirements[] = "at least {$this->minLength} characters";
        }
        if ($this->requireUppercase) {
            $requirements[] = "at least one uppercase letter";
        }
        if ($this->requireLowercase) {
            $requirements[] = "at least one lowercase letter";
        }
        if ($this->requireNumber) {
            $requirements[] = "at least one number";
        }
        if ($this->requireSpecial) {
            $requirements[] = "at least one special character";
        }

        return "The password must contain " . implode(', ', $requirements) . " and cannot contain common patterns.";
    }

    /**
     * Get password strength score (0-100)
     */
    public function getStrengthScore(string $password): int
    {
        $score = 0;

        // Length score (max 25)
        $length = strlen($password);
        if ($length >= 8) $score += 10;
        if ($length >= 12) $score += 10;
        if ($length >= 16) $score += 5;

        // Character variety (max 25)
        if (preg_match('/[a-z]/', $password)) $score += 5;
        if (preg_match('/[A-Z]/', $password)) $score += 5;
        if (preg_match('/[0-9]/', $password)) $score += 5;
        if (preg_match('/[^a-zA-Z0-9]/', $password)) $score += 10;

        // Complexity (max 25)
        $charCount = count_chars($password, 3);
        $uniqueChars = strlen($charCount);
        if ($uniqueChars >= 8) $score += 10;
        if ($uniqueChars >= 12) $score += 10;
        if ($uniqueChars >= 16) $score += 5;

        // Bonus for length and variety combination (max 25)
        if ($length >= 12 && preg_match('/[A-Z]/', $password) && preg_match('/[a-z]/', $password)) {
            $score += 10;
        }
        if ($length >= 12 && preg_match('/[0-9]/', $password) && preg_match('/[^a-zA-Z0-9]/', $password)) {
            $score += 15;
        }

        return min(100, $score);
    }

    /**
     * Get password strength level
     */
    public function getStrengthLevel(string $password): string
    {
        $score = $this->getStrengthScore($password);

        if ($score >= 80) return 'very_strong';
        if ($score >= 60) return 'strong';
        if ($score >= 40) return 'good';
        if ($score >= 20) return 'weak';
        return 'very_weak';
    }
}