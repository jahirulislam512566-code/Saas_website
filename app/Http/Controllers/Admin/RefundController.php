<?php
// app/Http/Controllers/Admin/RefundController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Refund;
use App\Models\Payment;
use App\Models\User;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RefundController extends Controller
{
    /**
     * Display a listing of refunds.
     */
    public function index(Request $request)
    {
        try {
            $tenantId = auth()->user()->tenant_id;

            $query = Refund::forTenant($tenantId)->with(['user', 'payment']);

            // Search filter
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('refund_id', 'LIKE', "%{$search}%")
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

            $refunds = $query->latest()->paginate(15)->withQueryString();

            // Get statistics
            $stats = [
                'total' => Refund::forTenant($tenantId)->count(),
                'completed' => Refund::forTenant($tenantId)->where('status', 'completed')->count(),
                'pending' => Refund::forTenant($tenantId)->where('status', 'pending')->count(),
                'failed' => Refund::forTenant($tenantId)->where('status', 'failed')->count(),
                'total_amount' => Refund::forTenant($tenantId)->where('status', 'completed')->sum('amount'),
            ];

            // Get completed payments for refund selection
            $payments = Payment::forTenant($tenantId)
                ->where('status', 'completed')
                ->whereDoesntHave('refunds', function ($q) {
                    $q->where('status', '!=', 'failed');
                })
                ->get();

            return view('admin.billing.refunds', compact('refunds', 'stats', 'payments'));
        } catch (\Exception $e) {
            Log::error('Error fetching refunds: ' . $e->getMessage());
            return back()->with('error', 'Unable to fetch refunds.');
        }
    }

    /**
     * Show a specific refund.
     */
    public function show(Refund $refund)
    {
        try {
            $this->authorizeTenant($refund);
            
            $refund->load(['user', 'payment']);
            
            return view('admin.billing.refunds.show', compact('refund'));
        } catch (\Exception $e) {
            Log::error('Error showing refund: ' . $e->getMessage());
            return back()->with('error', 'Unable to display refund.');
        }
    }

    /**
     * Store a new refund.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'payment_id' => ['required', 'exists:payments,id'],
                'amount' => ['required', 'numeric', 'min:0.01'],
                'reason' => ['required', 'string', 'max:255'],
                'notes' => ['nullable', 'string'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            DB::beginTransaction();

            try {
                $tenantId = auth()->user()->tenant_id;
                $payment = Payment::findOrFail($request->payment_id);

                // Check if payment is refundable
                if ($payment->status !== 'completed') {
                    return back()->with('error', 'Only completed payments can be refunded.');
                }

                // Check if already refunded
                if ($payment->refunds()->where('status', '!=', 'failed')->exists()) {
                    return back()->with('error', 'This payment already has a refund.');
                }

                // Check refund amount
                $totalRefunded = $payment->refunds()->where('status', 'completed')->sum('amount');
                $remaining = $payment->amount - $totalRefunded;

                if ($request->amount > $remaining) {
                    return back()->with('error', "Refund amount cannot exceed remaining balance of $" . number_format($remaining, 2));
                }

                // Generate refund ID
                $refundId = 'REF-' . Str::random(8) . '-' . time();

                $refund = Refund::create([
                    'tenant_id' => $tenantId,
                    'user_id' => $payment->user_id,
                    'payment_id' => $payment->id,
                    'refund_id' => $refundId,
                    'amount' => $request->amount,
                    'currency' => $payment->currency,
                    'reason' => $request->reason,
                    'notes' => $request->notes,
                    'status' => 'pending',
                    'created_by' => auth()->id(),
                ]);

                // Update payment status if fully refunded
                $totalRefunded = $payment->refunds()->where('status', 'completed')->sum('amount') + $request->amount;
                if ($totalRefunded >= $payment->amount) {
                    $payment->update(['status' => 'refunded']);
                }

                // Log activity
                Activity::create([
                    'user_id' => auth()->id(),
                    'tenant_id' => $tenantId,
                    'subject_type' => Refund::class,
                    'subject_id' => $refund->id,
                    'action' => 'created_refund',
                    'description' => "Created refund #{$refundId} for payment #{$payment->payment_id}",
                    'properties' => [
                        'refund_id' => $refundId,
                        'payment_id' => $payment->payment_id,
                        'amount' => $request->amount,
                        'reason' => $request->reason,
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                DB::commit();

                return redirect()->route('admin.billing.refunds.show', $refund)
                    ->with('success', "Refund #{$refundId} created successfully.");
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error creating refund: ' . $e->getMessage());
            return back()->with('error', 'Failed to create refund.');
        }
    }

    /**
     * Process a pending refund.
     */
    public function process(Request $request, Refund $refund)
    {
        try {
            $this->authorizeTenant($refund);

            if ($refund->status !== 'pending') {
                return back()->with('error', 'Only pending refunds can be processed.');
            }

            DB::beginTransaction();

            try {
                // In production, this would actually process the refund
                // with the payment gateway
                $refund->update([
                    'status' => 'completed',
                    'processed_at' => now(),
                    'processed_by' => auth()->id(),
                ]);

                // Update payment status
                $payment = $refund->payment;
                $totalRefunded = $payment->refunds()->where('status', 'completed')->sum('amount');
                
                if ($totalRefunded >= $payment->amount) {
                    $payment->update(['status' => 'refunded']);
                }

                // Log activity
                Activity::create([
                    'user_id' => auth()->id(),
                    'tenant_id' => $refund->tenant_id,
                    'subject_type' => Refund::class,
                    'subject_id' => $refund->id,
                    'action' => 'processed_refund',
                    'description' => "Processed refund #{$refund->refund_id}",
                    'properties' => [
                        'refund_id' => $refund->refund_id,
                        'amount' => $refund->amount,
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                DB::commit();

                return redirect()->route('admin.billing.refunds.show', $refund)
                    ->with('success', 'Refund processed successfully.');
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error processing refund: ' . $e->getMessage());
            return back()->with('error', 'Failed to process refund.');
        }
    }

    /**
     * Cancel a pending refund.
     */
    public function cancel(Request $request, Refund $refund)
    {
        try {
            $this->authorizeTenant($refund);

            if ($refund->status !== 'pending') {
                return back()->with('error', 'Only pending refunds can be cancelled.');
            }

            DB::beginTransaction();

            try {
                $refund->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                    'cancelled_by' => auth()->id(),
                ]);

                // Log activity
                Activity::create([
                    'user_id' => auth()->id(),
                    'tenant_id' => $refund->tenant_id,
                    'subject_type' => Refund::class,
                    'subject_id' => $refund->id,
                    'action' => 'cancelled_refund',
                    'description' => "Cancelled refund #{$refund->refund_id}",
                    'properties' => [
                        'refund_id' => $refund->refund_id,
                        'amount' => $refund->amount,
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
                      
                DB::commit();

                return redirect()->route('admin.billing.refunds.show', $refund)
                    ->with('success', 'Refund cancelled successfully.');
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error cancelling refund: ' . $e->getMessage());
            return back()->with('error', 'Failed to cancel refund.');
        }
    }

    /**
     * Export refunds to CSV.
     */
    public function export(Request $request)
    {
        try {
            $tenantId = auth()->user()->tenant_id;
            $refunds = Refund::forTenant($tenantId)->with(['user', 'payment'])->get();

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="refunds_' . date('Y-m-d') . '.csv"',
            ];

            $callback = function () use ($refunds) {
                $handle = fopen('php://output', 'w');
                fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

                fputcsv($handle, [
                    'Refund #', 'Payment #', 'User', 'Email', 'Amount', 'Currency',
                    'Status', 'Reason', 'Created At', 'Processed At'
                ]);

                foreach ($refunds as $refund) {
                    fputcsv($handle, [
                        $refund->refund_id,
                        $refund->payment->payment_id ?? 'N/A',
                        $refund->user->name ?? 'N/A',
                        $refund->user->email ?? 'N/A',
                        $refund->amount,
                        $refund->currency,
                        $refund->status,
                        $refund->reason,
                        $refund->created_at->format('Y-m-d H:i:s'),
                        $refund->processed_at ? $refund->processed_at->format('Y-m-d H:i:s') : 'N/A',
                    ]);
                }

                fclose($handle);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            Log::error('Error exporting refunds: ' . $e->getMessage());
            return back()->with('error', 'Failed to export refunds.');
        }
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
//     protected function authorizeTenant($model)
//     {
//         if ($model->tenant_id !== auth()->user()->tenant_id) {
//             abort(403, 'Unauthorized action.');
//         }
//     }
}
