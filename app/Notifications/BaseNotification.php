<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

abstract class BaseNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        $channels = ['mail', 'database'];

        // Add SMS if enabled
        if ($this->shouldSendSms($notifiable)) {
            $channels[] = 'sms';
        }

        // Add WhatsApp if enabled
        if ($this->shouldSendWhatsApp($notifiable)) {
            $channels[] = 'whatsapp';
        }

        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        return $this->buildMailMessage($notifiable);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toDatabase($notifiable): array
    {
        return $this->toArray($notifiable);
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }

    /**
     * Get the array representation of the notification.
     */
    abstract public function toArray($notifiable): array;

    /**
     * Build mail message.
     */
    abstract protected function buildMailMessage($notifiable): MailMessage;

    /**
     * Determine if SMS should be sent.
     */
    protected function shouldSendSms($notifiable): bool
    {
        return method_exists($notifiable, 'getSmsNumber');
    }

    /**
     * Determine if WhatsApp should be sent.
     */
    protected function shouldSendWhatsApp($notifiable): bool
    {
        return method_exists($notifiable, 'getWhatsAppNumber');
    }

    /**
     * Get notification title.
     */
    protected function getTitle(): string
    {
        return class_basename($this);
    }

    /**
     * Get notification icon.
     */
    protected function getIcon(): string
    {
        return 'fa-bell';
    }
}
