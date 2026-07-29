<?php

namespace App\Actions\Invoice;

use App\Actions\BaseAction;
use App\Models\Invoice;
use App\Services\EmailService;
use Illuminate\Support\Facades\Storage;

class SendInvoice extends BaseAction
{
    protected array $validationRules = [
        'invoice_id' => 'required|exists:invoices,id',
        'recipients' => 'nullable|array',
        'recipients.*.email' => 'required|email',
        'recipients.*.cc' => 'nullable|array',
        'recipients.*.bcc' => 'nullable|array',
        'subject' => 'nullable|string|max:255',
        'message' => 'nullable|string',
        'attachments' => 'nullable|array',
        'send_copy_to_admin' => 'nullable|boolean',
        'admin_emails' => 'nullable|array',
    ];

    protected EmailService $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    public function execute(array $data): array
    {
        $this->data = $data;
        $this->validate($data);

        try {
            $invoice = Invoice::findOrFail($data['invoice_id']);
            
            // Generate invoice PDF
            $pdfPath = $this->generateInvoicePDF($invoice);

            // Prepare email data
            $recipients = $data['recipients'] ?? [
                ['email' => $invoice->user->email]
            ];

            $results = [
                'sent' => [],
                'failed' => [],
            ];

            // Send to each recipient
            foreach ($recipients as $recipient) {
                try {
                    $this->sendToRecipient($invoice, $recipient, $pdfPath, $data);
                    $results['sent'][] = $recipient['email'];
                } catch (\Exception $e) {
                    $results['failed'][] = [
                        'email' => $recipient['email'],
                        'error' => $e->getMessage(),
                    ];
                }
            }

            // Send copy to admin if requested
            if ($data['send_copy_to_admin'] ?? false) {
                $this->sendAdminCopy($invoice, $pdfPath, $data);
            }

            // Update invoice
            $invoice->update([
                'sent_at' => now(),
                'sent_count' => ($invoice->sent_count ?? 0) + count($results['sent']),
            ]);

            // Clean up PDF
            if (file_exists($pdfPath)) {
                unlink($pdfPath);
            }

            return $results;

        } catch (\Exception $e) {
            $this->logError('Failed to send invoice', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    protected function generateInvoicePDF(Invoice $invoice): string
    {
        try {
            $pdf = app('dompdf.wrapper');
            
            $data = [
                'invoice' => $invoice,
                'user' => $invoice->user,
                'company' => config('app.name'),
                'logo' => config('app.logo_url'),
                'address' => config('app.address', ''),
                'phone' => config('app.phone', ''),
                'email' => config('app.email', ''),
            ];

            $pdf->loadView('invoices.pdf', $data);
            
            $filename = "invoice_{$invoice->invoice_number}_{$invoice->id}.pdf";
            $path = storage_path("app/temp/{$filename}");
            
            // Ensure temp directory exists
            if (!is_dir(dirname($path))) {
                mkdir(dirname($path), 0755, true);
            }
            
            file_put_contents($path, $pdf->output());
            
            return $path;

        } catch (\Exception $e) {
            throw new \Exception("Failed to generate invoice PDF: " . $e->getMessage());
        }
    }

    protected function sendToRecipient(Invoice $invoice, array $recipient, string $pdfPath, array $data): void
    {
        $emailData = [
            'to' => $recipient['email'],
            'cc' => $recipient['cc'] ?? [],
            'bcc' => $recipient['bcc'] ?? [],
            'subject' => $data['subject'] ?? "Invoice #{$invoice->invoice_number} from " . config('app.name'),
            'html_body' => $data['message'] ?? view('emails.invoice', [
                'invoice' => $invoice,
                'user' => $invoice->user,
                'message' => $data['message'] ?? null,
            ])->render(),
            'attachments' => [
                [
                    'path' => $pdfPath,
                    'name' => "invoice-{$invoice->invoice_number}.pdf",
                    'mime' => 'application/pdf',
                ],
            ],
            'data' => [
                'invoice' => $invoice->toArray(),
                'user' => $invoice->user->toArray(),
            ],
        ];

        // Add additional attachments if provided
        if (isset($data['attachments']) && is_array($data['attachments'])) {
            $emailData['attachments'] = array_merge($emailData['attachments'], $data['attachments']);
        }

        $this->emailService->sendEmail($emailData);
    }

    protected function sendAdminCopy(Invoice $invoice, string $pdfPath, array $data): void
    {
        $adminEmails = $data['admin_emails'] ?? config('app.admin_emails', []);
        
        if (empty($adminEmails)) {
            return;
        }

        foreach ($adminEmails as $adminEmail) {
            try {
                $this->sendToRecipient($invoice, ['email' => $adminEmail], $pdfPath, [
                    'subject' => "Admin Copy: Invoice #{$invoice->invoice_number}",
                    'message' => "This is an administrative copy of invoice #{$invoice->invoice_number}.",
                ]);
            } catch (\Exception $e) {
                \Log::warning("Failed to send admin copy to {$adminEmail}: " . $e->getMessage());
            }
        }
    }
}