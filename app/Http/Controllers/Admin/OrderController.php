<?php
// app/Http/Controllers/Admin/OrderController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index(Request $request)
    {
        try {
            $tenantId = auth()->user()->tenant_id;

            $query = Order::forTenant($tenantId)->with(['customer']);

            // Search filter
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('order_number', 'LIKE', "%{$search}%")
                      ->orWhereHas('customer', function ($q2) use ($search) {
                          $q2->where('name', 'LIKE', "%{$search}%")
                             ->orWhere('email', 'LIKE', "%{$search}%");
                      });
                });
            }

            // Status filter
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Customer filter
            if ($request->filled('customer_id')) {
                $query->where('customer_id', $request->customer_id);
            }

            // Date range filter
            if ($request->filled('period')) {
                $dates = $this->getDateRange($request->period);
                $query->whereBetween('created_at', $dates);
            }

            $orders = $query->latest()->paginate(15)->withQueryString();

            // Get statistics
            $stats = [
                'total' => Order::forTenant($tenantId)->count(),
                'pending' => Order::forTenant($tenantId)->where('status', 'pending')->count(),
                'completed' => Order::forTenant($tenantId)->where('status', 'completed')->count(),
                'total_revenue' => Order::forTenant($tenantId)->where('status', 'completed')->sum('total'),
            ];

            // Get customers for filter
            $customers = Customer::forTenant($tenantId)->get();

            return view('admin.orders.index', compact('orders', 'stats', 'customers'));
        } catch (\Exception $e) {
            Log::error('Error fetching orders: ' . $e->getMessage());
            return back()->with('error', 'Unable to fetch orders.');
        }
    }

    /**
     * Show the form for creating a new order.
     */
    public function create()
    {
        try {
            $tenantId = auth()->user()->tenant_id;
            $customers = Customer::forTenant($tenantId)->get();

            return view('admin.orders.form', compact('customers'));
        } catch (\Exception $e) {
            Log::error('Error loading create order form: ' . $e->getMessage());
            return back()->with('error', 'Unable to load create order form.');
        }
    }

    /**
     * Store a newly created order.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'customer_id' => ['required', 'exists:customers,id'],
                'items' => ['required', 'array', 'min:1'],
                'items.*.name' => ['required', 'string'],
                'items.*.quantity' => ['required', 'integer', 'min:1'],
                'items.*.price' => ['required', 'numeric', 'min:0'],
                'shipping_address' => ['nullable', 'string'],
                'status' => ['required', 'in:pending,processing,completed,shipped,delivered,cancelled,refunded'],
                'payment_method' => ['nullable', 'string'],
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

                // Calculate total
                $total = 0;
                $items = [];
                foreach ($request->items as $item) {
                    $itemTotal = $item['quantity'] * $item['price'];
                    $total += $itemTotal;
                    $items[] = [
                        'name' => $item['name'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'total' => $itemTotal,
                    ];
                }

                // Generate order number
                $orderNumber = $this->generateOrderNumber();

                $order = Order::create([
                    'tenant_id' => $tenantId,
                    'customer_id' => $request->customer_id,
                    'order_number' => $orderNumber,
                    'items' => $items,
                    'total' => $total,
                    'shipping_address' => $request->shipping_address,
                    'status' => $request->status,
                    'payment_method' => $request->payment_method,
                    'notes' => $request->notes,
                    'created_by' => auth()->id(),
                ]);

                // Log activity
                Activity::create([
                    'user_id' => auth()->id(),
                    'tenant_id' => $tenantId,
                    'subject_type' => Order::class,
                    'subject_id' => $order->id,
                    'action' => 'created_order',
                    'description' => "Created order #{$orderNumber}",
                    'properties' => [
                        'order_number' => $orderNumber,
                        'customer_id' => $request->customer_id,
                        'total' => $total,
                        'status' => $request->status,
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                DB::commit();

                return redirect()->route('admin.orders.show', $order)
                    ->with('success', "Order #{$orderNumber} created successfully.");
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error creating order: ' . $e->getMessage());
            return back()->with('error', 'Failed to create order.');
        }
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        try {
            $this->authorizeTenant($order);
            
            $order->load(['customer', 'items', 'activities']);

            return view('admin.orders.show', compact('order'));
        } catch (\Exception $e) {
            Log::error('Error showing order: ' . $e->getMessage());
            return back()->with('error', 'Unable to display order.');
        }
    }

    /**
     * Show the form for editing the specified order.
     */
    public function edit(Order $order)
    {
        try {
            $this->authorizeTenant($order);
            
            $tenantId = auth()->user()->tenant_id;
            $customers = Customer::forTenant($tenantId)->get();

            return view('admin.orders.form', compact('order', 'customers'));
        } catch (\Exception $e) {
            Log::error('Error loading edit order form: ' . $e->getMessage());
            return back()->with('error', 'Unable to load edit order form.');
        }
    }

    /**
     * Update the specified order.
     */
    public function update(Request $request, Order $order)
    {
        try {
            $this->authorizeTenant($order);

            $validator = Validator::make($request->all(), [
                'customer_id' => ['required', 'exists:customers,id'],
                'items' => ['required', 'array', 'min:1'],
                'items.*.name' => ['required', 'string'],
                'items.*.quantity' => ['required', 'integer', 'min:1'],
                'items.*.price' => ['required', 'numeric', 'min:0'],
                'shipping_address' => ['nullable', 'string'],
                'status' => ['required', 'in:pending,processing,completed,shipped,delivered,cancelled,refunded'],
                'payment_method' => ['nullable', 'string'],
                'notes' => ['nullable', 'string'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            DB::beginTransaction();

            try {
                // Calculate total
                $total = 0;
                $items = [];
                foreach ($request->items as $item) {
                    $itemTotal = $item['quantity'] * $item['price'];
                    $total += $itemTotal;
                    $items[] = [
                        'name' => $item['name'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'total' => $itemTotal,
                    ];
                }

                $oldStatus = $order->status;

                $order->update([
                    'customer_id' => $request->customer_id,
                    'items' => $items,
                    'total' => $total,
                    'shipping_address' => $request->shipping_address,
                    'status' => $request->status,
                    'payment_method' => $request->payment_method,
                    'notes' => $request->notes,
                    'updated_by' => auth()->id(),
                ]);

                // Log activity
                $changes = [];
                if ($oldStatus !== $request->status) {
                    $changes[] = "Status changed from {$oldStatus} to {$request->status}";
                }

                if (!empty($changes)) {
                    Activity::create([
                        'user_id' => auth()->id(),
                        'subject_type' => Order::class,
                        'subject_id' => $order->id,
                        'action' => 'updated_order',
                        'description' => "Updated order #{$order->order_number}",
                        'properties' => [
                            'order_number' => $order->order_number,
                            'changes' => $changes,
                            'new_status' => $request->status,
                        ],
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ]);
                }

                DB::commit();

                return redirect()->route('admin.orders.show', $order)
                    ->with('success', "Order #{$order->order_number} updated successfully.");
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error updating order: ' . $e->getMessage());
            return back()->with('error', 'Failed to update order.');
        }
    }

    /**
     * Process an order.
     */
    public function process(Request $request, Order $order)
    {
        try {
            $this->authorizeTenant($order);

            if ($order->status !== 'pending') {
                return back()->with('error', 'Only pending orders can be processed.');
            }

            DB::beginTransaction();

            try {
                $order->update([
                    'status' => 'processing',
                    'processed_at' => now(),
                    'updated_by' => auth()->id(),
                ]);

                Activity::create([
                    'user_id' => auth()->id(),
                    'subject_type' => Order::class,
                    'subject_id' => $order->id,
                    'action' => 'processed_order',
                    'description' => "Processed order #{$order->order_number}",
                    'properties' => [
                        'order_number' => $order->order_number,
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                DB::commit();

                return redirect()->route('admin.orders.show', $order)
                    ->with('success', "Order #{$order->order_number} processed successfully.");
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error processing order: ' . $e->getMessage());
            return back()->with('error', 'Failed to process order.');
        }
    }

    /**
     * Cancel an order.
     */
    public function cancel(Request $request, Order $order)
    {
        try {
            $this->authorizeTenant($order);

            if (!in_array($order->status, ['pending', 'processing'])) {
                return back()->with('error', 'Only pending or processing orders can be cancelled.');
            }

            DB::beginTransaction();

            try {
                $order->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                    'updated_by' => auth()->id(),
                ]);

                Activity::create([
                    'user_id' => auth()->id(),
                    'subject_type' => Order::class,
                    'subject_id' => $order->id,
                    'action' => 'cancelled_order',
                    'description' => "Cancelled order #{$order->order_number}",
                    'properties' => [
                        'order_number' => $order->order_number,
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                DB::commit();

                return redirect()->route('admin.orders.show', $order)
                    ->with('success', "Order #{$order->order_number} cancelled successfully.");
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error cancelling order: ' . $e->getMessage());
            return back()->with('error', 'Failed to cancel order.');
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

    /**
     * Generate order number.
     */
    private function generateOrderNumber()
    {
        $prefix = 'ORD';
        $year = date('Y');
        $month = date('m');
        
        $lastOrder = Order::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastOrder ? intval(substr($lastOrder->order_number, -4)) + 1 : 1;

        return $prefix . '-' . $year . $month . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get date range.
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