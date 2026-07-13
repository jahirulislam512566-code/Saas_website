<?php
// app/Http/Controllers/Admin/CustomerController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers.
     */
    public function index(Request $request)
    {
        try {
            $tenantId = auth()->user()->tenant_id;

            $query = Customer::forTenant($tenantId)
                ->withCount('orders')
                ->withSum('orders', 'total');

            // Search filter
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%")
                      ->orWhere('phone', 'LIKE', "%{$search}%");
                });
            }

            // Status filter
            if ($request->filled('status')) {
                $query->where('is_active', $request->status === 'active' ? 1 : 0);
            }

            // Sort
            $sortField = $request->get('sort', 'created_at');
            $sortDirection = $request->get('direction', 'desc');
            $allowedSorts = ['id', 'name', 'email', 'created_at', 'orders_count', 'total_spent'];
            
            if (in_array($sortField, $allowedSorts)) {
                $query->orderBy($sortField, $sortDirection);
            }

            $customers = $query->paginate(15)->withQueryString();

            // Get statistics
            $stats = [
                'total' => Customer::forTenant($tenantId)->count(),
                'active' => Customer::forTenant($tenantId)->where('is_active', true)->count(),
                'inactive' => Customer::forTenant($tenantId)->where('is_active', false)->count(),
                'total_orders' => Order::forTenant($tenantId)->count(),
                'total_spent' => Order::forTenant($tenantId)->where('status', 'completed')->sum('total'),
            ];

            return view('admin.customers.index', compact('customers', 'stats'));
        } catch (\Exception $e) {
            Log::error('Error fetching customers: ' . $e->getMessage());
            return back()->with('error', 'Unable to fetch customers.');
        }
    }

    /**
     * Show the specified customer.
     */
    public function show(Customer $customer)
    {
        try {
            $this->authorizeTenant($customer);
            
            $customer->load(['orders' => function ($query) {
                $query->latest()->limit(5);
            }]);

            $stats = [
                'total_orders' => $customer->orders()->count(),
                'total_spent' => $customer->orders()->where('status', 'completed')->sum('total'),
                'avg_order' => $customer->orders()->where('status', 'completed')->avg('total') ?? 0,
            ];

            return view('admin.customers.show', compact('customer', 'stats'));
        } catch (\Exception $e) {
            Log::error('Error showing customer: ' . $e->getMessage());
            return back()->with('error', 'Unable to display customer.');
        }
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(Customer $customer)
    {
        try {
            $this->authorizeTenant($customer);
            return view('admin.customers.form', compact('customer'));
        } catch (\Exception $e) {
            Log::error('Error loading edit customer form: ' . $e->getMessage());
            return back()->with('error', 'Unable to load edit customer form.');
        }
    }

    // Additional methods for create, store, update, destroy, etc.
    // ... (similar to other controllers)
}