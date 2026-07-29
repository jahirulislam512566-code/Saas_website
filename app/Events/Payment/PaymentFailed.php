<?php

namespace App\Events\Payment;

use App\Models\Payment;
use App\Events\Event;

class PaymentFailed extends Event
{
    public Payment $payment;
    public string $error;
    public array $details;

    public function __construct(Payment $payment, string $error, array $details = [])
    {
        parent::__construct();
        $this->payment = $payment;
        $this->error = $error;
        $this->details = $details;
    }

    public function eventName(): string
    {
        return 'payment.failed';
    }

    public function payload(): array
    {
        return [
            'payment_id' => $this->payment->id,
            'order_id' => $this->payment->order_id,
            'amount' => $this->payment->amount,
            'currency' => $this->payment->currency,
            'payment_method' => $this->payment->payment_method,
            'gateway' => $this->payment->gateway,
            'error' => $this->error,
            'details' => $this->details,
            'failed_at' => $this->occurred_at->toDateTimeString(),
        ];
    }
}