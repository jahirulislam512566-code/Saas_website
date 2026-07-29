<?php

namespace App\Jobs\Email;

use App\Jobs\BaseJob;
use App\Mail\InvoiceMail;
use App\Models\Invoice;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendInvoiceEmailJob extends BaseJob
{
    public Invoice $invoice;
    public array $recipients;
    public ?array $cc;

    public function __construct(Invoice $invoice, array $recipients, ?array $cc = null)
    {
        $this->invoice = $invoice;
        $this->recipients = $recipients;
        $this->cc = $cc;
    }

    public function handle(): void
    {
        $this->logStart();

        try {
            foreach ($this->recipients as $recipient) {
                Mail::to($recipient)
                    ->cc($this->cc ?? [])
                    ->send(new InvoiceMail($this->invoice));
            }

            $this->invoice->update([
                'sent_at' => now(),
                'sent_by' => auth()->id(),
                'status' => 'sent',
            ]);

            Log::info('Invoice email sent', [
                'invoice_id' => $this->invoice->id,
                'recipients' => $this->recipients,
            ]);

            $this->logComplete();

        } catch (\Exception $e) {
            $this->logError($e);
            throw $e;
        }
    }

    protected function getJobData(): array
    {
        return [
            'invoice_id' => $this->invoice->id,
            'recipients' => $this->recipients,
            'cc' => $this->cc,
        ];
    }
}