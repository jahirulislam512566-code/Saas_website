<?php

namespace App\Rules\Content;

use App\Rules\BaseRule;

class SlugFormatRule extends BaseRule
{
    protected bool $allowNumbers;
    protected bool $allowUnderscore;
    protected int $minLength;
    protected int $maxLength;

    public function __construct(
        bool $allowNumbers = true,
        bool $allowUnderscore = false,
        int $minLength = 3,
        int $maxLength = 255
    ) {
        $this->allowNumbers = $allowNumbers;
        $this->allowUnderscore = $allowUnderscore;
        $this->minLength = $minLength;
        $this->maxLength = $maxLength;
    }

    public function passes($attribute, $value): bool
    {
        if (strlen($value) < $this->minLength || strlen($value) > $this->maxLength) {
            return false;
        }

        $pattern = '/^[a-z]';
        if ($this->allowNumbers) {
            $pattern .= '0-9';
        }
        if ($this->allowUnderscore) {
            $pattern .= '_';
        }
        $pattern .= '+(?:-[';

        if ($this->allowNumbers) {
            $pattern .= '0-9';
        }
        if ($this->allowUnderscore) {
            $pattern .= '_';
        }
        $pattern .= 'a-z]+)*$/';

        return preg_match($pattern, $value) === 1;
    }

    public function message(): string
    {
        $requirements = [];
        $requirements[] = "between {$this->minLength} and {$this->maxLength} characters";
        $requirements[] = "lowercase letters" . ($this->allowNumbers ? ", numbers" : "") .
                         ($this->allowUnderscore ? ", underscores" : "");
        $requirements[] = "hyphens between words";

        return "The slug must contain " . implode(' and ', $requirements) . ".";
    }
}