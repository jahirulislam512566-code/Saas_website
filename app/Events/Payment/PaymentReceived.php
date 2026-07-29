<?php

namespace App\Events\Payment;

use App\Models\Payment;
use App\Events\Event;

class PaymentReceived extends Event
{
    public Payment $payment;
    public array $details;

    public function __construct(Payment $payment, array $details = [])
    {
        parent::__construct();
        $this->payment = $payment;
        $this->details = $details;
    }

    public function eventName(): string
    {
        return 'payment.received';
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
            'transaction_id' => $this->payment->gateway_reference,
            'details' => $this->details,
            'received_at' => $this->occurred_at->toDateTimeString(),
        ];
    }
}