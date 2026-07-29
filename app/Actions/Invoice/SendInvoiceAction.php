<?php

namespace App\Actions\Invoice;

use App\Mail\InvoiceMail;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendInvoiceAction
{
    /**
     * Send invoice to customer
     *
     * @param Invoice $invoice
     * @param array $data
     * @return bool
     */
    public function execute(Invoice $invoice, array $data = []): bool
    {
        return DB::transaction(function () use ($invoice, $data) {
            // Check if invoice can be sent
            if (!in_array($invoice->status, ['draft', 'pending'])) {
                throw new \Exception('Invoice cannot be sent. Current status: ' . $invoice->status);
            }

            // Send email
            $sent = Mail::to($invoice->customer->email)
                ->send(new InvoiceMail($invoice, $data));

            // Update invoice status
            $invoice->update([
                'status' => 'sent',
                'sent_at' => now(),
                'sent_by' => auth()->id(),
            ]);

            Log::info('Invoice sent', [
                'invoice_id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'customer_id' => $invoice->customer_id,
                'sent_by' => auth()->id()
            ]);

            return true;
        });
    }
}