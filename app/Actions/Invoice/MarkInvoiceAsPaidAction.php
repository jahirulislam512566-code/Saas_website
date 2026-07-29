<?php

namespace App\Actions\Invoice;

use App\Models\Invoice;
use App\Notifications\InvoicePaidNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class MarkInvoiceAsPaidAction
{
    /**
     * Mark invoice as paid
     *
     * @param Invoice $invoice
     * @param array $data
     * @return Invoice
     */
    public function execute(Invoice $invoice, array $data): Invoice
    {
        return DB::transaction(function () use ($invoice, $data) {
            $oldStatus = $invoice->status;

            // Validate status transition
            if (!in_array($oldStatus, ['sent', 'pending', 'overdue'])) {
                throw new \Exception('Invoice cannot be marked as paid. Current status: ' . $oldStatus);
            }

            $invoice->update([
                'status' => 'paid',
                'paid_at' => now(),
                'payment_method' => $data['payment_method'] ?? 'other',
                'payment_reference' => $data['payment_reference'] ?? null,
                'payment_notes' => $data['notes'] ?? null,
                'paid_by' => auth()->id(),
            ]);

            // Notify customer
            if ($data['notify_customer'] ?? true) {
                Notification::send($invoice->customer, new InvoicePaidNotification($invoice));
            }

            Log::info('Invoice marked as paid', [
                'invoice_id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'customer_id' => $invoice->customer_id,
                'amount' => $invoice->total,
                'old_status' => $oldStatus,
                'new_status' => 'paid',
                'paid_by' => auth()->id()
            ]);

            return $invoice->fresh();
        });
    }
}