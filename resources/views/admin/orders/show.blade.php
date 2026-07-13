{{-- resources/views/admin/orders/show.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Order #' . $order->order_number)

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <a href="{{ route('admin.orders.index') }}" class="text-gray-500 hover:text-gray-700">Orders</a>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-700">#{{ $order->order_number }}</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- ===== HEADER ===== -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Order #{{ $order->order_number }}</h1>
            <div class="flex items-center space-x-3 mt-1">
                <span class="text-sm text-gray-500">Status:</span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                    {{ $order->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                       ($order->status == 'processing' ? 'bg-blue-100 text-blue-800' : 
                       ($order->status == 'completed' ? 'bg-green-100 text-green-800' : 
                       ($order->status == 'shipped' ? 'bg-purple-100 text-purple-800' : 
                       ($order->status == 'delivered' ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-800')))) }}">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            @if($order->status === 'pending')
                <button onclick="processOrder('{{ $order->id }}')" 
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-check mr-2"></i> Process Order
                </button>
            @endif
            @if(in_array($order->status, ['pending', 'processing']))
                <button onclick="cancelOrder('{{ $order->id }}')" 
                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-times mr-2"></i> Cancel Order
                </button>
            @endif
            <a href="{{ route('admin.orders.edit', $order) }}" 
               class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-pen mr-2"></i> Edit
            </a>
            <a href="{{ route('admin.orders.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
        </div>
    </div>

    <!-- ===== ORDER OVERVIEW ===== -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Order Total</p>
            <p class="text-xl font-bold text-gray-900">${{ number_format($order->total, 2) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Items</p>
            <p class="text-xl font-bold text-blue-600">{{ $order->items->count() }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Customer</p>
            <p class="text-xl font-bold text-gray-900">{{ $order->customer->name ?? 'N/A' }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Order Date</p>
            <p class="text-xl font-bold text-gray-900">{{ $order->created_at->format('M d, Y') }}</p>
        </div>
    </div>

    <!-- ===== ORDER DETAILS ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Items -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-sm font-medium text-gray-900">Order Items</h3>
            </div>
            <div class="divide-y divide-gray-200">
                @foreach($order->items as $item)
                    <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $item->name }}</p>
                            <p class="text-xs text-gray-500">Qty: {{ $item->quantity }}</p>
                        </div>
                        <p class="text-sm font-medium text-gray-900">${{ number_format($item->price * $item->quantity, 2) }}</p>
                    </div>
                @endforeach
                
                <div class="px-6 py-4 bg-gray-50 flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-900">Total</p>
                    <p class="text-lg font-bold text-gray-900">${{ number_format($order->total, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Order Information -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-sm font-medium text-gray-900 mb-4">Order Information</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-xs text-gray-500">Order Number</dt>
                        <dd class="text-sm text-gray-900">#{{ $order->order_number }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Status</dt>
                        <dd class="text-sm text-gray-900">{{ ucfirst($order->status) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Payment Method</dt>
                        <dd class="text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Customer</dt>
                        <dd class="text-sm text-gray-900">{{ $order->customer->name ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Email</dt>
                        <dd class="text-sm text-gray-900">{{ $order->customer->email ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Created</dt>
                        <dd class="text-sm text-gray-900">{{ $order->created_at->format('M d, Y H:i:s') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Updated</dt>
                        <dd class="text-sm text-gray-900">{{ $order->updated_at->format('M d, Y H:i:s') }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Shipping Address -->
            @if($order->shipping_address)
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-sm font-medium text-gray-900 mb-4">Shipping Address</h3>
                    <p class="text-sm text-gray-600">{{ $order->shipping_address }}</p>
                </div>
            @endif

            <!-- Notes -->
            @if($order->notes)
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-sm font-medium text-gray-900 mb-4">Notes</h3>
                    <p class="text-sm text-gray-600">{{ $order->notes }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- ===== ORDER TIMELINE ===== -->
    @if($order->activities && $order->activities->count() > 0)
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-sm font-medium text-gray-900 mb-4">Order Timeline</h3>
            <div class="space-y-3">
                @foreach($order->activities as $activity)
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-circle text-gray-400 text-xs"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-900">{{ $activity->description }}</p>
                            <p class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

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
@endsection