{{-- resources/views/admin/billing/payments.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Payment Management')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <a href="{{ route('admin.billing.index') }}" class="text-gray-500 hover:text-gray-700">Billing</a>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">Payments</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- ===== HEADER ===== -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Payment Management</h1>
            <p class="text-sm text-gray-500 mt-1">View and manage all payments</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.billing.payments.export') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-download mr-2"></i> Export
            </a>
            <button onclick="showAddPayment()" 
                    class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-plus mr-2"></i> Add Payment
            </button>
        </div>
    </div>

    <!-- ===== STATS CARDS ===== -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Payments</p>
                    <p class="text-xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center">
                    <i class="fas fa-credit-card"></i>
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
                    <p class="text-sm text-gray-500">Total Amount</p>
                    <p class="text-xl font-bold text-purple-600">${{ number_format($stats['total_amount'] ?? 0, 2) }}</p>
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
                <input type="text" name="search" placeholder="Payment ID or user..." value="{{ request('search') }}" 
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">All Status</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                    <option value="disputed" {{ request('status') == 'disputed' ? 'selected' : '' }}>Disputed</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                <select name="method" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">All Methods</option>
                    <option value="credit_card" {{ request('method') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                    <option value="paypal" {{ request('method') == 'paypal' ? 'selected' : '' }}>PayPal</option>
                    <option value="stripe" {{ request('method') == 'stripe' ? 'selected' : '' }}>Stripe</option>
                    <option value="bank_transfer" {{ request('method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="crypto" {{ request('method') == 'crypto' ? 'selected' : '' }}>Cryptocurrency</option>
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
                <a href="{{ route('admin.billing.payments') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-times mr-2"></i> Clear
                </a>
            </div>
        </form>
    </div>

    <!-- ===== PAYMENTS TABLE ===== -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                #{{ $payment->payment_id }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <x-admin.avatar :src="$payment->user->avatar ?? null" :name="$payment->user->name ?? 'N/A'" size="sm" />
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">{{ $payment->user->name ?? 'N/A' }}</p>
                                        <p class="text-xs text-gray-500">{{ $payment->user->email ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                ${{ number_format($payment->amount, 2) }}
                                <span class="text-xs text-gray-500 block">{{ $payment->currency ?? 'USD' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <i class="fas {{ $payment->method_icon }} mr-1"></i>
                                    {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'completed' => 'bg-green-100 text-green-800',
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'failed' => 'bg-red-100 text-red-800',
                                        'refunded' => 'bg-blue-100 text-blue-800',
                                        'disputed' => 'bg-orange-100 text-orange-800',
                                    ];
                                    $color = $statusColors[$payment->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <div>{{ $payment->payment_date->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-400">{{ $payment->payment_date->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-1">
                                    <a href="{{ route('admin.billing.payments.show', $payment) }}" 
                                       class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" 
                                       title="View">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    @if($payment->status === 'completed')
                                        <button onclick="refundPayment('{{ $payment->id }}')" 
                                                class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors" 
                                                title="Refund">
                                            <i class="fas fa-undo text-sm"></i>
                                        </button>
                                        <a href="{{ route('admin.billing.payments.invoice', $payment) }}" 
                                           class="p-2 text-gray-400 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors" 
                                           title="Invoice">
                                            <i class="fas fa-file-invoice text-sm"></i>
                                        </a>
                                    @endif
                                    @if($payment->status === 'pending')
                                        <button onclick="voidPayment('{{ $payment->id }}')" 
                                                class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" 
                                                title="Void">
                                            <i class="fas fa-ban text-sm"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-credit-card text-4xl text-gray-300 mb-3 block"></i>
                                <p class="text-lg font-medium">No payments found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $payments->links() }}
        </div>
    </div>
</div>

<!-- Add Payment Modal -->
<div x-data="{ show: false }" 
     x-show="show"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto" 
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" @click="show = false">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form action="{{ route('admin.billing.payments.store') }}" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Add Payment</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        User <span class="text-red-500">*</span>
                                    </label>
                                    <select name="user_id" required
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <option value="">Select User</option>
                                        @foreach($users ?? [] as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Amount <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="amount" required
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                           placeholder="0.00" step="0.01" min="0">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Payment Method <span class="text-red-500">*</span>
                                    </label>
                                    <select name="payment_method" required
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <option value="credit_card">Credit Card</option>
                                        <option value="paypal">PayPal</option>
                                        <option value="stripe">Stripe</option>
                                        <option value="bank_transfer">Bank Transfer</option>
                                        <option value="crypto">Cryptocurrency</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Status <span class="text-red-500">*</span>
                                    </label>
                                    <select name="status" required
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <option value="completed">Completed</option>
                                        <option value="pending">Pending</option>
                                        <option value="failed">Failed</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Add Payment
                    </button>
                    <button type="button" @click="show = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ===== FORMS ===== -->
<form id="refund-form" method="POST" style="display: none;">
    @csrf
    @method('POST')
</form>

<form id="void-form" method="POST" style="display: none;">
    @csrf
    @method('POST')
</form>

@push('scripts')
<script>
    function showAddPayment() {
        document.querySelector('[x-data]').__x.$data.show = true;
    }

    function refundPayment(paymentId) {
        if (confirm('Refund this payment?')) {
            const form = document.getElementById('refund-form');
            form.action = `/admin/billing/payments/${paymentId}/refund`;
            form.submit();
        }
    }

    function voidPayment(paymentId) {
        if (confirm('Void this payment?')) {
            const form = document.getElementById('void-form');
            form.action = `/admin/billing/payments/${paymentId}/void`;
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