<?php

namespace App\Rules\Auth;

use App\Rules\BaseRule;
use Illuminate\Support\Facades\Hash;

class CurrentPasswordRule extends BaseRule
{
    protected $user;

    public function __construct($user = null)
    {
        $this->user = $user ?? auth()->user();
    }

    public function passes($attribute, $value): bool
    {
        if (!$this->user) {
            return false;
        }

        return Hash::check($value, $this->user->password);
    }

    public function message(): string
    {
        return 'The current password is incorrect.';
    }
}