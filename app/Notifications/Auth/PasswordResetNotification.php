<?php

namespace App\Notifications\Auth;

use App\Notifications\BaseNotification;
use Illuminate\Notifications\Messages\MailMessage;

class PasswordResetNotification extends BaseNotification
{
    public string $token;
    public string $resetUrl;

    public function __construct(string $token, string $resetUrl)
    {
        $this->token = $token;
        $this->resetUrl = $resetUrl;
    }

    protected function buildMailMessage($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Reset Your Password')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->action('Reset Password', $this->resetUrl)
            ->line('This password reset link will expire in ' . config('auth.passwords.users.expire', 60) . ' minutes.')
            ->line('If you did not request a password reset, no further action is required.')
            ->line('Thank you for using our application!');
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Password Reset Request',
            'message' => 'A password reset request was made for your account.',
            'type' => 'password_reset',
            'icon' => 'fa-key',
            'color' => 'info',
            'action_url' => $this->resetUrl,
            'action_text' => 'Reset Password',
            'expires_at' => now()->addMinutes(config('auth.passwords.users.expire', 60))->toDateTimeString(),
        ];
    }
}