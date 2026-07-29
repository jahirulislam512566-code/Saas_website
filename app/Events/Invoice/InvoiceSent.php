<?php

namespace App\Events\Invoice;

use App\Models\Invoice;
use App\Events\Event;

class InvoiceSent extends Event
{
    public Invoice $invoice;
    public string $recipient;

    public function __construct(Invoice $invoice, string $recipient)
    {
        parent::__construct();
        $this->invoice = $invoice;
        $this->recipient = $recipient;
    }

    public function eventName(): string
    {
        return 'invoice.sent';
    }

    public function payload(): array
    {
        return [
            'invoice_id' => $this->invoice->id,
            'invoice_number' => $this->invoice->invoice_number,
            'customer_id' => $this->invoice->customer_id,
            'recipient' => $this->recipient,
            'sent_at' => $this->occurred_at->toDateTimeString(),
        ];
    }
}