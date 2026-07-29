<?php

namespace App\Jobs\PDF;

use App\Jobs\BaseJob;
use App\Models\Invoice;
use App\Services\PDFGeneratorService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GenerateInvoicePDFJob extends BaseJob
{
    public Invoice $invoice;
    public ?string $path;

    public function __construct(Invoice $invoice, ?string $path = null)
    {
        $this->invoice = $invoice;
        $this->path = $path;
    }

    public function handle(PDFGeneratorService $pdfGenerator): void
    {
        $this->logStart();

        try {
            // Generate PDF
            $pdf = $pdfGenerator->generateInvoice($this->invoice);

            // Save PDF
            $filename = "invoice_{$this->invoice->invoice_number}.pdf";
            $path = $this->path ?? "invoices/{$this->invoice->customer_id}/{$filename}";

            Storage::put($path, $pdf);
            
            // Update invoice record
            $this->invoice->update([
                'pdf_path' => $path,
                'pdf_generated_at' => now(),
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
            'invoice_number' => $this->invoice->invoice_number,
            'customer_id' => $this->invoice->customer_id,
        ];
    }
}