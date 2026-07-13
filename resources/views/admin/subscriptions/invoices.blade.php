{{-- resources/views/admin/subscriptions/invoices.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Invoices')

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
            <span class="text-gray-500">Invoices</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- ===== HEADER ===== -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Invoices</h1>
            <p class="text-sm text-gray-500 mt-1">Manage all invoices and billing history</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.subscriptions.invoices.export') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-download mr-2"></i> Export
            </a>
            <button onclick="generateInvoice()" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-plus mr-2"></i> Generate Invoice
            </button>
        </div>
    </div>

    <!-- ===== STATS ===== -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Total Invoices</p>
            <p class="text-xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Paid</p>
            <p class="text-xl font-bold text-green-600">{{ $stats['paid'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Unpaid</p>
            <p class="text-xl font-bold text-red-600">{{ $stats['unpaid'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Total Amount</p>
            <p class="text-xl font-bold text-purple-600">${{ number_format($stats['total_amount'] ?? 0, 2) }}</p>
        </div>
    </div>

    <!-- ===== FILTERS ===== -->
    <div class="bg-white rounded-xl shadow-sm p-4">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" placeholder="Invoice # or user..." value="{{ request('search') }}" 
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">All Status</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
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
                <a href="{{ route('admin.subscriptions.invoices') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-times mr-2"></i> Clear
                </a>
            </div>
        </form>
    </div>

    <!-- ===== INVOICES TABLE ===== -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Plan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($invoices as $invoice)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                #{{ $invoice->invoice_number }}
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $invoice->user->name ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500">{{ $invoice->user->email ?? 'N/A' }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $invoice->plan_name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                ${{ number_format($invoice->amount, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'paid' => 'bg-green-100 text-green-800',
                                        'unpaid' => 'bg-red-100 text-red-800',
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'overdue' => 'bg-orange-100 text-orange-800',
                                    ];
                                    $color = $statusColors[$invoice->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <div>{{ $invoice->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-400">{{ $invoice->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-1">
                                    <a href="{{ route('admin.subscriptions.invoices.view', $invoice) }}" 
                                       class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" 
                                       title="View">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    <a href="{{ route('admin.subscriptions.invoices.download', $invoice) }}" 
                                       class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors" 
                                       title="Download">
                                        <i class="fas fa-download text-sm"></i>
                                    </a>
                                    @if($invoice->status !== 'paid')
                                        <button onclick="markAsPaid('{{ $invoice->id }}')" 
                                                class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors" 
                                                title="Mark as Paid">
                                            <i class="fas fa-check text-sm"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-file-invoice text-4xl text-gray-300 mb-3 block"></i>
                                <p class="text-lg font-medium">No invoices found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $invoices->links() }}
        </div>
    </div>
</div>

<form id="mark-paid-form" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
</form>

@push('scripts')
<script>
    function markAsPaid(invoiceId) {
        if (confirm('Mark this invoice as paid?')) {
            const form = document.getElementById('mark-paid-form');
            form.action = `/admin/subscriptions/invoices/${invoiceId}/mark-paid`;
            form.submit();
        }
    }

    function generateInvoice() {
        window.location.href = '{{ route("admin.subscriptions.invoices.create") }}';
    }
</script>
@endpush
@endsection