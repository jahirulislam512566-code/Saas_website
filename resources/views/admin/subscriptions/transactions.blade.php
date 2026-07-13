{{-- resources/views/admin/subscriptions/transactions.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Transactions')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <a href="{{ route('admin.subscriptions.index') }}" class="text-gray-500 hover:text-gray-700">Subscriptions</a>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">Transactions</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- ===== HEADER ===== -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Transactions</h1>
            <p class="text-sm text-gray-500 mt-1">View all payment transactions</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.subscriptions.transactions.export') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-download mr-2"></i> Export
            </a>
            <a href="{{ route('admin.subscriptions.transactions.void', $transaction ?? null) }}" 
               class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                <i class="fas fa-ban mr-2"></i> Void Transaction
            </a>
        </div>
    </div>

    <!-- ===== STATS ===== -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Total Transactions</p>
            <p class="text-xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Successful</p>
            <p class="text-xl font-bold text-green-600">{{ $stats['successful'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Failed</p>
            <p class="text-xl font-bold text-red-600">{{ $stats['failed'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Total Volume</p>
            <p class="text-xl font-bold text-purple-600">${{ number_format($stats['total_volume'] ?? 0, 2) }}</p>
        </div>
    </div>

    <!-- ===== FILTERS ===== -->
    <div class="bg-white rounded-xl shadow-sm p-4">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" placeholder="Transaction ID or user..." value="{{ request('search') }}" 
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">All Status</option>
                    <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Success</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                    <option value="voided" {{ request('status') == 'voided' ? 'selected' : '' }}>Voided</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                <select name="method" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">All Methods</option>
                    <option value="credit_card" {{ request('method') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                    <option value="paypal" {{ request('method') == 'paypal' ? 'selected' : '' }}>PayPal</option>
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
                <a href="{{ route('admin.subscriptions.transactions') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-times mr-2"></i> Clear
                </a>
            </div>
        </form>
    </div>

    <!-- ===== TRANSACTIONS TABLE ===== -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Transaction ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($transactions as $transaction)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                #{{ $transaction->transaction_id }}
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $transaction->user->name ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500">{{ $transaction->user->email ?? 'N/A' }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                ${{ number_format($transaction->amount, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ ucfirst(str_replace('_', ' ', $transaction->payment_method ?? 'N/A')) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'success' => 'bg-green-100 text-green-800',
                                        'failed' => 'bg-red-100 text-red-800',
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'refunded' => 'bg-blue-100 text-blue-800',
                                        'voided' => 'bg-gray-100 text-gray-800',
                                    ];
                                    $color = $statusColors[$transaction->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <div>{{ $transaction->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-400">{{ $transaction->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-1">
                                    <a href="{{ route('admin.subscriptions.transactions.show', $transaction) }}" 
                                       class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" 
                                       title="View Details">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    @if($transaction->status === 'success')
                                        <button onclick="refundTransaction('{{ $transaction->id }}')" 
                                                class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors" 
                                                title="Refund">
                                            <i class="fas fa-undo text-sm"></i>
                                        </button>
                                    @endif
                                    @if($transaction->status === 'success' || $transaction->status === 'pending')
                                        <button onclick="voidTransaction('{{ $transaction->id }}')" 
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
                                <i class="fas fa-exchange-alt text-4xl text-gray-300 mb-3 block"></i>
                                <p class="text-lg font-medium">No transactions found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $transactions->links() }}
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
    function refundTransaction(transactionId) {
        if (confirm('Refund this transaction?')) {
            const form = document.getElementById('refund-form');
            form.action = `/admin/subscriptions/transactions/${transactionId}/refund`;
            form.submit();
        }
    }

    function voidTransaction(transactionId) {
        if (confirm('Void this transaction?')) {
            const form = document.getElementById('void-form');
            form.action = `/admin/subscriptions/transactions/${transactionId}/void`;
            form.submit();
        }
    }
</script>
@endpush
@endsection