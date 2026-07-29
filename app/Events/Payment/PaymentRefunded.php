<?php

namespace App\Events\Payment;

use App\Models\Payment;
use App\Events\Event;

class PaymentRefunded extends Event
{
    public Payment $payment;
    public float $refundAmount;
    public string $reason;

    public function __construct(Payment $payment, float $refundAmount, string $reason = 'Customer requested refund')
    {
        parent::__construct();
        $this->payment = $payment;
        $this->refundAmount = $refundAmount;
        $this->reason = $reason;
    }

    public function eventName(): string
    {
        return 'payment.refunded';
    }

    public function payload(): array
    {
        return [
            'payment_id' => $this->payment->id,
            'order_id' => $this->payment->order_id,
            'original_amount' => $this->payment->amount,
            'refund_amount' => $this->refundAmount,
            'currency' => $this->payment->currency,
            'reason' => $this->reason,
            'refunded_at' => $this->occurred_at->toDateTimeString(),
        ];
    }
}