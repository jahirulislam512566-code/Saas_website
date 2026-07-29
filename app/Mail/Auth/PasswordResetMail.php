<?php

namespace App\Mail\Auth;

use App\Mail\BaseMail;
use App\Models\User;

class PasswordResetMail extends BaseMail
{
    public User $user;
    public string $token;
    public string $resetUrl;

    public function __construct(User $user, string $token, string $resetUrl)
    {
        $this->user = $user;
        $this->token = $token;
        $this->resetUrl = $resetUrl;
    }

    protected function getSubject(): string
    {
        return 'Reset Your Password - ' . config('app.name');
    }

    protected function getView(): string
    {
        return 'emails.auth.password-reset';
    }

    protected function getViewData(): array
    {
        return [
            'user' => $this->user,
            'resetUrl' => $this->resetUrl,
            'token' => $this->token,
            'expires_in' => config('auth.passwords.users.expire', 60),
            'company_name' => company('name'),
        ];
    }
}