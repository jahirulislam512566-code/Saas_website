<?php

namespace App\Events\Invoice;

use App\Models\Invoice;
use App\Events\Event;

class InvoiceViewed extends Event
{
    public Invoice $invoice;
    public string $viewer;

    public function __construct(Invoice $invoice, string $viewer)
    {
        parent::__construct();
        $this->invoice = $invoice;
        $this->viewer = $viewer;
    }

    public function eventName(): string
    {
        return 'invoice.viewed';
    }

    public function payload(): array
    {
        return [
            'invoice_id' => $this->invoice->id,
            'invoice_number' => $this->invoice->invoice_number,
            'customer_id' => $this->invoice->customer_id,
            'viewer' => $this->viewer,
            'viewed_at' => $this->occurred_at->toDateTimeString(),
        ];
    }
}