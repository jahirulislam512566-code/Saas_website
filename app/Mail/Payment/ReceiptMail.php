<?php

namespace App\Mail\Payment;

use App\Mail\BaseMail;
use App\Models\Payment;

class ReceiptMail extends BaseMail
{
    public Payment $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    protected function getSubject(): string
    {
        return "Payment Receipt #{$this->payment->id}";
    }

    protected function getView(): string
    {
        return 'emails.payment.receipt';
    }

    protected function getViewData(): array
    {
        $order = $this->payment->order;

        return [
            'payment' => $this->payment,
            'order' => $order,
            'customer' => $order->customer,
            'amount' => $this->payment->amount,
            'currency' => $this->payment->currency ?? 'USD',
            'payment_method' => $this->payment->payment_method,
            'transaction_id' => $this->payment->gateway_reference,
            'company_name' => company('name'),
            'company_logo' => company('logo'),
            'company_address' => company('address'),
        ];
    }
}