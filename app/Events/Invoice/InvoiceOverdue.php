<?php

namespace App\Events\Invoice;

use App\Models\Invoice;
use App\Events\Event;

class InvoiceOverdue extends Event
{
    public Invoice $invoice;
    public int $daysOverdue;

    public function __construct(Invoice $invoice, int $daysOverdue)
    {
        parent::__construct();
        $this->invoice = $invoice;
        $this->daysOverdue = $daysOverdue;
    }

    public function eventName(): string
    {
        return 'invoice.overdue';
    }

    public function payload(): array
    {
        return [
            'invoice_id' => $this->invoice->id,
            'invoice_number' => $this->invoice->invoice_number,
            'customer_id' => $this->invoice->customer_id,
            'total' => $this->invoice->total,
            'due_date' => $this->invoice->due_date,
            'days_overdue' => $this->daysOverdue,
            'overdue_since' => $this->invoice->due_date->toDateTimeString(),
            'overdue_at' => $this->occurred_at->toDateTimeString(),
        ];
    }
}