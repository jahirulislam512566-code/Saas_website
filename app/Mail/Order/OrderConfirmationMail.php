<?php

namespace App\Mail\Order;

use App\Mail\BaseMail;
use App\Models\Order;

class OrderConfirmationMail extends BaseMail
{
    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    protected function getSubject(): string
    {
        return "Order Confirmation #{$this->order->order_number}";
    }

    protected function getView(): string
    {
        return 'emails.order.confirmation';
    }

    protected function getViewData(): array
    {
        return [
            'order' => $this->order,
            'customer' => $this->order->customer,
            'items' => $this->order->items,
            'total' => $this->order->total,
            'currency' => $this->order->currency ?? 'USD',
            'company_name' => company('name'),
            'support_email' => company('email'),
            'support_phone' => company('phone'),
        ];
    }

    protected function getAttachments(): array
    {
        // Attach PDF invoice if generated
        if ($this->order->invoice && $this->order->invoice->pdf_path) {
            return [
                [
                    'file' => storage_path("app/public/{$this->order->invoice->pdf_path}"),
                    'as' => "Invoice_{$this->order->invoice->invoice_number}.pdf",
                    'mime' => 'application/pdf',
                ]
            ];
        }

        return [];
    }
}