<?php
// app/Http/Controllers/Admin/InvoiceController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Payment;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    /**
     * Display a listing of invoices.
     */
    public function index(Request $request)
    {
        try {
            $tenantId = auth()->user()->tenant_id;

            $query = Invoice::forTenant($tenantId)->with('user');

            // Search filter
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('invoice_number', 'LIKE', "%{$search}%")
                      ->orWhereHas('user', function ($q2) use ($search) {
                          $q2->where('name', 'LIKE', "%{$search}%")
                             ->orWhere('email', 'LIKE', "%{$search}%");
                      });
                });
            }

            // Status filter
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Date range filter
            if ($request->filled('period')) {
                $dates = $this->getDateRange($request->period);
                $query->whereBetween('created_at', $dates);
            }

            $invoices = $query->latest()->paginate(15)->withQueryString();

            // Get statistics
            $stats = [
                'total' => Invoice::forTenant($tenantId)->count(),
                'paid' => Invoice::forTenant($tenantId)->where('status', 'paid')->count(),
                'unpaid' => Invoice::forTenant($tenantId)->where('status', 'unpaid')->count(),
                'overdue' => Invoice::forTenant($tenantId)->where('status', 'overdue')->count(),
                'total_amount' => Invoice::forTenant($tenantId)->sum('amount'),
            ];

            // Get users for filter
            $users = User::forTenant($tenantId)->get();

            return view('admin.billing.invoices', compact('invoices', 'stats', 'users'));
        } catch (\Exception $e) {
            Log::error('Error fetching invoices: ' . $e->getMessage());
            return back()->with('error', 'Unable to fetch invoices.');
        }
    }

    /**
     * Show a specific invoice.
     */
    public function show(Invoice $invoice)
    {
        try {
            $this->authorizeTenant($invoice);
            
            $invoice->load(['user', 'items', 'payments']);
            
            return view('admin.billing.invoices.show', compact('invoice'));
        } catch (\Exception $e) {
            Log::error('Error showing invoice: ' . $e->getMessage());
            return back()->with('error', 'Unable to display invoice.');
        }
    }

    /**
     * Generate a new invoice.
     */
    public function generate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => ['required', 'exists:users,id'],
                'amount' => ['required', 'numeric', 'min:0.01'],
                'due_date' => ['required', 'date', 'after:today'],
                'description' => ['nullable', 'string'],
                'items' => ['nullable', 'array'],
                'items.*.description' => ['required', 'string'],
                'items.*.amount' => ['required', 'numeric', 'min:0'],
                'items.*.quantity' => ['required', 'integer', 'min:1'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            DB::beginTransaction();

            try {
                $tenantId = auth()->user()->tenant_id;
                $user = User::findOrFail($request->user_id);

                // Generate invoice number
                $invoiceNumber = $this->generateInvoiceNumber();

                // Calculate total from items or use provided amount
                $totalAmount = $request->amount;
                if ($request->has('items') && count($request->items) > 0) {
                    $totalAmount = 0;
                    foreach ($request->items as $item) {
                        $totalAmount += $item['amount'] * $item['quantity'];
                    }
                }

                $invoice = Invoice::create([
                    'tenant_id' => $tenantId,
                    'user_id' => $user->id,
                    'invoice_number' => $invoiceNumber,
                    'amount' => $totalAmount,
                    'currency' => $request->currency ?? 'USD',
                    'status' => 'unpaid',
                    'due_date' => $request->due_date,
                    'description' => $request->description,
                    'items' => $request->items ?? [],
                    'created_by' => auth()->id(),
                ]);

                // Log activity
                Activity::create([
                    'user_id' => auth()->id(),
                    'tenant_id' => $tenantId,
                    'subject_type' => Invoice::class,
                    'subject_id' => $invoice->id,
                    'action' => 'generated_invoice',
                    'description' => "Generated invoice #{$invoiceNumber} for {$user->name}",
                    'properties' => [
                        'invoice_number' => $invoiceNumber,
                        'user_name' => $user->name,
                        'amount' => $totalAmount,
                        'due_date' => $request->due_date,
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                DB::commit();

                return redirect()->route('admin.billing.invoices.view', $invoice)
                    ->with('success', "Invoice #{$invoiceNumber} generated successfully.");
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error generating invoice: ' . $e->getMessage());
            return back()->with('error', 'Failed to generate invoice.');
        }
    }

    /**
     * Mark invoice as paid.
     */
    public function markPaid(Request $request, Invoice $invoice)
    {
        try {
            $this->authorizeTenant($invoice);

            if ($invoice->status === 'paid') {
                return back()->with('error', 'Invoice is already paid.');
            }

            DB::beginTransaction();

            try {
                $invoice->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);

                // Create payment record if not exists
                if (!$invoice->payments()->exists()) {
                    Payment::create([
                        'tenant_id' => $invoice->tenant_id,
                        'user_id' => $invoice->user_id,
                        'invoice_id' => $invoice->id,
                        'payment_id' => 'PAY-' . Str::random(8),
                        'amount' => $invoice->amount,
                        'currency' => $invoice->currency,
                        'payment_method' => 'manual',
                        'status' => 'completed',
                        'payment_date' => now(),
                        'created_by' => auth()->id(),
                    ]);
                }

                // Log activity
                Activity::create([
                    'user_id' => auth()->id(),
                    'tenant_id' => $invoice->tenant_id,
                    'subject_type' => Invoice::class,
                    'subject_id' => $invoice->id,
                    'action' => 'marked_invoice_paid',
                    'description' => "Marked invoice #{$invoice->invoice_number} as paid",
                    'properties' => [
                        'invoice_number' => $invoice->invoice_number,
                        'amount' => $invoice->amount,
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                DB::commit();

                return redirect()->route('admin.billing.invoices.view', $invoice)
                    ->with('success', 'Invoice marked as paid successfully.');
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error marking invoice as paid: ' . $e->getMessage());
            return back()->with('error', 'Failed to mark invoice as paid.');
        }
    }

    /**
     * Cancel invoice.
     */
    public function cancel(Request $request, Invoice $invoice)
    {
        try {
            $this->authorizeTenant($invoice);

            if ($invoice->status === 'paid') {
                return back()->with('error', 'Cannot cancel a paid invoice.');
            }

            DB::beginTransaction();

            try {
                $invoice->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                ]);

                // Log activity
                Activity::create([
                    'user_id' => auth()->id(),
                    'tenant_id' => $invoice->tenant_id,
                    'subject_type' => Invoice::class,
                    'subject_id' => $invoice->id,
                    'action' => 'cancelled_invoice',
                    'description' => "Cancelled invoice #{$invoice->invoice_number}",
                    'properties' => [
                        'invoice_number' => $invoice->invoice_number,
                        'amount' => $invoice->amount,
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                DB::commit();

                return redirect()->route('admin.billing.invoices.view', $invoice)
                    ->with('success', 'Invoice cancelled successfully.');
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error cancelling invoice: ' . $e->getMessage());
            return back()->with('error', 'Failed to cancel invoice.');
        }
    }

    /**
     * Download invoice PDF.
     */
    public function download(Invoice $invoice)
    {
        try {
            $this->authorizeTenant($invoice);
            
            $invoice->load(['user', 'items']);

            // In production, you would use a PDF library like DomPDF
            // For now, we'll return a simple view
            $pdf = PDF::loadView('admin.billing.invoices.pdf', compact('invoice'));
            
            return $pdf->download("invoice-{$invoice->invoice_number}.pdf");
        } catch (\Exception $e) {
            Log::error('Error downloading invoice: ' . $e->getMessage());
            return back()->with('error', 'Failed to download invoice.');
        }
    }

    /**
     * Export invoices to CSV.
     */
    public function export(Request $request)
    {
        try {
            $tenantId = auth()->user()->tenant_id;
            $invoices = Invoice::forTenant($tenantId)->with('user')->get();

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="invoices_' . date('Y-m-d') . '.csv"',
            ];

            $callback = function () use ($invoices) {
                $handle = fopen('php://output', 'w');
                fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

                fputcsv($handle, [
                    'Invoice #', 'User', 'Email', 'Amount', 'Currency', 
                    'Status', 'Due Date', 'Paid At', 'Created At'
                ]);

                foreach ($invoices as $invoice) {
                    fputcsv($handle, [
                        $invoice->invoice_number,
                        $invoice->user->name ?? 'N/A',
                        $invoice->user->email ?? 'N/A',
                        $invoice->amount,
                        $invoice->currency,
                        $invoice->status,
                        $invoice->due_date->format('Y-m-d'),
                        $invoice->paid_at ? $invoice->paid_at->format('Y-m-d H:i:s') : 'N/A',
                        $invoice->created_at->format('Y-m-d H:i:s'),
                    ]);
                }

                fclose($handle);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            Log::error('Error exporting invoices: ' . $e->getMessage());
            return back()->with('error', 'Failed to export invoices.');
        }
    }

    /**
     * Generate invoice number.
     */
    private function generateInvoiceNumber()
    {
        $prefix = 'INV';
        $year = date('Y');
        $month = date('m');
        
        $lastInvoice = Invoice::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastInvoice ? intval(substr($lastInvoice->invoice_number, -4)) + 1 : 1;

        return $prefix . '-' . $year . $month . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get date range helper.
     */
    private function getDateRange($period)
    {
        switch ($period) {
            case 'today':
                return [now()->startOfDay(), now()->endOfDay()];
            case 'week':
                return [now()->startOfWeek(), now()->endOfWeek()];
            case 'month':
                return [now()->startOfMonth(), now()->endOfMonth()];
            case 'quarter':
                return [now()->startOfQuarter(), now()->endOfQuarter()];
            case 'year':
                return [now()->startOfYear(), now()->endOfYear()];
            default:
                return [now()->subYear(), now()];
        }
    }

    /**
     * Authorize tenant.
     */
    protected function authorizeTenant($model)
    {
        if ($model->tenant_id !== auth()->user()->tenant_id) {
            abort(403, 'Unauthorized action.');
        }
    }
}