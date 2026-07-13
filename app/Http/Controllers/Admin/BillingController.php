<?php
// app/Http/Controllers/Admin/BillingController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Refund;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BillingController extends Controller
{
    /**
     * Payments Index.
     */
    public function payments(Request $request)
    {
        try {
            $tenantId = auth()->user()->tenant_id;
            
            $query = Payment::forTenant($tenantId)->with('user');
            
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('payment_id', 'LIKE', "%{$search}%")
                      ->orWhereHas('user', function ($q2) use ($search) {
                          $q2->where('name', 'LIKE', "%{$search}%")
                             ->orWhere('email', 'LIKE', "%{$search}%");
                      });
                });
            }
            
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            if ($request->filled('method')) {
                $query->where('payment_method', $request->method);
            }
            
            if ($request->filled('period')) {
                $dates = $this->getDateRange($request->period);
                $query->whereBetween('payment_date', $dates);
            }
            
            $payments = $query->latest()->paginate(15);
            
            $stats = [
                'total' => Payment::forTenant($tenantId)->count(),
                'completed' => Payment::forTenant($tenantId)->where('status', 'completed')->count(),
                'pending' => Payment::forTenant($tenantId)->where('status', 'pending')->count(),
                'total_amount' => Payment::forTenant($tenantId)->where('status', 'completed')->sum('amount'),
            ];
            
            $users = User::forTenant($tenantId)->get();
            
            return view('admin.billing.payments', compact('payments', 'stats', 'users'));
        } catch (\Exception $e) {
            Log::error('Error loading payments: ' . $e->getMessage());
            return back()->with('error', 'Unable to load payments.');
        }
    }

    /**
     * Payment Gateways.
     */
    public function gateways()
    {
        try {
            $gateways = [
                'stripe' => [
                    'enabled' => config('services.stripe.enabled', false),
                    'mode' => config('services.stripe.mode', 'live'),
                ],
                'paypal' => [
                    'enabled' => config('services.paypal.enabled', false),
                    'mode' => config('services.paypal.mode', 'live'),
                ],
                'razorpay' => [
                    'enabled' => config('services.razorpay.enabled', false),
                    'mode' => config('services.razorpay.mode', 'live'),
                ],
                'paddle' => [
                    'enabled' => config('services.paddle.enabled', false),
                    'mode' => config('services.paddle.mode', 'live'),
                ],
                'crypto' => [
                    'enabled' => config('services.crypto.enabled', false),
                    'mode' => config('services.crypto.mode', 'live'),
                ],
                'bank_transfer' => [
                    'enabled' => config('services.bank_transfer.enabled', false),
                    'mode' => config('services.bank_transfer.mode', 'live'),
                ],
            ];
            
            return view('admin.billing.gateways', compact('gateways'));
        } catch (\Exception $e) {
            Log::error('Error loading gateways: ' . $e->getMessage());
            return back()->with('error', 'Unable to load gateways.');
        }
    }

    /**
     * Invoices Index.
     */
    public function invoices(Request $request)
    {
        try {
            $tenantId = auth()->user()->tenant_id;
            
            $query = Invoice::forTenant($tenantId)->with('user');
            
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
            
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            $invoices = $query->latest()->paginate(15);
            
            $stats = [
                'total' => Invoice::forTenant($tenantId)->count(),
                'paid' => Invoice::forTenant($tenantId)->where('status', 'paid')->count(),
                'unpaid' => Invoice::forTenant($tenantId)->where('status', 'unpaid')->count(),
                'total_amount' => Invoice::forTenant($tenantId)->sum('amount'),
            ];
            
            $users = User::forTenant($tenantId)->get();
            
            return view('admin.billing.invoices', compact('invoices', 'stats', 'users'));
        } catch (\Exception $e) {
            Log::error('Error loading invoices: ' . $e->getMessage());
            return back()->with('error', 'Unable to load invoices.');
        }
    }

    /**
     * Refunds Index.
     */
    public function refunds(Request $request)
    {
        try {
            $tenantId = auth()->user()->tenant_id;
            
            $query = Refund::forTenant($tenantId)->with(['user', 'payment']);
            
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
            
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            $refunds = $query->latest()->paginate(15);
            
            $stats = [
                'total' => Refund::forTenant($tenantId)->count(),
                'completed' => Refund::forTenant($tenantId)->where('status', 'completed')->count(),
                'pending' => Refund::forTenant($tenantId)->where('status', 'pending')->count(),
                'total_amount' => Refund::forTenant($tenantId)->where('status', 'completed')->sum('amount'),
            ];
            
            $payments = Payment::forTenant($tenantId)->where('status', 'completed')->get();
            
            return view('admin.billing.refunds', compact('refunds', 'stats', 'payments'));
        } catch (\Exception $e) {
            Log::error('Error loading refunds: ' . $e->getMessage());
            return back()->with('error', 'Unable to load refunds.');
        }
    }

    /**
     * Get date range for filtering.
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
}