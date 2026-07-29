<?php

namespace App\Mail\Auth;

use App\Mail\BaseMail;
use App\Models\User;

class WelcomeMail extends BaseMail
{
    public User $user;
    public ?string $password;

    public function __construct(User $user, ?string $password = null)
    {
        $this->user = $user;
        $this->password = $password;
    }

    protected function getSubject(): string
    {
        return 'Welcome to ' . config('app.name') . '!';
    }

    protected function getView(): string
    {
        return 'emails.auth.welcome';
    }

    protected function getViewData(): array
    {
        return [
            'user' => $this->user,
            'password' => $this->password,
            'company_name' => company('name'),
            'company_logo' => company('logo'),
            'dashboard_url' => route('customer.dashboard'),
            'support_email' => company('email'),
        ];
    }
}