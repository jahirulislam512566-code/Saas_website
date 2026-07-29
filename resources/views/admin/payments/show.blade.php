<!-- admin/payments/show.blade.php -->
@extends('layout.app')

@section('title', 'Payment Details')
@section('page-title', 'Payment Details')
@section('page-subtitle', 'View complete payment information')

@section('content')
<div x-data="paymentDetails()" x-init="init()">
    <!-- Payment Header -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden mb-6">
        <div class="p-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Payment #{{ $payment->transaction_id }}</h3>
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium
                                     {{ $payment->status === 'successful' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 
                                        ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' :
                                        ($payment->status === 'failed' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' :
                                        'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400')) }}">
                            <i class="fas fa-{{ $payment->status === 'successful' ? 'check-circle' : 
                                        ($payment->status === 'pending' ? 'clock' : 
                                        ($payment->status === 'failed' ? 'times-circle' : 'undo-alt')) }}"></i>
                            {{ ucfirst($payment->status) }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Created on {{ $payment->created_at->format('M d, Y h:i A') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <button onclick="window.print()" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors">
                        <i class="fas fa-print mr-1.5"></i>Print
                    </button>
                    @if($payment->status === 'successful')
                        <button @click="refundPayment()" 
                                class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg shadow-sm transition-all">
                            <i class="fas fa-undo-alt mr-1.5"></i>Refund
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Payment Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Amount & Details -->
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-6">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Payment Summary</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</label>
                            <p class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">${{ number_format($payment->amount, 2) }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Gateway</label>
                            <p class="mt-1">
                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-medium
                                             {{ $payment->gateway === 'stripe' ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400' : 
                                                ($payment->gateway === 'paypal' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' :
                                                ($payment->gateway === 'sslcommerz' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' :
                                                'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400')) }}">
                                    <i class="{{ $payment->gateway === 'stripe' ? 'fab fa-stripe' : 
                                              ($payment->gateway === 'paypal' ? 'fab fa-paypal' : 
                                              ($payment->gateway === 'sslcommerz' ? 'fas fa-shield-alt' : 'fas fa-hand')) }}"></i>
                                    {{ ucfirst($payment->gateway) }}
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="mt-4 grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Payment Method</label>
                            <p class="mt-1 text-gray-900 dark:text-white">{{ $payment->method ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Gateway Transaction ID</label>
                            <p class="mt-1 font-mono text-sm text-gray-600 dark:text-gray-400">{{ $payment->gateway_transaction_id ?? 'N/A' }}</p>
                        </div>
                    </div>
                    @if($payment->description)
                        <div class="mt-4">
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Description</label>
                            <p class="mt-1 text-gray-700 dark:text-gray-300">{{ $payment->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Customer Info -->
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-6">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Customer Information</h4>
                    <div class="flex items-start gap-4">
                        <img src="{{ $payment->customer->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($payment->customer->name ?? 'Unknown') }}" 
                             alt="" class="w-12 h-12 rounded-full">
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $payment->customer->name ?? 'Unknown' }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $payment->customer->email ?? 'No email' }}</p>
                            @if($payment->customer)
                                <a href="{{ route('admin.customers.show', $payment->customer) }}" 
                                   class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                                    View Customer Profile <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Transactions -->
            @if($relatedPayments->isNotEmpty())
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-6">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Related Transactions</h4>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-gray-200 dark:border-gray-800">
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Transaction</th>
                                        <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                    @foreach($relatedPayments as $related)
                                        <tr>
                                            <td class="px-3 py-2">
                                                <a href="{{ route('admin.payments.show', $related) }}" 
                                                   class="font-mono text-indigo-600 dark:text-indigo-400 hover:underline">
                                                    {{ $related->transaction_id }}
                                                </a>
                                            </td>
                                            <td class="px-3 py-2 text-right font-medium text-gray-900 dark:text-white">
                                                ${{ number_format($related->amount, 2) }}
                                            </td>
                                            <td class="px-3 py-2">
                                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium
                                                             {{ $related->status === 'successful' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 
                                                                ($related->status === 'pending' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' :
                                                                ($related->status === 'failed' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' :
                                                                'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400')) }}">
                                                    {{ ucfirst($related->status) }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-2 text-gray-500 dark:text-gray-400 text-xs">
                                                {{ $related->created_at->format('M d, Y') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Refund Information -->
            @if($payment->refunds->isNotEmpty())
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-6">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Refund Information</h4>
                        @foreach($payment->refunds as $refund)
                            <div class="flex items-center justify-between p-3 bg-red-50 dark:bg-red-900/10 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Refund #{{ $refund->id }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $refund->created_at->format('M d, Y h:i A') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-red-600 dark:text-red-400">-${{ number_format($refund->amount, 2) }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst($refund->status) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Payment Timeline -->
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-6">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Timeline</h4>
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="w-2 h-2 mt-2 rounded-full bg-green-500 flex-shrink-0"></div>
                            <div>
                                <p class="text-sm text-gray-900 dark:text-white">Payment {{ $payment->status }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $payment->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        @if($payment->refunds->isNotEmpty())
                            @foreach($payment->refunds as $refund)
                                <div class="flex items-start gap-3">
                                    <div class="w-2 h-2 mt-2 rounded-full bg-red-500 flex-shrink-0"></div>
                                    <div>
                                        <p class="text-sm text-gray-900 dark:text-white">Refund processed</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $refund->created_at->format('M d, Y h:i A') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-6">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Actions</h4>
                    <div class="space-y-2">
                        @if($payment->status === 'successful')
                            <button @click="refundPayment()" 
                                    class="w-full flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors">
                                <i class="fas fa-undo-alt"></i> Refund Payment
                            </button>
                        @endif
                        <button onclick="window.print()" 
                                class="w-full flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors">
                            <i class="fas fa-print"></i> Print Receipt
                        </button>
                        <a href="{{ route('admin.payments.index') }}" 
                           class="w-full flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                            <i class="fas fa-arrow-left"></i> Back to Payments
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function paymentDetails() {
    return {
        paymentId: {{ $payment->id }},
        
        refundPayment() {
            const amount = prompt('Enter refund amount:', '{{ $payment->amount }}');
            if (amount && !isNaN(amount) && parseFloat(amount) > 0) {
                if (confirm(`Are you sure you want to refund $${parseFloat(amount).toFixed(2)}?`)) {
                    fetch(`{{ route('admin.payments.refund', $payment) }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ amount: parseFloat(amount) })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Refund failed: ' + data.error);
                        }
                    })
                    .catch(error => console.error('Error refunding payment:', error));
                }
            }
        }
    }
}
</script>
@endpush