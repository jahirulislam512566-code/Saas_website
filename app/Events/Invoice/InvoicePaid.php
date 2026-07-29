<?php

namespace App\Events\Invoice;

use App\Models\Invoice;
use App\Events\Event;

class InvoicePaid extends Event
{
    public Invoice $invoice;
    public array $paymentDetails;

    public function __construct(Invoice $invoice, array $paymentDetails = [])
    {
        parent::__construct();
        $this->invoice = $invoice;
        $this->paymentDetails = $paymentDetails;
    }

    public function eventName(): string
    {
        return 'invoice.paid';
    }

    public function payload(): array
    {
        return [
            'invoice_id' => $this->invoice->id,
            'invoice_number' => $this->invoice->invoice_number,
            'customer_id' => $this->invoice->customer_id,
            'amount_paid' => $this->invoice->total,
            'payment_method' => $this->paymentDetails['method'] ?? 'unknown',
            'transaction_id' => $this->paymentDetails['transaction_id'] ?? null,
            'paid_at' => $this->occurred_at->toDateTimeString(),
        ];
    }
}