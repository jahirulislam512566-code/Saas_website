<?php

namespace App\Events\Invoice;

use App\Models\Invoice;
use App\Events\Event;

class InvoiceCreated extends Event
{
    public Invoice $invoice;

    public function __construct(Invoice $invoice)
    {
        parent::__construct();
        $this->invoice = $invoice;
    }

    public function eventName(): string
    {
        return 'invoice.created';
    }

    public function payload(): array
    {
        return [
            'invoice_id' => $this->invoice->id,
            'invoice_number' => $this->invoice->invoice_number,
            'customer_id' => $this->invoice->customer_id,
            'total' => $this->invoice->total,
            'due_date' => $this->invoice->due_date,
            'status' => $this->invoice->status,
            'created_at' => $this->occurred_at->toDateTimeString(),
        ];
    }
}