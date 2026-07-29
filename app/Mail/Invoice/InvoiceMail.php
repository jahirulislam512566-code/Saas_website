<?php

namespace App\Mail\Invoice;

use App\Mail\BaseMail;
use App\Models\Invoice;

class InvoiceMail extends BaseMail
{
    public Invoice $invoice;
    public ?string $message;

    public function __construct(Invoice $invoice, ?string $message = null)
    {
        $this->invoice = $invoice;
        $this->message = $message;
    }

    protected function getSubject(): string
    {
        return "Invoice #{$this->invoice->invoice_number} from " . company('name');
    }

    protected function getView(): string
    {
        return 'emails.invoice.invoice';
    }

    protected function getViewData(): array
    {
        return [
            'invoice' => $this->invoice,
            'customer' => $this->invoice->customer,
            'items' => $this->invoice->items,
            'subtotal' => $this->invoice->subtotal,
            'tax' => $this->invoice->tax_amount,
            'discount' => $this->invoice->discount,
            'total' => $this->invoice->total,
            'currency' => $this->invoice->currency ?? 'USD',
            'due_date' => $this->invoice->due_date,
            'message' => $this->message,
            'company_name' => company('name'),
            'company_address' => company('address'),
            'company_phone' => company('phone'),
            'company_email' => company('email'),
            'payment_url' => route('customer.invoices.pay', $this->invoice),
            'download_url' => route('customer.invoices.download', $this->invoice),
        ];
    }

    protected function getAttachments(): array
    {
        $attachments = [];

        // Attach PDF invoice
        if ($this->invoice->pdf_path) {
            $attachments[] = [
                'file' => storage_path("app/public/{$this->invoice->pdf_path}"),
                'as' => "Invoice_{$this->invoice->invoice_number}.pdf",
                'mime' => 'application/pdf',
            ];
        }

        return $attachments;
    }
}