{{-- resources/views/admin/customers/show.blade.php --}}
@extends('admin.layouts.admin')

@section('title', $customer->name)

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <a href="{{ route('admin.customers.index') }}" class="text-gray-500 hover:text-gray-700">Customers</a>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-700">{{ $customer->name }}</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- ===== HEADER ===== -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center space-x-4">
            <x-admin.avatar :src="$customer->avatar" :name="$customer->name" size="lg" />
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $customer->name }}</h1>
                <div class="flex items-center space-x-3 mt-1">
                    <span class="text-sm text-gray-500">{{ $customer->email }}</span>
                    <span class="text-gray-300">|</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        {{ $customer->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $customer->is_active ? 'Active' : 'Inactive' }}
                    </span>
                    <span class="text-xs text-gray-400">ID: #{{ $customer->id }}</span>
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.customers.edit', $customer) }}" 
               class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-pen mr-2"></i> Edit
            </a>
            <a href="{{ route('admin.customers.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
        </div>
    </div>

    <!-- ===== STATS ===== -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Total Orders</p>
            <p class="text-xl font-bold text-gray-900">{{ $stats['total_orders'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Total Spent</p>
            <p class="text-xl font-bold text-green-600">${{ number_format($stats['total_spent'] ?? 0, 2) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Average Order</p>
            <p class="text-xl font-bold text-blue-600">${{ number_format($stats['avg_order'] ?? 0, 2) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Member Since</p>
            <p class="text-xl font-bold text-purple-600">{{ $customer->created_at->format('M d, Y') }}</p>
        </div>
    </div>

    <!-- ===== CUSTOMER DETAILS ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Orders -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-sm font-medium text-gray-900">Recent Orders</h3>
                    <a href="{{ route('admin.customers.orders', $customer) }}" class="text-xs text-primary-600 hover:text-primary-700">
                        View All
                    </a>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($customer->orders()->latest()->limit(5)->get() as $order)
                        <div class="px-6 py-3 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="text-sm font-medium text-gray-900 hover:text-primary-600">
                                        #{{ $order->order_number }}
                                    </a>
                                    <p class="text-xs text-gray-500">{{ $order->created_at->format('M d, Y') }}</p>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $order->status == 'completed' ? 'bg-green-100 text-green-800' : 
                                           ($order->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                    <span class="text-sm font-medium text-gray-900">${{ number_format($order->total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-4 text-center text-gray-500">No orders yet</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Customer Info -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-sm font-medium text-gray-900 mb-4">Customer Information</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-xs text-gray-500">Full Name</dt>
                        <dd class="text-sm text-gray-900">{{ $customer->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Email</dt>
                        <dd class="text-sm text-gray-900">{{ $customer->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Phone</dt>
                        <dd class="text-sm text-gray-900">{{ $customer->phone ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Status</dt>
                        <dd class="text-sm text-gray-900">{{ $customer->is_active ? 'Active' : 'Inactive' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Joined</dt>
                        <dd class="text-sm text-gray-900">{{ $customer->created_at->format('M d, Y H:i:s') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Last Updated</dt>
                        <dd class="text-sm text-gray-900">{{ $customer->updated_at->format('M d, Y H:i:s') }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Address -->
            @if($customer->address)
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-sm font-medium text-gray-900 mb-4">Address</h3>
                    <p class="text-sm text-gray-600">{{ $customer->address }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection