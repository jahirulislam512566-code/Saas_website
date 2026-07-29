<?php

namespace App\Notifications\Auth;

use App\Notifications\BaseNotification;
use Illuminate\Notifications\Messages\MailMessage;

class EmailVerificationNotification extends BaseNotification
{
    public string $verificationUrl;

    public function __construct(string $verificationUrl)
    {
        $this->verificationUrl = $verificationUrl;
    }

    protected function buildMailMessage($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Verify Your Email Address')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Please click the button below to verify your email address.')
            ->action('Verify Email', $this->verificationUrl)
            ->line('This verification link will expire in 60 minutes.')
            ->line('If you did not create an account, no further action is required.')
            ->line('Thank you for using our application!');
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Email Verification Required',
            'message' => 'Please verify your email address to activate your account.',
            'type' => 'email_verification',
            'icon' => 'fa-envelope',
            'color' => 'warning',
            'action_url' => $this->verificationUrl,
            'action_text' => 'Verify Email',
        ];
    }
}