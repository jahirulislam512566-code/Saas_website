<!-- admin/subscriptions/show.blade.php -->
@extends('layout.app')

@section('title', 'Subscription Details')
@section('page-title', 'Subscription Details')
@section('page-subtitle', 'Complete subscription information and history')

@section('content')
<div x-data="subscriptionDetails()" x-init="init()">
    <!-- Subscription Header -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden mb-6">
        <div class="p-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Subscription #{{ $subscription->id }}</h3>
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium
                                     {{ $subscription->status === 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 
                                        ($subscription->status === 'trial' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' :
                                        ($subscription->status === 'pending' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' :
                                        ($subscription->status === 'paused' ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400' :
                                        ($subscription->status === 'cancelled' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' :
                                        'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400')))) }}">
                            <i class="fas fa-{{ $subscription->status === 'active' ? 'check-circle' : 
                                        ($subscription->status === 'trial' ? 'flask' :
                                        ($subscription->status === 'pending' ? 'clock' :
                                        ($subscription->status === 'paused' ? 'pause-circle' :
                                        ($subscription->status === 'cancelled' ? 'times-circle' : 'hourglass-end')))) }}"></i>
                            {{ ucfirst($subscription->status) }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Started on {{ $subscription->started_at->format('M d, Y h:i A') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    @if($subscription->status === 'active' || $subscription->status === 'trial')
                        <button @click="renewSubscription()" 
                                class="px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg shadow-sm transition-all">
                            <i class="fas fa-sync mr-1.5"></i>Renew
                        </button>
                        <button @click="cancelSubscription()" 
                                class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg shadow-sm transition-all">
                            <i class="fas fa-times mr-1.5"></i>Cancel
                        </button>
                    @endif
                    <a href="{{ route('admin.subscriptions.index') }}" 
                       class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors">
                        <i class="fas fa-arrow-left mr-1.5"></i>Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Customer Info -->
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-6">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Customer Information</h4>
                    <div class="flex items-start gap-4">
                        <img src="{{ $subscription->customer->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($subscription->customer->name ?? 'Unknown') }}" 
                             alt="" class="w-12 h-12 rounded-full">
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $subscription->customer->name ?? 'Unknown' }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $subscription->customer->email ?? 'No email' }}</p>
                            @if($subscription->customer)
                                <a href="{{ route('admin.customers.show', $subscription->customer) }}" 
                                   class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                                    View Customer Profile <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Plan Details -->
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-6">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Plan Details</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Plan Name</label>
                            <p class="mt-1 font-medium text-gray-900 dark:text-white">{{ $subscription->plan->name ?? 'Unknown' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Price</label>
                            <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($subscription->price, 2) }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Billing Interval</label>
                            <p class="mt-1 text-gray-900 dark:text-white">{{ ucfirst($subscription->plan->interval ?? 'N/A') }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Trial Period</label>
                            <p class="mt-1 text-gray-900 dark:text-white">{{ $subscription->plan->trial_days ?? 0 }} days</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment History -->
            @if($payments->isNotEmpty())
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-6">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Payment History</h4>
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
                                    @foreach($payments as $payment)
                                        <tr>
                                            <td class="px-3 py-2">
                                                <a href="{{ route('admin.payments.show', $payment) }}" 
                                                   class="font-mono text-indigo-600 dark:text-indigo-400 hover:underline">
                                                    {{ $payment->transaction_id }}
                                                </a>
                                            </td>
                                            <td class="px-3 py-2 text-right font-medium text-gray-900 dark:text-white">
                                                ${{ number_format($payment->amount, 2) }}
                                            </td>
                                            <td class="px-3 py-2">
                                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium
                                                             {{ $payment->status === 'successful' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 
                                                                ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' :
                                                                'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400') }}">
                                                    {{ ucfirst($payment->status) }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-2 text-gray-500 dark:text-gray-400 text-xs">
                                                {{ $payment->created_at->format('M d, Y') }}
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
            <!-- Subscription Timeline -->
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-6">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Timeline</h4>
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="w-2 h-2 mt-2 rounded-full bg-green-500 flex-shrink-0"></div>
                            <div>
                                <p class="text-sm text-gray-900 dark:text-white">Subscription {{ $subscription->status }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $subscription->started_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        @if($subscription->cancelled_at)
                            <div class="flex items-start gap-3">
                                <div class="w-2 h-2 mt-2 rounded-full bg-red-500 flex-shrink-0"></div>
                                <div>
                                    <p class="text-sm text-gray-900 dark:text-white">Subscription Cancelled</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $subscription->cancelled_at->format('M d, Y h:i A') }}</p>
                                </div>
                            </div>
                        @endif
                        @foreach($subscription->history as $event)
                            <div class="flex items-start gap-3">
                                <div class="w-2 h-2 mt-2 rounded-full bg-blue-500 flex-shrink-0"></div>
                                <div>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $event->title }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $event->created_at->format('M d, Y h:i A') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-6">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h4>
                    <div class="space-y-2">
                        <a href="{{ route('admin.subscriptions.history', $subscription) }}" 
                           class="w-full flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors">
                            <i class="fas fa-history"></i> View History
                        </a>
                        <a href="{{ route('admin.invoices.create', ['subscription' => $subscription]) }}" 
                           class="w-full flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors">
                            <i class="fas fa-file-invoice"></i> Generate Invoice
                        </a>
                        <button @click="editSubscription()" 
                                class="w-full flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition-all">
                            <i class="fas fa-edit"></i> Edit Subscription
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function subscriptionDetails() {
    return {
        subscriptionId: {{ $subscription->id }},
        
        renewSubscription() {
            if (confirm('Renew this subscription?')) {
                fetch(`{{ route('admin.subscriptions.renew', $subscription) }}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                })
                .catch(error => console.error('Error renewing subscription:', error));
            }
        },
        
        cancelSubscription() {
            if (confirm('Cancel this subscription?')) {
                fetch(`{{ route('admin.subscriptions.cancel', $subscription) }}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                })
                .catch(error => console.error('Error cancelling subscription:', error));
            }
        },
        
        editSubscription() {
            window.location.href = `{{ route('admin.subscriptions.edit', $subscription) }}`;
        }
    }
}
</script>
@endpush