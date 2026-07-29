<!-- admin/reports/invoices.blade.php -->
@extends('layout.app')

@section('title', 'Invoice Reports')
@section('page-title', 'Invoice Reports')
@section('page-subtitle', 'Track invoice status, payments, and outstanding balances')

@section('content')
<div x-data="invoiceReport()" x-init="init()">
    <!-- Quick Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Invoices</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="stats.total">0</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Paid</p>
            <p class="text-2xl font-bold text-green-600" x-text="stats.paid">0</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Pending</p>
            <p class="text-2xl font-bold text-yellow-600" x-text="stats.pending">0</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Overdue</p>
            <p class="text-2xl font-bold text-red-600" x-text="stats.overdue">0</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Outstanding</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="'$' + formatNumber(stats.outstanding)">$0</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm mb-6">
        <div class="flex flex-wrap items-center gap-3">
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Status:</label>
                <select x-model="filters.status" @change="applyFilters()"
                        class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    <option value="all">All</option>
                    <option value="paid">Paid</option>
                    <option value="pending">Pending</option>
                    <option value="overdue">Overdue</option>
                    <option value="cancelled">Cancelled</option>
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

            <div class="flex-1 min-w-[200px]">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" x-model="filters.search" 
                           placeholder="Search invoices..." 
                           class="w-full pl-9 pr-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <button @click="applyFilters()" 
                    class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all">
                <i class="fas fa-search mr-1.5"></i>Search
            </button>
            <button @click="resetFilters()" 
                    class="px-4 py-1.5 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                <i class="fas fa-undo mr-1.5"></i>Reset
            </button>
        </div>
    </div>

    <!-- Invoices Table -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Invoice #</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Customer</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Due Date</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    <template x-for="invoice in filteredInvoices" :key="invoice.id">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-4 py-3">
                                <span class="font-mono text-indigo-600 dark:text-indigo-400" x-text="invoice.number"></span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <img :src="invoice.customer?.avatar || 'https://ui-avatars.com/api/?name=' + (invoice.customer?.name || 'Guest')" 
                                         alt="" class="w-6 h-6 rounded-full">
                                    <span class="text-gray-900 dark:text-white" x-text="invoice.customer?.name || 'Unknown'"></span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400" x-text="formatDate(invoice.created_at)"></td>
                            <td class="px-4 py-3" :class="isOverdue(invoice.due_date) ? 'text-red-600 dark:text-red-400' : 'text-gray-600 dark:text-gray-400'" 
                                x-text="formatDate(invoice.due_date)"></td>
                            <td class="px-4 py-3 text-right font-medium text-gray-900 dark:text-white" x-text="'$' + formatNumber(invoice.amount)"></td>
                            <td class="px-4 py-3 text-right">
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium"
                                      :class="{
                                        'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400': invoice.status === 'paid',
                                        'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400': invoice.status === 'pending',
                                        'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400': invoice.status === 'overdue',
                                        'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400': invoice.status === 'cancelled'
                                      }">
                                    <i :class="{
                                        'fas fa-check-circle': invoice.status === 'paid',
                                        'fas fa-clock': invoice.status === 'pending',
                                        'fas fa-exclamation-circle': invoice.status === 'overdue',
                                        'fas fa-times-circle': invoice.status === 'cancelled'
                                    }"></i>
                                    <span x-text="invoice.status"></span>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button @click="viewInvoice(invoice)" 
                                            class="p-1.5 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>
                                    <button @click="downloadInvoice(invoice)" 
                                            class="p-1.5 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                        <i class="fas fa-download text-xs"></i>
                                    </button>
                                    <button @click="sendReminder(invoice)" x-show="invoice.status === 'pending' || invoice.status === 'overdue'"
                                            class="p-1.5 text-gray-400 hover:text-green-600 dark:hover:text-green-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                        <i class="fas fa-envelope text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="filteredInvoices.length === 0">
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-file-invoice text-3xl text-gray-300 dark:text-gray-700 mb-2"></i>
                                <p>No invoices found</p>
                                <p class="text-xs mt-1">Try adjusting your filters</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-800">
            @include('admin.partials.pagination', ['paginator' => $invoices])
        </div>
    </div>

    <!-- Invoice Detail Modal -->
    <div x-show="showDetailModal" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
         @click.away="showDetailModal = false">
        
        <div x-show="showDetailModal"
             x-transition:enter="transition ease-out duration-200 delay-100"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-hidden"
             @click.stop>
            
            <div class="p-6 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white" x-text="'Invoice ' + selectedInvoice?.number"></h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400" x-text="selectedInvoice?.customer?.name"></p>
                </div>
                <div class="flex items-center gap-2">
                    <button @click="downloadInvoice(selectedInvoice)" 
                            class="px-3 py-1.5 text-sm bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors">
                        <i class="fas fa-download mr-1"></i>PDF
                    </button>
                    <button @click="showDetailModal = false" 
                            class="p-1.5 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                        <i class="fas fa-times text-gray-400"></i>
                    </button>
                </div>
            </div>
            
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-8rem)]">
                <template x-if="selectedInvoice">
                    <div class="space-y-6">
                        <!-- Invoice Details -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Invoice Date</label>
                                <p class="mt-1 text-gray-900 dark:text-white" x-text="formatDate(selectedInvoice.created_at)"></p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Due Date</label>
                                <p class="mt-1 text-gray-900 dark:text-white" x-text="formatDate(selectedInvoice.due_date)"></p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</label>
                                <p class="mt-1">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium"
                                          :class="{
                                            'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400': selectedInvoice.status === 'paid',
                                            'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400': selectedInvoice.status === 'pending',
                                            'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400': selectedInvoice.status === 'overdue',
                                            'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400': selectedInvoice.status === 'cancelled'
                                          }">
                                        <span x-text="selectedInvoice.status"></span>
                                    </span>
                                </p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Amount</label>
                                <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white" x-text="'$' + formatNumber(selectedInvoice.amount)"></p>
                            </div>
                        </div>

                        <!-- Line Items -->
                        <div>
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Line Items</label>
                            <div class="mt-2 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                                <table class="w-full text-sm">
                                    <thead class="bg-gray-50 dark:bg-gray-800/50">
                                        <tr>
                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Description</th>
                                            <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400">Qty</th>
                                            <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400">Price</th>
                                            <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                                        <template x-for="item in selectedInvoice.items" :key="item.id">
                                            <tr>
                                                <td class="px-3 py-2 text-gray-900 dark:text-white" x-text="item.description"></td>
                                                <td class="px-3 py-2 text-right text-gray-600 dark:text-gray-400" x-text="item.quantity"></td>
                                                <td class="px-3 py-2 text-right text-gray-600 dark:text-gray-400" x-text="'$' + formatNumber(item.price)"></td>
                                                <td class="px-3 py-2 text-right text-gray-900 dark:text-white font-medium" x-text="'$' + formatNumber(item.total)"></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                    <tfoot class="bg-gray-50 dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-800">
                                        <tr>
                                            <td colspan="3" class="px-3 py-2 text-right font-medium text-gray-900 dark:text-white">Subtotal</td>
                                            <td class="px-3 py-2 text-right font-medium text-gray-900 dark:text-white" x-text="'$' + formatNumber(selectedInvoice.subtotal)"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="px-3 py-2 text-right text-gray-600 dark:text-gray-400">Tax (<span x-text="selectedInvoice.tax_rate"></span>%)</td>
                                            <td class="px-3 py-2 text-right text-gray-600 dark:text-gray-400" x-text="'$' + formatNumber(selectedInvoice.tax)"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="px-3 py-2 text-right font-bold text-gray-900 dark:text-white">Total</td>
                                            <td class="px-3 py-2 text-right font-bold text-gray-900 dark:text-white" x-text="'$' + formatNumber(selectedInvoice.amount)"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <!-- Payment History -->
                        <div x-show="selectedInvoice.payments?.length">
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Payment History</label>
                            <div class="mt-2 space-y-2">
                                <template x-for="payment in selectedInvoice.payments" :key="payment.id">
                                    <div class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                        <div class="flex items-center gap-3">
                                            <span class="text-xs px-2 py-0.5 rounded-full"
                                                  :class="payment.status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'"
                                                  x-text="payment.status"></span>
                                            <span class="text-sm text-gray-600 dark:text-gray-400" x-text="payment.method"></span>
                                        </div>
                                        <div class="flex items-center gap-4">
                                            <span class="text-sm text-gray-900 dark:text-white" x-text="'$' + formatNumber(payment.amount)"></span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400" x-text="formatDate(payment.created_at)"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
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
function invoiceReport() {
    return {
        invoices: @json($invoices->items()),
        stats: @json($stats),
        filters: {
            status: 'all',
            date_from: '',
            date_to: '',
            search: ''
        },
        selectedInvoice: null,
        showDetailModal: false,
        
        get filteredInvoices() {
            let invoices = this.invoices;
            
            if (this.filters.status !== 'all') {
                invoices = invoices.filter(i => i.status === this.filters.status);
            }
            
            if (this.filters.search) {
                const search = this.filters.search.toLowerCase();
                invoices = invoices.filter(i => 
                    i.number.toLowerCase().includes(search) ||
                    i.customer?.name?.toLowerCase().includes(search) ||
                    i.customer?.email?.toLowerCase().includes(search)
                );
            }
            
            return invoices;
        },
        
        applyFilters() {
            const params = new URLSearchParams(this.filters);
            window.location.href = `{{ route('admin.reports.invoices') }}?${params.toString()}`;
        },
        
        resetFilters() {
            this.filters = {
                status: 'all',
                date_from: '',
                date_to: '',
                search: ''
            };
            this.applyFilters();
        },
        
        viewInvoice(invoice) {
            this.selectedInvoice = invoice;
            this.showDetailModal = true;
        },
        
        downloadInvoice(invoice) {
            window.open(`{{ route('admin.reports.invoices.download', '') }}/${invoice.id}`, '_blank');
        },
        
        sendReminder(invoice) {
            if (confirm(`Send payment reminder for invoice ${invoice.number}?`)) {
                fetch(`{{ route('admin.reports.invoices.reminder', '') }}/${invoice.id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Reminder sent successfully!');
                    }
                });
            }
        },
        
        isOverdue(dueDate) {
            return new Date(dueDate) < new Date();
        },
        
        formatDate(timestamp) {
            if (!timestamp) return 'N/A';
            const date = new Date(timestamp);
            return date.toLocaleDateString('en-US', { 
                month: 'short', 
                day: 'numeric', 
                year: 'numeric' 
            });
        },
        
        formatNumber(value) {
            if (value >= 1000000) return (value / 1000000).toFixed(1) + 'M';
            if (value >= 1000) return (value / 1000).toFixed(1) + 'K';
            return value.toFixed(2);
        }
    }
}
</script>
@endpush