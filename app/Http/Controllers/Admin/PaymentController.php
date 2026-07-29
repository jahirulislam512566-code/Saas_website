<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    /**
     * Display a listing of payments with filters.
     */
    public function index(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;

        $query = Payment::with(['user', 'subscription', 'order'])
            ->when($tenantId, function ($q) use ($tenantId) {
                return $q->where('tenant_id', $tenantId);
            });

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search by transaction ID or user
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($sub) use ($search) {
                      $sub->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $payments = $query->latest()->paginate(20);
        
        // Get summary statistics
        $stats = $this->getPaymentStats($query);

        $statuses = ['pending', 'completed', 'failed', 'refunded', 'cancelled'];
        $paymentMethods = ['credit_card', 'paypal', 'bank_transfer', 'crypto', 'stripe', 'razorpay'];

        return view('admin.payments.index', compact(
            'payments', 
            'stats', 
            'statuses', 
            'paymentMethods'
        ));
    }

    /**
     * Show the form for creating a new payment.
     */
    public function create()
    {
        $tenantId = Auth::user()->tenant_id;
        
        $users = User::when($tenantId, function ($q) use ($tenantId) {
            return $q->where('tenant_id', $tenantId);
        })->get();
        
        $subscriptions = Subscription::when($tenantId, function ($q) use ($tenantId) {
            return $q->where('tenant_id', $tenantId);
        })->with('user')->get();

        $orders = Order::when($tenantId, function ($q) use ($tenantId) {
            return $q->where('tenant_id', $tenantId);
        })->where('status', 'pending')->get();

        $paymentMethods = ['credit_card', 'paypal', 'bank_transfer', 'crypto', 'stripe', 'razorpay'];
        $currencies = ['USD', 'EUR', 'GBP', 'CAD', 'AUD', 'INR', 'BDT'];

        return view('admin.payments.create', compact(
            'users', 
            'subscriptions',
            'orders',
            'paymentMethods', 
            'currencies'
        ));
    }

    /**
     * Store a newly created payment.
     */
    public function store(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;

        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'subscription_id' => ['nullable', 'exists:subscriptions,id'],
            'order_id' => ['nullable', 'exists:orders,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'currency' => ['required', 'string', 'size:3'],
            'payment_method' => ['required', Rule::in(['credit_card', 'paypal', 'bank_transfer', 'crypto', 'stripe', 'razorpay'])],
            'status' => ['required', Rule::in(['pending', 'completed', 'failed', 'refunded', 'cancelled'])],
            'transaction_id' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'payment_date' => ['nullable', 'date'],
        ]);

        DB::beginTransaction();
        try {
            $payment = Payment::create([
                'tenant_id' => $tenantId,
                'user_id' => $validated['user_id'],
                'subscription_id' => $validated['subscription_id'] ?? null,
                'order_id' => $validated['order_id'] ?? null,
                'amount' => $validated['amount'],
                'currency' => $validated['currency'],
                'payment_method' => $validated['payment_method'],
                'status' => $validated['status'],
                'transaction_id' => $validated['transaction_id'] ?? $this->generateTransactionId(),
                'description' => $validated['description'],
                'payment_date' => $validated['payment_date'] ?? now(),
                'payment_details' => [
                    'created_by' => Auth::user()->name,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ],
            ]);

            // If payment is completed, update subscription status
            if ($payment->status === 'completed' && $payment->subscription_id) {
                $this->updateSubscriptionAfterPayment($payment);
            }

            // If payment is completed and has order, update order
            if ($payment->status === 'completed' && $payment->order_id) {
                $this->updateOrderAfterPayment($payment);
            }

            DB::commit();

            return redirect()->route('admin.payments.index')
                ->with('success', 'Payment created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment creation failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to create payment. Please try again.');
        }
    }

    /**
     * Display the specified payment.
     */
    public function show(Payment $payment)
    {
        $this->authorizeTenant($payment);
        $payment->load(['user', 'subscription', 'subscription.plan', 'order']);
        
        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified payment.
     */
    public function edit(Payment $payment)
    {
        $this->authorizeTenant($payment);
        
        $tenantId = Auth::user()->tenant_id;
        $users = User::when($tenantId, function ($q) use ($tenantId) {
            return $q->where('tenant_id', $tenantId);
        })->get();
        
        $subscriptions = Subscription::when($tenantId, function ($q) use ($tenantId) {
            return $q->where('tenant_id', $tenantId);
        })->with('user')->get();

        $orders = Order::when($tenantId, function ($q) use ($tenantId) {
            return $q->where('tenant_id', $tenantId);
        })->get();

        $paymentMethods = ['credit_card', 'paypal', 'bank_transfer', 'crypto', 'stripe', 'razorpay'];
        $currencies = ['USD', 'EUR', 'GBP', 'CAD', 'AUD', 'INR', 'BDT'];

        return view('admin.payments.edit', compact(
            'payment',
            'users', 
            'subscriptions',
            'orders',
            'paymentMethods', 
            'currencies'
        ));
    }

    /**
     * Update the specified payment.
     */
    public function update(Request $request, Payment $payment)
    {
        $this->authorizeTenant($payment);

        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'subscription_id' => ['nullable', 'exists:subscriptions,id'],
            'order_id' => ['nullable', 'exists:orders,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'currency' => ['required', 'string', 'size:3'],
            'payment_method' => ['required', Rule::in(['credit_card', 'paypal', 'bank_transfer', 'crypto', 'stripe', 'razorpay'])],
            'status' => ['required', Rule::in(['pending', 'completed', 'failed', 'refunded', 'cancelled'])],
            'transaction_id' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'payment_date' => ['nullable', 'date'],
        ]);

        DB::beginTransaction();
        try {
            $oldStatus = $payment->status;
            $payment->update($validated);

            // If status changed to completed and subscription exists
            if ($oldStatus !== 'completed' && $payment->status === 'completed') {
                if ($payment->subscription_id) {
                    $this->updateSubscriptionAfterPayment($payment);
                }
                if ($payment->order_id) {
                    $this->updateOrderAfterPayment($payment);
                }
            }

            DB::commit();

            return redirect()->route('admin.payments.index')
                ->with('success', 'Payment updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment update failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to update payment. Please try again.');
        }
    }

    /**
     * Remove the specified payment.
     */
    public function destroy(Payment $payment)
    {
        $this->authorizeTenant($payment);

        try {
            $payment->delete();
            return redirect()->route('admin.payments.index')
                ->with('success', 'Payment deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Payment deletion failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete payment.');
        }
    }

    /**
     * Refund a payment.
     */
    public function refund(Request $request, Payment $payment)
    {
        $this->authorizeTenant($payment);

        $request->validate([
            'reason' => ['required', 'string', 'max:500'],
            'partial_amount' => ['nullable', 'numeric', 'min:0.01', 'max:' . $payment->amount],
        ]);

        if ($payment->status !== 'completed') {
            return back()->with('error', 'Only completed payments can be refunded.');
        }

        if ($payment->is_refunded) {
            return back()->with('error', 'This payment has already been refunded.');
        }

        DB::beginTransaction();
        try {
            $refundAmount = $request->partial_amount ?? $payment->amount;
            
            $payment->update([
                'status' => 'refunded',
                'refunded_at' => now(),
                'refund_amount' => $refundAmount,
                'refund_reason' => $request->reason,
                'payment_details' => array_merge(
                    $payment->payment_details ?? [],
                    [
                        'refunded_by' => Auth::user()->name,
                        'refunded_at' => now()->toISOString(),
                        'refund_reason' => $request->reason,
                    ]
                ),
            ]);

            DB::commit();

            return redirect()->route('admin.payments.show', $payment)
                ->with('success', "Payment refunded successfully (Amount: {$refundAmount} {$payment->currency}).");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment refund failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to refund payment.');
        }
    }

    /**
     * Void a payment.
     */
    public function void(Payment $payment)
    {
        $this->authorizeTenant($payment);

        if ($payment->status !== 'pending') {
            return back()->with('error', 'Only pending payments can be voided.');
        }

        try {
            $payment->update([
                'status' => 'cancelled',
                'voided_at' => now(),
                'voided_by' => Auth::user()->name,
                'payment_details' => array_merge(
                    $payment->payment_details ?? [],
                    [
                        'voided_by' => Auth::user()->name,
                        'voided_at' => now()->toISOString(),
                    ]
                ),
            ]);

            return redirect()->route('admin.payments.show', $payment)
                ->with('success', 'Payment voided successfully.');
        } catch (\Exception $e) {
            Log::error('Payment void failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to void payment.');
        }
    }

    /**
     * Export payments to CSV.
     */
    public function export(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;

        $payments = Payment::with(['user', 'subscription', 'order'])
            ->when($tenantId, function ($q) use ($tenantId) {
                return $q->where('tenant_id', $tenantId);
            })
            ->latest()
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=payments_' . date('Y-m-d') . '.csv',
        ];

        $callback = function () use ($payments) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'ID', 'User', 'Subscription', 'Order', 'Amount', 'Currency', 
                'Payment Method', 'Status', 'Transaction ID', 'Date'
            ]);

            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->id,
                    $payment->user->name ?? 'N/A',
                    $payment->subscription->name ?? 'N/A',
                    $payment->order->order_number ?? 'N/A',
                    $payment->amount,
                    $payment->currency,
                    $payment->payment_method,
                    $payment->status,
                    $payment->transaction_id,
                    $payment->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Generate invoice for payment.
     */
    public function invoice(Payment $payment)
    {
        $this->authorizeTenant($payment);
        // Logic to generate invoice
        return view('admin.payments.invoice', compact('payment'));
    }

    /**
     * Download invoice for payment.
     */
    public function downloadInvoice(Payment $payment)
    {
        $this->authorizeTenant($payment);
        // Logic to download invoice
        // return response()->download($path);
    }

    /**
     * Search payments.
     */
    public function search(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;
        $search = $request->q;

        $payments = Payment::when($tenantId, function ($q) use ($tenantId) {
            return $q->where('tenant_id', $tenantId);
        })
        ->where(function ($q) use ($search) {
            $q->where('transaction_id', 'like', "%{$search}%")
              ->orWhereHas('user', function ($sub) use ($search) {
                  $sub->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
              });
        })
        ->limit(10)
        ->get(['id', 'transaction_id', 'amount', 'status']);

        return response()->json($payments);
    }

    /**
     * Get payment statistics for dashboard.
     */
    protected function getPaymentStats($query)
    {
        $baseQuery = clone $query;
        
        return [
            'total_revenue' => (clone $baseQuery)->where('status', 'completed')->sum('amount'),
            'total_payments' => (clone $baseQuery)->count(),
            'pending_payments' => (clone $baseQuery)->where('status', 'pending')->count(),
            'failed_payments' => (clone $baseQuery)->where('status', 'failed')->count(),
            'completed_payments' => (clone $baseQuery)->where('status', 'completed')->count(),
            'refunded_amount' => (clone $baseQuery)->where('status', 'refunded')->sum('amount'),
            'average_payment' => (clone $baseQuery)->where('status', 'completed')->avg('amount'),
        ];
    }

    /**
     * Generate a unique transaction ID.
     */
    protected function generateTransactionId(): string
    {
        return 'TXN-' . strtoupper(uniqid()) . '-' . rand(1000, 9999);
    }

    /**
     * Update subscription after successful payment.
     */
    protected function updateSubscriptionAfterPayment(Payment $payment)
    {
        $subscription = $payment->subscription;
        if (!$subscription) {
            return;
        }

        $subscription->update([
            'status' => 'active',
            'last_payment_at' => now(),
            'next_payment_at' => $this->calculateNextPaymentDate($subscription->billing_cycle ?? 'monthly'),
        ]);

        // Update user's subscription status
        if ($payment->user) {
            $payment->user->update([
                'subscription_status' => 'active',
            ]);
        }
    }

    /**
     * Update order after successful payment.
     */
    protected function updateOrderAfterPayment(Payment $payment)
    {
        $order = $payment->order;
        if (!$order) {
            return;
        }

        $order->update([
            'payment_status' => 'paid',
            'status' => 'processing',
            'paid_at' => now(),
        ]);
    }

    /**
     * Calculate next payment date based on billing cycle.
     */
    protected function calculateNextPaymentDate($billingCycle)
    {
        $now = now();
        return match ($billingCycle) {
            'monthly' => $now->addMonth(),
            'quarterly' => $now->addMonths(3),
            'semi-annual' => $now->addMonths(6),
            'annual' => $now->addYear(),
            default => $now->addMonth(),
        };
    }

    /**
     * Authorize that the payment belongs to the current tenant.
     */
    protected function authorizeTenant(Payment $payment)
    {
        $tenantId = Auth::user()->tenant_id;
        if ($tenantId && $payment->tenant_id !== $tenantId) {
            abort(403, 'Unauthorized action.');
        }
    }
}