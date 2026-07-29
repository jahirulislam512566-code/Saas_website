<!-- admin/payments/refunds.blade.php -->
@extends('layout.app')

@section('title', 'Refunds')
@section('page-title', 'Refund Management')
@section('page-subtitle', 'Process and track payment refunds')

@section('content')
<div x-data="refundManager()" x-init="init()">
    <!-- Refund Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Refunds</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatNumber(stats.total)">0</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Amount</p>
            <p class="text-2xl font-bold text-red-600" x-text="formatCurrency(stats.total_amount)">$0</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Pending</p>
            <p class="text-2xl font-bold text-yellow-600" x-text="formatNumber(stats.pending)">0</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Completed</p>
            <p class="text-2xl font-bold text-green-600" x-text="formatNumber(stats.completed)">0</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm mb-6">
        <div class="flex flex-wrap items-center gap-3">
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Status:</label>
                <select x-model="filters.status" @change="applyFilters()"
                        class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    <option value="all">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="completed">Completed</option>
                    <option value="failed">Failed</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Date:</label>
                <input type="date" x-model="filters.date_from" 
                       class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                <span class="text-gray-500">to</span>
                <input type="date" x-model="filters.date_to" 
                       class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
            </div>

            <button @click="applyFilters()" 
                    class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all">
                <i class="fas fa-filter mr-1.5"></i>Apply
            </button>
            <button @click="resetFilters()" 
                    class="px-4 py-1.5 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                <i class="fas fa-undo mr-1.5"></i>Reset
            </button>
        </div>
    </div>

    <!-- Refunds Table -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Refund ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Original Payment</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Customer</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Reason</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    <template x-for="refund in filteredRefunds" :key="refund.id">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-4 py-3">
                                <span class="font-mono text-indigo-600 dark:text-indigo-400">#{{ refund.id }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <a :href="'/admin/payments/' + refund.payment_id" 
                                   class="font-mono text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400">
                                    {{ refund.payment?.transaction_id }}
                                </a>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <img :src="refund.payment?.customer?.avatar || 'https://ui-avatars.com/api/?name=' + (refund.payment?.customer?.name || 'Unknown')" 
                                         alt="" class="w-6 h-6 rounded-full">
                                    <span class="text-gray-900 dark:text-white" x-text="refund.payment?.customer?.name || 'Unknown'"></span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-right font-medium text-red-600 dark:text-red-400" x-text="formatCurrency(refund.amount)"></td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400 max-w-[150px] truncate" x-text="refund.reason || 'N/A'"></td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium"
                                      :class="{
                                        'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400': refund.status === 'completed',
                                        'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400': refund.status === 'pending',
                                        'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400': refund.status === 'failed'
                                      }">
                                    <i :class="{
                                        'fas fa-check-circle': refund.status === 'completed',
                                        'fas fa-clock': refund.status === 'pending',
                                        'fas fa-times-circle': refund.status === 'failed'
                                    }"></i>
                                    <span x-text="refund.status"></span>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400 text-xs" x-text="formatDate(refund.created_at)"></td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button @click="viewRefund(refund)" 
                                            class="p-1.5 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>
                                    <button @click="processRefund(refund)" x-show="refund.status === 'pending'"
                                            class="p-1.5 text-gray-400 hover:text-green-600 dark:hover:text-green-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                        <i class="fas fa-check text-xs"></i>
                                    </button>
                                    <button @click="deleteRefund(refund)" 
                                            class="p-1.5 text-gray-400 hover:text-red-600 dark:hover:text-red-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="filteredRefunds.length === 0">
                        <td colspan="8" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-undo-alt text-3xl text-gray-300 dark:text-gray-700 mb-2"></i>
                                <p>No refunds found</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-800">
            @include('admin.partials.pagination', ['paginator' => $refunds])
        </div>
    </div>

    <!-- View Refund Modal -->
    <div x-show="showViewModal" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
         @click.away="showViewModal = false">
        
        <div x-show="showViewModal"
             x-transition:enter="transition ease-out duration-200 delay-100"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden"
             @click.stop>
            
            <div class="p-6 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Refund Details</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400" x-text="'Refund #' + selectedRefund?.id"></p>
                </div>
                <button @click="showViewModal = false" 
                        class="p-1.5 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                    <i class="fas fa-times text-gray-400"></i>
                </button>
            </div>
            
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-8rem)]">
                <template x-if="selectedRefund">
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Original Payment</label>
                                <p class="mt-1 font-mono text-gray-900 dark:text-white" x-text="selectedRefund.payment?.transaction_id"></p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Customer</label>
                                <p class="mt-1 text-gray-900 dark:text-white" x-text="selectedRefund.payment?.customer?.name || 'Unknown'"></p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Refund Amount</label>
                                <p class="mt-1 text-2xl font-bold text-red-600 dark:text-red-400" x-text="formatCurrency(selectedRefund.amount)"></p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</label>
                                <p class="mt-1">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium"
                                          :class="{
                                            'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400': selectedRefund.status === 'completed',
                                            'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400': selectedRefund.status === 'pending',
                                            'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400': selectedRefund.status === 'failed'
                                          }">
                                        <span x-text="selectedRefund.status"></span>
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Reason</label>
                            <p class="mt-1 text-gray-700 dark:text-gray-300" x-text="selectedRefund.reason || 'No reason provided'"></p>
                        </div>

                        <div>
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Notes</label>
                            <p class="mt-1 text-gray-600 dark:text-gray-400" x-text="selectedRefund.notes || 'No notes'"></p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Requested At</label>
                                <p class="mt-1 text-gray-900 dark:text-white" x-text="formatDateTime(selectedRefund.created_at)"></p>
                            </div>
                            <div x-show="selectedRefund.processed_at">
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Processed At</label>
                                <p class="mt-1 text-gray-900 dark:text-white" x-text="formatDateTime(selectedRefund.processed_at)"></p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-800">
                            <button @click="showViewModal = false" 
                                    class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                                Close
                            </button>
                            <button @click="processRefund(selectedRefund)" x-show="selectedRefund.status === 'pending'"
                                    class="px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg shadow-sm transition-all">
                                <i class="fas fa-check mr-1.5"></i>Process Refund
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function refundManager() {
    return {
        refunds: @json($refunds->items()),
        stats: @json($stats),
        filters: {
            status: 'all',
            date_from: '',
            date_to: ''
        },
        selectedRefund: null,
        showViewModal: false,
        
        get filteredRefunds() {
            let refunds = this.refunds;
            
            if (this.filters.status !== 'all') {
                refunds = refunds.filter(r => r.status === this.filters.status);
            }
            
            return refunds;
        },
        
        applyFilters() {
            const params = new URLSearchParams(this.filters);
            window.location.href = `{{ route('admin.payments.refunds') }}?${params.toString()}`;
        },
        
        resetFilters() {
            this.filters = {
                status: 'all',
                date_from: '',
                date_to: ''
            };
            this.applyFilters();
        },
        
        viewRefund(refund) {
            this.selectedRefund = refund;
            this.showViewModal = true;
        },
        
        processRefund(refund) {
            if (confirm(`Process refund #${refund.id} for $${parseFloat(refund.amount).toFixed(2)}?`)) {
                fetch(`{{ route('admin.payments.refunds.process', '') }}/${refund.id}`, {
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
                .catch(error => console.error('Error processing refund:', error));
            }
        },
        
        deleteRefund(refund) {
            if (confirm(`Delete refund #${refund.id}?`)) {
                fetch(`{{ route('admin.payments.refunds.destroy', '') }}/${refund.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.refunds = this.refunds.filter(r => r.id !== refund.id);
                    }
                })
                .catch(error => console.error('Error deleting refund:', error));
            }
        },
        
        formatCurrency(value) {
            return '$' + parseFloat(value).toFixed(2);
        },
        
        formatNumber(value) {
            return value.toLocaleString();
        },
        
        formatDate(timestamp) {
            const date = new Date(timestamp);
            return date.toLocaleDateString('en-US', { 
                month: 'short', 
                day: 'numeric', 
                year: 'numeric' 
            });
        },
        
        formatDateTime(timestamp) {
            const date = new Date(timestamp);
            return date.toLocaleString('en-US', { 
                month: 'short', 
                day: 'numeric', 
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }
    }
}
</script>
@endpush