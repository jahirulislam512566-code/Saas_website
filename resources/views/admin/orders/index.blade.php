{{-- resources/views/admin/orders/index.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Orders Management')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">Orders</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- ===== HEADER ===== -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Orders</h1>
            <p class="text-sm text-gray-500 mt-1">Manage all customer orders</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.orders.export') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-download mr-2"></i> Export
            </a>
            <a href="{{ route('admin.orders.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-plus mr-2"></i> Create Order
            </a>
        </div>
    </div>

    <!-- ===== STATS CARDS ===== -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Orders</p>
                    <p class="text-xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center">
                    <i class="fas fa-shopping-cart"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Pending</p>
                    <p class="text-xl font-bold text-yellow-600">{{ $stats['pending'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Completed</p>
                    <p class="text-xl font-bold text-green-600">{{ $stats['completed'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Revenue</p>
                    <p class="text-xl font-bold text-purple-600">${{ number_format($stats['total_revenue'] ?? 0, 2) }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== FILTERS ===== -->
    <div class="bg-white rounded-xl shadow-sm p-4">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" placeholder="Order # or customer..." value="{{ request('search') }}" 
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Customer</label>
                <select name="customer_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">All Customers</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                            {{ $customer->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                <select name="period" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">All Time</option>
                    <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Today</option>
                    <option value="week" {{ request('period') == 'week' ? 'selected' : '' }}>This Week</option>
                    <option value="month" {{ request('period') == 'month' ? 'selected' : '' }}>This Month</option>
                    <option value="quarter" {{ request('period') == 'quarter' ? 'selected' : '' }}>This Quarter</option>
                    <option value="year" {{ request('period') == 'year' ? 'selected' : '' }}>This Year</option>
                </select>
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    <i class="fas fa-search mr-2"></i> Filter
                </button>
                <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-times mr-2"></i> Clear
                </a>
            </div>
        </form>
    </div>

    <!-- ===== ORDERS TABLE ===== -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Items</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                #{{ $order->order_number }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <x-admin.avatar :src="$order->customer->avatar ?? null" :name="$order->customer->name ?? 'N/A'" size="sm" />
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">{{ $order->customer->name ?? 'N/A' }}</p>
                                        <p class="text-xs text-gray-500">{{ $order->customer->email ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $order->items_count ?? 0 }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                ${{ number_format($order->total, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'processing' => 'bg-blue-100 text-blue-800',
                                        'completed' => 'bg-green-100 text-green-800',
                                        'shipped' => 'bg-purple-100 text-purple-800',
                                        'delivered' => 'bg-indigo-100 text-indigo-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                        'refunded' => 'bg-gray-100 text-gray-800',
                                    ];
                                    $color = $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <div>{{ $order->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-400">{{ $order->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-1">
                                    <a href="{{ route('admin.orders.show', $order) }}" 
                                       class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" 
                                       title="View">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    <a href="{{ route('admin.orders.edit', $order) }}" 
                                       class="p-2 text-gray-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" 
                                       title="Edit">
                                        <i class="fas fa-pen text-sm"></i>
                                    </a>
                                    @if($order->status === 'pending')
                                        <button onclick="processOrder('{{ $order->id }}')" 
                                                class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors" 
                                                title="Process">
                                            <i class="fas fa-check text-sm"></i>
                                        </button>
                                    @endif
                                    @if(in_array($order->status, ['pending', 'processing']))
                                        <button onclick="cancelOrder('{{ $order->id }}')" 
                                                class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" 
                                                title="Cancel">
                                            <i class="fas fa-times text-sm"></i>
                                        </button>
                                    @endif
                                    @if($order->status === 'completed')
                                        <a href="{{ route('admin.orders.invoice', $order) }}" 
                                           class="p-2 text-gray-400 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors" 
                                           title="Invoice">
                                            <i class="fas fa-file-invoice text-sm"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-shopping-cart text-4xl text-gray-300 mb-3 block"></i>
                                <p class="text-lg font-medium">No orders found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- ===== PAGINATION ===== -->
        <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
            @if($orders instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="text-sm text-gray-500">
                    Showing {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }} results
                </div>
                <div>
                    {{ $orders->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-sm text-gray-500">
                    Showing {{ $orders->count() }} results
                </div>
            @endif
        </div>
    </div>
</div>

<!-- ===== FORMS ===== -->
<form id="process-form" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
</form>

<form id="cancel-order-form" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
</form>

@push('scripts')
<script>
    function processOrder(orderId) {
        if (confirm('Process this order?')) {
            const form = document.getElementById('process-form');
            form.action = `/admin/orders/${orderId}/process`;
            form.submit();
        }
    }

    function cancelOrder(orderId) {
        if (confirm('Cancel this order?')) {
            const form = document.getElementById('cancel-order-form');
            form.action = `/admin/orders/${orderId}/cancel`;
            form.submit();
        }
    }
</script>
@endpush

@push('styles')
<style>
    .card-hover {
        transition: all 0.2s ease;
    }
    .card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    }
</style>
@endpush
@endsection