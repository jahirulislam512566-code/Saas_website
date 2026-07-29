<?php

namespace App\Services\Email\Templates;

class InvoiceTemplate extends BaseTemplate
{
    protected function getDefaultSubject(): string
    {
        return "Invoice #{$this->data['invoice_number']} from " . company('name');
    }

    protected function getView(): string
    {
        return 'emails.invoice.invoice';
    }

    protected function getViewData(): array
    {
        return array_merge($this->data, [
            'company_name' => company('name'),
            'company_address' => company('address'),
            'company_phone' => company('phone'),
            'company_email' => company('email'),
            'payment_url' => route('customer.invoices.pay', $this->data['invoice_id']),
            'download_url' => route('customer.invoices.download', $this->data['invoice_id']),
        ]);
    }

    public function addInvoiceAttachment(string $pdfPath): self
    {
        return $this->addAttachment($pdfPath, "Invoice_{$this->data['invoice_number']}.pdf");
    }
}