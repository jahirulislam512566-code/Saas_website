<!-- admin/payments/index.blade.php -->
@extends('layout.app')

@section('title', 'Payments')
@section('page-title', 'Payments')
@section('page-subtitle', 'Manage all payments and transactions')

@section('content')
<div x-data="paymentManager()" x-init="init()">
    <!-- Payment Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatCurrency(stats.total_revenue)">$0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/20 flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-green-600 dark:text-green-400"></i>
                </div>
            </div>
            <div class="mt-2 text-sm text-green-600 dark:text-green-400">
                <i class="fas fa-arrow-up mr-1"></i> <span x-text="stats.revenue_growth + '%'"></span> vs last month
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Transactions</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatNumber(stats.total_transactions)">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center">
                    <i class="fas fa-exchange-alt text-blue-600 dark:text-blue-400"></i>
                </div>
            </div>
            <div class="mt-2 text-sm text-green-600 dark:text-green-400">
                <i class="fas fa-arrow-up mr-1"></i> <span x-text="stats.transaction_growth + '%'"></span> vs last month
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Successful</p>
                    <p class="text-2xl font-bold text-green-600" x-text="formatNumber(stats.successful)">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/20 flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 dark:text-green-400"></i>
                </div>
            </div>
            <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                <span x-text="stats.success_rate + '%'"></span> success rate
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Pending</p>
                    <p class="text-2xl font-bold text-yellow-600" x-text="formatNumber(stats.pending)">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-yellow-100 dark:bg-yellow-900/20 flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600 dark:text-yellow-400"></i>
                </div>
            </div>
            <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                Requires attention
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Refunds</p>
                    <p class="text-2xl font-bold text-red-600" x-text="formatCurrency(stats.refunds)">$0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-red-100 dark:bg-red-900/20 flex items-center justify-center">
                    <i class="fas fa-undo-alt text-red-600 dark:text-red-400"></i>
                </div>
            </div>
            <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                <span x-text="stats.refund_rate + '%'"></span> of total
            </div>
        </div>
    </div>

    <!-- Filters & Actions -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm mb-6">
        <div class="flex flex-wrap items-center gap-3">
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Status:</label>
                <select x-model="filters.status" @change="applyFilters()"
                        class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    <option value="all">All Status</option>
                    <option value="successful">Successful</option>
                    <option value="pending">Pending</option>
                    <option value="failed">Failed</option>
                    <option value="refunded">Refunded</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Gateway:</label>
                <select x-model="filters.gateway" @change="applyFilters()"
                        class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    <option value="all">All Gateways</option>
                    <option value="stripe">Stripe</option>
                    <option value="paypal">PayPal</option>
                    <option value="sslcommerz">SSLCommerz</option>
                    <option value="manual">Manual</option>
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
                           placeholder="Search transactions..." 
                           class="w-full pl-9 pr-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <button @click="applyFilters()" 
                    class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all">
                <i class="fas fa-filter mr-1.5"></i>Apply
            </button>
            <button @click="resetFilters()" 
                    class="px-4 py-1.5 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                <i class="fas fa-undo mr-1.5"></i>Reset
            </button>
            
            <button @click="openCreateModal()" 
                    class="px-4 py-1.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all ml-auto">
                <i class="fas fa-plus mr-1.5"></i>New Payment
            </button>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-12">
                            <input type="checkbox" @click="toggleAll" class="rounded border-gray-300 dark:border-gray-700">
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Transaction ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Customer</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Gateway</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    <template x-for="payment in filteredPayments" :key="payment.id">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-4 py-3">
                                <input type="checkbox" x-model="selectedPayments" :value="payment.id" 
                                       class="rounded border-gray-300 dark:border-gray-700">
                            </td>
                            <td class="px-4 py-3">
                                <span class="font-mono text-indigo-600 dark:text-indigo-400" x-text="payment.transaction_id"></span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <img :src="payment.customer?.avatar || 'https://ui-avatars.com/api/?name=' + (payment.customer?.name || 'Unknown')" 
                                         alt="" class="w-6 h-6 rounded-full">
                                    <span class="text-gray-900 dark:text-white" x-text="payment.customer?.name || 'Unknown'"></span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium"
                                      :class="{
                                        'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400': payment.gateway === 'stripe',
                                        'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400': payment.gateway === 'paypal',
                                        'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400': payment.gateway === 'sslcommerz',
                                        'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400': payment.gateway === 'manual'
                                      }">
                                    <i :class="{
                                        'fab fa-stripe': payment.gateway === 'stripe',
                                        'fab fa-paypal': payment.gateway === 'paypal',
                                        'fas fa-shield-alt': payment.gateway === 'sslcommerz',
                                        'fas fa-hand': payment.gateway === 'manual'
                                    }"></i>
                                    <span x-text="payment.gateway"></span>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right font-medium text-gray-900 dark:text-white" x-text="formatCurrency(payment.amount)"></td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium"
                                      :class="{
                                        'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400': payment.status === 'successful',
                                        'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400': payment.status === 'pending',
                                        'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400': payment.status === 'failed',
                                        'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400': payment.status === 'refunded'
                                      }">
                                    <i :class="{
                                        'fas fa-check-circle': payment.status === 'successful',
                                        'fas fa-clock': payment.status === 'pending',
                                        'fas fa-times-circle': payment.status === 'failed',
                                        'fas fa-undo-alt': payment.status === 'refunded'
                                    }"></i>
                                    <span x-text="payment.status"></span>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400 text-xs" x-text="formatDate(payment.created_at)"></td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button @click="viewPayment(payment)" 
                                            class="p-1.5 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>
                                    <button @click="refundPayment(payment)" x-show="payment.status === 'successful'"
                                            class="p-1.5 text-gray-400 hover:text-red-600 dark:hover:text-red-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                        <i class="fas fa-undo-alt text-xs"></i>
                                    </button>
                                    <button @click="deletePayment(payment)" 
                                            class="p-1.5 text-gray-400 hover:text-red-600 dark:hover:text-red-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="filteredPayments.length === 0">
                        <td colspan="8" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-credit-card text-3xl text-gray-300 dark:text-gray-700 mb-2"></i>
                                <p>No payments found</p>
                                <p class="text-xs mt-1">Try adjusting your filters or create a new payment</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-800 flex items-center justify-between">
            <div class="text-sm text-gray-500 dark:text-gray-400">
                Showing <span x-text="filteredPayments.length"></span> of <span x-text="payments.length"></span> payments
            </div>
            @include('admin.partials.pagination', ['paginator' => $payments])
        </div>
    </div>

    <!-- View Payment Modal -->
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
             class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-hidden"
             @click.stop>
            
            <div class="p-6 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Payment Details</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400" x-text="'Transaction #' + selectedPayment?.transaction_id"></p>
                </div>
                <button @click="showViewModal = false" 
                        class="p-1.5 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                    <i class="fas fa-times text-gray-400"></i>
                </button>
            </div>
            
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-8rem)]">
                <template x-if="selectedPayment">
                    <div class="space-y-6">
                        <!-- Payment Info Grid -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Customer</label>
                                <div class="flex items-center gap-2 mt-1">
                                    <img :src="selectedPayment.customer?.avatar || 'https://ui-avatars.com/api/?name=' + (selectedPayment.customer?.name || 'Unknown')" 
                                         alt="" class="w-8 h-8 rounded-full">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white" x-text="selectedPayment.customer?.name || 'Unknown'"></p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400" x-text="selectedPayment.customer?.email || ''"></p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</label>
                                <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white" x-text="formatCurrency(selectedPayment.amount)"></p>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Gateway</label>
                                <p class="mt-1">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium"
                                          :class="{
                                            'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400': selectedPayment.gateway === 'stripe',
                                            'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400': selectedPayment.gateway === 'paypal',
                                            'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400': selectedPayment.gateway === 'sslcommerz',
                                            'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400': selectedPayment.gateway === 'manual'
                                          }">
                                        <i :class="{
                                            'fab fa-stripe': selectedPayment.gateway === 'stripe',
                                            'fab fa-paypal': selectedPayment.gateway === 'paypal',
                                            'fas fa-shield-alt': selectedPayment.gateway === 'sslcommerz',
                                            'fas fa-hand': selectedPayment.gateway === 'manual'
                                        }"></i>
                                        <span x-text="selectedPayment.gateway"></span>
                                    </span>
                                </p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</label>
                                <p class="mt-1">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium"
                                          :class="{
                                            'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400': selectedPayment.status === 'successful',
                                            'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400': selectedPayment.status === 'pending',
                                            'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400': selectedPayment.status === 'failed',
                                            'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400': selectedPayment.status === 'refunded'
                                          }">
                                        <span x-text="selectedPayment.status"></span>
                                    </span>
                                </p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</label>
                                <p class="mt-1 text-gray-900 dark:text-white" x-text="formatDateTime(selectedPayment.created_at)"></p>
                            </div>
                        </div>

                        <!-- Payment Details -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Payment Method</label>
                                <p class="mt-1 text-gray-900 dark:text-white" x-text="selectedPayment.method || 'N/A'"></p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Gateway Transaction ID</label>
                                <p class="mt-1 font-mono text-xs text-gray-600 dark:text-gray-400" x-text="selectedPayment.gateway_transaction_id || 'N/A'"></p>
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Description</label>
                            <p class="mt-1 text-gray-700 dark:text-gray-300" x-text="selectedPayment.description || 'No description'"></p>
                        </div>

                        <!-- Metadata -->
                        <div x-show="selectedPayment.metadata">
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Metadata</label>
                            <div class="mt-1 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg overflow-x-auto">
                                <pre class="text-xs font-mono text-gray-600 dark:text-gray-400" x-text="JSON.stringify(selectedPayment.metadata, null, 2)"></pre>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-800">
                            <button @click="showViewModal = false" 
                                    class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                                Close
                            </button>
                            <button @click="refundPayment(selectedPayment)" x-show="selectedPayment.status === 'successful'"
                                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg shadow-sm transition-all">
                                <i class="fas fa-undo-alt mr-1.5"></i>Refund
                            </button>
                            <button @click="printReceipt(selectedPayment)" 
                                    class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition-all">
                                <i class="fas fa-print mr-1.5"></i>Print Receipt
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Create Payment Modal -->
    <div x-show="showCreateModal" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
         @click.away="showCreateModal = false">
        
        <div x-show="showCreateModal"
             x-transition:enter="transition ease-out duration-200 delay-100"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden"
             @click.stop>
            
            <div class="p-6 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">New Payment</h3>
                <button @click="showCreateModal = false" 
                        class="p-1.5 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                    <i class="fas fa-times text-gray-400"></i>
                </button>
            </div>
            
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-8rem)]">
                <form @submit.prevent="createPayment()" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Customer *</label>
                        <select x-model="form.customer_id" required
                                class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="">Select customer...</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Amount *</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400">$</span>
                            <input type="number" x-model="form.amount" step="0.01" min="0.01" required
                                   class="w-full pl-7 pr-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gateway *</label>
                            <select x-model="form.gateway" required
                                    class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                <option value="stripe">Stripe</option>
                                <option value="paypal">PayPal</option>
                                <option value="sslcommerz">SSLCommerz</option>
                                <option value="manual">Manual</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                            <select x-model="form.status"
                                    class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                <option value="successful">Successful</option>
                                <option value="pending">Pending</option>
                                <option value="failed">Failed</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Payment Method</label>
                        <input type="text" x-model="form.method"
                               placeholder="e.g. Credit Card, Bank Transfer, etc."
                               class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                        <textarea x-model="form.description" rows="2"
                                  class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-800">
                        <button type="button" @click="showCreateModal = false" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition-all">
                            <i class="fas fa-save mr-1.5"></i>Create Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function paymentManager() {
    return {
        payments: @json($payments->items()),
        customers: @json($customers),
        stats: @json($stats),
        filters: {
            status: 'all',
            gateway: 'all',
            date_from: '',
            date_to: '',
            search: ''
        },
        selectedPayments: [],
        selectedPayment: null,
        showViewModal: false,
        showCreateModal: false,
        form: {
            customer_id: '',
            amount: '',
            gateway: 'stripe',
            status: 'successful',
            method: '',
            description: ''
        },
        
        get filteredPayments() {
            let payments = this.payments;
            
            if (this.filters.status !== 'all') {
                payments = payments.filter(p => p.status === this.filters.status);
            }
            
            if (this.filters.gateway !== 'all') {
                payments = payments.filter(p => p.gateway === this.filters.gateway);
            }
            
            if (this.filters.search) {
                const search = this.filters.search.toLowerCase();
                payments = payments.filter(p => 
                    p.transaction_id.toLowerCase().includes(search) ||
                    p.customer?.name?.toLowerCase().includes(search) ||
                    p.customer?.email?.toLowerCase().includes(search)
                );
            }
            
            return payments;
        },
        
        applyFilters() {
            const params = new URLSearchParams(this.filters);
            window.location.href = `{{ route('admin.payments.index') }}?${params.toString()}`;
        },
        
        resetFilters() {
            this.filters = {
                status: 'all',
                gateway: 'all',
                date_from: '',
                date_to: '',
                search: ''
            };
            this.applyFilters();
        },
        
        toggleAll(e) {
            const checked = e.target.checked;
            this.selectedPayments = checked ? this.filteredPayments.map(p => p.id) : [];
        },
        
        viewPayment(payment) {
            this.selectedPayment = payment;
            this.showViewModal = true;
        },
        
        openCreateModal() {
            this.form = {
                customer_id: '',
                amount: '',
                gateway: 'stripe',
                status: 'successful',
                method: '',
                description: ''
            };
            this.showCreateModal = true;
        },
        
        createPayment() {
            fetch('{{ route("admin.payments.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(this.form)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.showCreateModal = false;
                    location.reload();
                }
            })
            .catch(error => console.error('Error creating payment:', error));
        },
        
        refundPayment(payment) {
            if (confirm(`Are you sure you want to refund this payment?`)) {
                fetch(`{{ route('admin.payments.refund', '') }}/${payment.id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ amount: payment.amount })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                })
                .catch(error => console.error('Error refunding payment:', error));
            }
        },
        
        deletePayment(payment) {
            if (confirm(`Are you sure you want to delete this payment?`)) {
                fetch(`{{ route('admin.payments.destroy', '') }}/${payment.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.payments = this.payments.filter(p => p.id !== payment.id);
                    }
                })
                .catch(error => console.error('Error deleting payment:', error));
            }
        },
        
        printReceipt(payment) {
            window.open(`{{ route('admin.payments.receipt', '') }}/${payment.id}`, '_blank');
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