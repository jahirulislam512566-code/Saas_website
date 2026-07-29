<?php

namespace App\Actions\Invoice;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GenerateInvoiceAction
{
    /**
     * Generate a new invoice
     *
     * @param array $data
     * @return Invoice
     */
    public function execute(array $data): Invoice
    {
        return DB::transaction(function () use ($data) {
            // Calculate totals
            $subtotal = $this->calculateSubtotal($data['items']);
            $tax = $this->calculateTax($subtotal, $data['tax_rate'] ?? 0);
            $discount = $this->calculateDiscount($subtotal, $data['discount'] ?? 0);
            $total = $subtotal + $tax - $discount;

            $invoice = Invoice::create([
                'invoice_number' => $this->generateInvoiceNumber(),
                'customer_id' => $data['customer_id'],
                'project_id' => $data['project_id'] ?? null,
                'issue_date' => $data['issue_date'] ?? now(),
                'due_date' => $data['due_date'] ?? now()->addDays(30),
                'subtotal' => $subtotal,
                'tax_rate' => $data['tax_rate'] ?? 0,
                'tax_amount' => $tax,
                'discount' => $data['discount'] ?? 0,
                'discount_type' => $data['discount_type'] ?? 'fixed',
                'total' => $total,
                'currency' => $data['currency'] ?? 'USD',
                'status' => 'draft',
                'notes' => $data['notes'] ?? null,
                'terms' => $data['terms'] ?? null,
                'created_by' => auth()->id(),
            ]);

            // Create invoice items
            foreach ($data['items'] as $item) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'description' => $item['description'],
                    'quantity' => $item['quantity'] ?? 1,
                    'unit_price' => $item['unit_price'],
                    'total' => ($item['quantity'] ?? 1) * $item['unit_price'],
                ]);
            }

            Log::info('Invoice generated', [
                'invoice_id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'customer_id' => $invoice->customer_id,
                'total' => $invoice->total,
                'created_by' => auth()->id()
            ]);

            return $invoice->load('items');
        });
    }

    /**
     * Generate unique invoice number
     *
     * @return string
     */
    private function generateInvoiceNumber(): string
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
     * Calculate subtotal
     *
     * @param array $items
     * @return float
     */
    private function calculateSubtotal(array $items): float
    {
        return array_reduce($items, function ($carry, $item) {
            $quantity = $item['quantity'] ?? 1;
            return $carry + ($quantity * $item['unit_price']);
        }, 0);
    }

    /**
     * Calculate tax
     *
     * @param float $subtotal
     * @param float $taxRate
     * @return float
     */
    private function calculateTax(float $subtotal, float $taxRate): float
    {
        return ($subtotal * $taxRate) / 100;
    }

    /**
     * Calculate discount
     *
     * @param float $subtotal
     * @param float $discount
     * @param string $discountType
     * @return float
     */
    private function calculateDiscount(float $subtotal, float $discount, string $discountType = 'fixed'): float
    {
        if ($discountType === 'percentage') {
            return ($subtotal * $discount) / 100;
        }
        
        return $discount;
    }
}