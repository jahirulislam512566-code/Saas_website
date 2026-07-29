<?php

namespace App\Notifications\Invoice;

use App\Models\Invoice;
use App\Notifications\BaseNotification;
use Illuminate\Notifications\Messages\MailMessage;

class InvoiceOverdueNotification extends BaseNotification
{
    public Invoice $invoice;
    public int $daysOverdue;

    public function __construct(Invoice $invoice, int $daysOverdue)
    {
        $this->invoice = $invoice;
        $this->daysOverdue = $daysOverdue;
    }

    protected function buildMailMessage($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Overdue Invoice #{$this->invoice->invoice_number}")
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your invoice #' . $this->invoice->invoice_number . ' is ' . $this->daysOverdue . ' days overdue.')
            ->line('**Amount Due:** ' . money($this->invoice->total))
            ->line('**Original Due Date:** ' . $this->invoice->due_date->format('M d, Y'))
            ->line('Please make payment as soon as possible to avoid additional fees.')
            ->action('Pay Invoice', route('customer.invoices.pay', $this->invoice))
            ->line('If you have already made the payment, please disregard this message.');
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => "Overdue Invoice #{$this->invoice->invoice_number}",
            'message' => 'Your invoice is ' . $this->daysOverdue . ' days overdue. Amount: ' . money($this->invoice->total),
            'type' => 'invoice_overdue',
            'icon' => 'fa-exclamation-triangle',
            'color' => 'danger',
            'invoice_id' => $this->invoice->id,
            'invoice_number' => $this->invoice->invoice_number,
            'amount' => $this->invoice->total,
            'amount_formatted' => money($this->invoice->total),
            'days_overdue' => $this->daysOverdue,
            'due_date' => $this->invoice->due_date->toDateTimeString(),
            'action_url' => route('customer.invoices.pay', $this->invoice),
            'action_text' => 'Pay Invoice',
        ];
    }
}