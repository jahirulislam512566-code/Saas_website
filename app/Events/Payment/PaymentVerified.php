<?php

namespace App\Events\Payment;

use App\Models\Payment;
use App\Events\Event;

class PaymentVerified extends Event
{
    public Payment $payment;
    public array $verificationData;

    public function __construct(Payment $payment, array $verificationData)
    {
        parent::__construct();
        $this->payment = $payment;
        $this->verificationData = $verificationData;
    }

    public function eventName(): string
    {
        return 'payment.verified';
    }

    public function payload(): array
    {
        return [
            'payment_id' => $this->payment->id,
            'order_id' => $this->payment->order_id,
            'amount' => $this->payment->amount,
            'status' => $this->payment->status,
            'verification_data' => $this->verificationData,
            'verified_at' => $this->occurred_at->toDateTimeString(),
        ];
    }
}