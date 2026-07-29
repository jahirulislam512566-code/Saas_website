<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\DataAwareRule;

abstract class BaseRule implements Rule, DataAwareRule
{
    /**
     * All of the data under validation.
     */
    protected array $data = [];

    /**
     * Set the data under validation.
     */
    public function setData($data): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Determine if the validation rule passes.
     */
    abstract public function passes($attribute, $value): bool;

    /**
     * Get the validation error message.
     */
    abstract public function message(): string;

    /**
     * Get the validation error message with attribute.
     */
    protected function getMessage(string $message, $attribute): string
    {
        return str_replace(':attribute', $attribute, $message);
    }
}