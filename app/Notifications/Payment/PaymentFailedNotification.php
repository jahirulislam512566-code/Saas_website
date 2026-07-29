<?php

namespace App\Notifications\Payment;

use App\Models\Payment;
use App\Notifications\BaseNotification;
use Illuminate\Notifications\Messages\MailMessage;

class PaymentFailedNotification extends BaseNotification
{
    public Payment $payment;
    public string $error;

    public function __construct(Payment $payment, string $error)
    {
        $this->payment = $payment;
        $this->error = $error;
    }

    protected function buildMailMessage($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Payment Failed')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('We were unable to process your payment of ' . money($this->payment->amount) . '.')
            ->line('**Error:** ' . $this->error)
            ->line('Please try again or use a different payment method.')
            ->action('Retry Payment', route('customer.payments.retry', $this->payment))
            ->line('If you continue to experience issues, please contact our support team.');
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Payment Failed',
            'message' => 'Payment of ' . money($this->payment->amount) . ' failed: ' . $this->error,
            'type' => 'payment_failed',
            'icon' => 'fa-times-circle',
            'color' => 'danger',
            'payment_id' => $this->payment->id,
            'amount' => $this->payment->amount,
            'amount_formatted' => money($this->payment->amount),
            'error' => $this->error,
            'action_url' => route('customer.payments.retry', $this->payment),
            'action_text' => 'Retry Payment',
        ];
    }
}