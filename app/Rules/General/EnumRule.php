<?php

namespace App\Rules\General;

use App\Rules\BaseRule;

class EnumRule extends BaseRule
{
    protected string $enumClass;
    protected bool $caseSensitive;

    public function __construct(string $enumClass, bool $caseSensitive = false)
    {
        $this->enumClass = $enumClass;
        $this->caseSensitive = $caseSensitive;
    }

    public function passes($attribute, $value): bool
    {
        if (!class_exists($this->enumClass)) {
            return false;
        }

        if (!method_exists($this->enumClass, 'values')) {
            return false;
        }

        $values = $this->enumClass::values();

        if (!$this->caseSensitive) {
            $value = strtolower($value);
            $values = array_map('strtolower', $values);
        }

        return in_array($value, $values);
    }

    public function message(): string
    {
        return 'The selected :attribute is invalid.';
    }
}