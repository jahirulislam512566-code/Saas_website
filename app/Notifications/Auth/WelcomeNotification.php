<?php

namespace App\Notifications\Auth;

use App\Notifications\BaseNotification;
use Illuminate\Notifications\Messages\MailMessage;

class WelcomeNotification extends BaseNotification
{
    public function __construct()
    {
        //
    }

    protected function buildMailMessage($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Welcome to ' . config('app.name'))
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Welcome to ' . config('app.name') . '! We\'re excited to have you on board.')
            ->line('You can now access your account and start exploring our services.')
            ->action('Go to Dashboard', route('customer.dashboard'))
            ->line('If you have any questions, feel free to contact our support team.')
            ->line('Thank you for choosing us!');
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Welcome to ' . config('app.name'),
            'message' => 'Welcome ' . $notifiable->name . '! We\'re excited to have you on board.',
            'type' => 'welcome',
            'icon' => 'fa-user-plus',
            'color' => 'success',
            'action_url' => route('customer.dashboard'),
            'action_text' => 'Go to Dashboard',
        ];
    }
}