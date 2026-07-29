<?php

namespace App\Notifications\Payment;

use App\Models\Payment;
use App\Notifications\BaseNotification;
use Illuminate\Notifications\Messages\MailMessage;

class PaymentReceivedNotification extends BaseNotification
{
    public Payment $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    protected function buildMailMessage($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Payment Received - ' . money($this->payment->amount))
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('We have received your payment of ' . money($this->payment->amount) . '.')
            ->line('**Payment Method:** ' . ucfirst($this->payment->payment_method))
            ->line('**Transaction ID:** ' . $this->payment->gateway_reference)
            ->line('**Order:** #' . $this->payment->order->order_number)
            ->action('View Payment', route('customer.payments.show', $this->payment))
            ->line('Thank you for your payment!');
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Payment Received',
            'message' => 'Payment of ' . money($this->payment->amount) . ' has been received.',
            'type' => 'payment_received',
            'icon' => 'fa-credit-card',
            'color' => 'success',
            'payment_id' => $this->payment->id,
            'amount' => $this->payment->amount,
            'amount_formatted' => money($this->payment->amount),
            'payment_method' => $this->payment->payment_method,
            'transaction_id' => $this->payment->gateway_reference,
            'order_id' => $this->payment->order_id,
            'action_url' => route('customer.payments.show', $this->payment),
            'action_text' => 'View Payment',
        ];
    }
}