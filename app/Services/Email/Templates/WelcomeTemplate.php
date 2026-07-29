<?php

namespace App\Services\Email\Templates;

class WelcomeTemplate extends BaseTemplate
{
    protected function getDefaultSubject(): string
    {
        return 'Welcome to ' . config('app.name');
    }

    protected function getView(): string
    {
        return 'emails.auth.welcome';
    }

    protected function getViewData(): array
    {
        return array_merge($this->data, [
            'company_name' => company('name'),
            'company_logo' => company('logo'),
            'dashboard_url' => route('customer.dashboard'),
            'support_email' => company('email'),
        ]);
    }
}