{{-- resources/views/admin/billing/refunds.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Refunds')

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
            <span class="text-gray-500">Refunds</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- ===== HEADER ===== -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Refund Management</h1>
            <p class="text-sm text-gray-500 mt-1">Manage all refunds and disputes</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.billing.refunds.export') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-download mr-2"></i> Export
            </a>
            <button onclick="showProcessRefund()" 
                    class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-plus mr-2"></i> Process Refund
            </button>
        </div>
    </div>

    <!-- ===== STATS ===== -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Total Refunds</p>
            <p class="text-xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Completed</p>
            <p class="text-xl font-bold text-green-600">{{ $stats['completed'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Pending</p>
            <p class="text-xl font-bold text-yellow-600">{{ $stats['pending'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Total Amount</p>
            <p class="text-xl font-bold text-purple-600">${{ number_format($stats['total_amount'] ?? 0, 2) }}</p>
        </div>
    </div>

    <!-- ===== REFUNDS TABLE ===== -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Refund ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Original Payment</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reason</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($refunds as $refund)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                #{{ $refund->refund_id }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                #{{ $refund->payment_id }}
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $refund->user->name ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500">{{ $refund->user->email ?? 'N/A' }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                ${{ number_format($refund->amount, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'completed' => 'bg-green-100 text-green-800',
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'failed' => 'bg-red-100 text-red-800',
                                        'cancelled' => 'bg-gray-100 text-gray-800',
                                    ];
                                    $color = $statusColors[$refund->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                    {{ ucfirst($refund->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ Str::limit($refund->reason ?? 'N/A', 30) }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-1">
                                    <a href="{{ route('admin.billing.refunds.show', $refund) }}" 
                                       class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" 
                                       title="View">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    @if($refund->status === 'pending')
                                        <button onclick="processRefund('{{ $refund->id }}')" 
                                                class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors" 
                                                title="Process Refund">
                                            <i class="fas fa-check text-sm"></i>
                                        </button>
                                        <button onclick="cancelRefund('{{ $refund->id }}')" 
                                                class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" 
                                                title="Cancel Refund">
                                            <i class="fas fa-times text-sm"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-undo text-4xl text-gray-300 mb-3 block"></i>
                                <p class="text-lg font-medium">No refunds found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $refunds->links() }}
        </div>
    </div>
</div>

<!-- Process Refund Modal -->
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
            <form action="{{ route('admin.billing.refunds.store') }}" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Process Refund</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Original Payment <span class="text-red-500">*</span>
                                    </label>
                                    <select name="payment_id" required
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <option value="">Select Payment</option>
                                        @foreach($payments ?? [] as $payment)
                                            <option value="{{ $payment->id }}">#{{ $payment->payment_id }} - ${{ number_format($payment->amount, 2) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Refund Amount <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="amount" required
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                           placeholder="0.00" step="0.01" min="0">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Reason <span class="text-red-500">*</span>
                                    </label>
                                    <select name="reason" required
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <option value="">Select Reason</option>
                                        <option value="customer_request">Customer Request</option>
                                        <option value="duplicate_payment">Duplicate Payment</option>
                                        <option value="fraudulent">Fraudulent</option>
                                        <option value="product_issue">Product Issue</option>
                                        <option value="service_not_provided">Service Not Provided</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Notes
                                    </label>
                                    <textarea name="notes" rows="3"
                                              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                              placeholder="Additional notes about the refund"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Process Refund
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
<form id="process-refund-form" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
</form>

<form id="cancel-refund-form" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
</form>

@push('scripts')
<script>
    function showProcessRefund() {
        document.querySelector('[x-data]').__x.$data.show = true;
    }

    function processRefund(refundId) {
        if (confirm('Process this refund?')) {
            const form = document.getElementById('process-refund-form');
            form.action = `/admin/billing/refunds/${refundId}/process`;
            form.submit();
        }
    }

    function cancelRefund(refundId) {
        if (confirm('Cancel this refund?')) {
            const form = document.getElementById('cancel-refund-form');
            form.action = `/admin/billing/refunds/${refundId}/cancel`;
            form.submit();
        }
    }
</script>
@endpush
@endsection