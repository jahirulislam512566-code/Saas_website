<?php

namespace App\Observers;

use App\Models\Invoice;
use App\Enums\InvoiceStatus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class InvoiceObserver extends BaseObserver
{
    /**
     * Handle the Invoice "creating" event.
     */
    public function creating(Invoice $invoice): void
    {
        // Generate invoice number
        if (empty($invoice->invoice_number)) {
            $invoice->invoice_number = $this->generateInvoiceNumber();
        }

        // Set default status
        if (empty($invoice->status)) {
            $invoice->status = InvoiceStatus::DRAFT->value;
        }

        // Calculate totals if not set
        if (empty($invoice->subtotal) && $invoice->items) {
            $this->calculateTotals($invoice);
        }

        $this->log('creating', $invoice);
    }

    /**
     * Handle the Invoice "created" event.
     */
    public function created(Invoice $invoice): void
    {
        // Clear cache
        Cache::forget('invoices_count');
        Cache::forget('invoices_list');

        // Create audit log
        $this->createAuditLog($invoice, 'created');

        $this->log('created', $invoice);
    }

    /**
     * Handle the Invoice "updating" event.
     */
    public function updating(Invoice $invoice): void
    {
        // Track status changes
        if ($invoice->isDirty('status')) {
            $oldStatus = $invoice->getOriginal('status');
            $newStatus = $invoice->status;
            
            $this->validateStatusTransition($oldStatus, $newStatus);
            $this->logStatusChange($invoice, $oldStatus, $newStatus);
        }

        $this->log('updating', $invoice);
    }

    /**
     * Handle the Invoice "updated" event.
     */
    public function updated(Invoice $invoice): void
    {
        // Clear cache
        Cache::forget("invoice_{$invoice->id}");
        Cache::forget("invoice_{$invoice->invoice_number}");
        Cache::forget('invoices_list');

        // Create audit log
        $this->createAuditLog($invoice, 'updated', $invoice->getChanges());

        $this->log('updated', $invoice);
    }

    /**
     * Handle the Invoice "deleted" event.
     */
    public function deleted(Invoice $invoice): void
    {
        // Clear cache
        Cache::forget("invoice_{$invoice->id}");
        Cache::forget('invoices_count');
        Cache::forget('invoices_list');

        $this->log('deleted', $invoice);
    }

    /**
     * Generate unique invoice number.
     */
    protected function generateInvoiceNumber(): string
    {
        $prefix = 'INV';
        $year = date('Y');
        $month = date('m');
        $lastInvoice = Invoice::whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))
            ->orderBy('id', 'desc')
            ->first();

        $number = $lastInvoice ? intval(substr($lastInvoice->invoice_number, -4)) + 1 : 1;
        
        return sprintf('%s-%s%s-%04d', $prefix, $year, $month, $number);
    }

    /**
     * Calculate invoice totals.
     */
    protected function calculateTotals(Invoice $invoice): void
    {
        $subtotal = 0;
        
        foreach ($invoice->items as $item) {
            $subtotal += $item->quantity * $item->unit_price;
        }

        $invoice->subtotal = $subtotal;
        $invoice->tax_amount = $subtotal * ($invoice->tax_rate / 100);
        $invoice->total = $subtotal + $invoice->tax_amount - ($invoice->discount ?? 0);
    }

    /**
     * Validate status transition.
     */
    protected function validateStatusTransition(string $oldStatus, string $newStatus): void
    {
        $allowedTransitions = [
            'draft' => ['sent', 'cancelled'],
            'sent' => ['viewed', 'pending', 'overdue'],
            'viewed' => ['pending', 'overdue'],
            'pending' => ['partially_paid', 'paid', 'overdue'],
            'partially_paid' => ['paid', 'overdue'],
            'paid' => ['refunded'],
            'overdue' => ['pending', 'paid', 'collection'],
            'collection' => ['paid', 'cancelled'],
            'disputed' => ['pending', 'paid', 'cancelled'],
        ];

        if (!in_array($newStatus, $allowedTransitions[$oldStatus] ?? [])) {
            throw new \Exception("Invalid status transition from {$oldStatus} to {$newStatus}");
        }
    }

    /**
     * Log status change.
     */
    protected function logStatusChange(Invoice $invoice, string $oldStatus, string $newStatus): void
    {
        Log::info('Invoice status changed', [
            'invoice_id' => $invoice->id,
            'invoice_number' => $invoice->invoice_number,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'changed_by' => $this->getUserId(),
        ]);
    }

    /**
     * Create audit log.
     */
    protected function createAuditLog(Invoice $invoice, string $action, array $changes = []): void
    {
        // Create audit log entry
        // \App\Models\AuditLog::create([...]);
    }
}