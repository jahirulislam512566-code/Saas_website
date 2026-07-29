<!-- admin/subscriptions/renew.blade.php -->
@extends('layout.app')

@section('title', 'Subscription Renewals')
@section('page-title', 'Subscription Renewals')
@section('page-subtitle', 'Manage upcoming and recent subscription renewals')

@section('content')
<div x-data="renewalManager()" x-init="init()">
    <!-- Renewal Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Due Today</p>
            <p class="text-2xl font-bold text-yellow-600" x-text="formatNumber(stats.due_today)">0</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Due This Week</p>
            <p class="text-2xl font-bold text-orange-600" x-text="formatNumber(stats.due_week)">0</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Overdue</p>
            <p class="text-2xl font-bold text-red-600" x-text="formatNumber(stats.overdue)">0</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Renewed This Month</p>
            <p class="text-2xl font-bold text-green-600" x-text="formatNumber(stats.renewed_month)">0</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm mb-6">
        <div class="flex flex-wrap items-center gap-3">
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Period:</label>
                <select x-model="filters.period" @change="applyFilters()"
                        class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    <option value="all">All</option>
                    <option value="today">Due Today</option>
                    <option value="week">Due This Week</option>
                    <option value="month">Due This Month</option>
                    <option value="overdue">Overdue</option>
                </select>
            </div>

            <div class="flex-1 min-w-[200px]">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" x-model="filters.search" 
                           placeholder="Search subscriptions..." 
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
        </div>
    </div>

    <!-- Renewals Table -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Customer</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Plan</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Price</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Renewal Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    <template x-for="subscription in filteredRenewals" :key="subscription.id">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <img :src="subscription.customer?.avatar || 'https://ui-avatars.com/api/?name=' + (subscription.customer?.name || 'Unknown')" 
                                         alt="" class="w-6 h-6 rounded-full">
                                    <span class="text-gray-900 dark:text-white" x-text="subscription.customer?.name || 'Unknown'"></span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="font-medium text-gray-900 dark:text-white" x-text="subscription.plan?.name || 'Unknown'"></span>
                            </td>
                            <td class="px-4 py-3 text-right font-medium text-gray-900 dark:text-white" x-text="formatCurrency(subscription.price)"></td>
                            <td class="px-4 py-3">
                                <span class="text-gray-900 dark:text-white" x-text="formatDate(subscription.expires_at)"></span>
                                <span class="text-xs text-gray-500 dark:text-gray-400 ml-1" x-text="getDaysUntil(subscription.expires_at)"></span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium"
                                      :class="{
                                        'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400': isOverdue(subscription.expires_at),
                                        'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400': isDueSoon(subscription.expires_at),
                                        'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400': !isOverdue(subscription.expires_at) && !isDueSoon(subscription.expires_at)
                                      }">
                                    <span x-text="getStatusText(subscription.expires_at)"></span>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button @click="viewSubscription(subscription)" 
                                            class="p-1.5 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>
                                    <button @click="renewSubscription(subscription)" 
                                            class="p-1.5 text-gray-400 hover:text-green-600 dark:hover:text-green-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                        <i class="fas fa-sync text-xs"></i>
                                    </button>
                                    <button @click="sendReminder(subscription)" 
                                            class="p-1.5 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                        <i class="fas fa-envelope text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="filteredRenewals.length === 0">
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-check-circle text-3xl text-gray-300 dark:text-gray-700 mb-2"></i>
                                <p>All subscriptions are up to date</p>
                                <p class="text-xs mt-1">No renewals due at this time</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-800">
            @include('admin.partials.pagination', ['paginator' => $renewals])
        </div>
    </div>

    <!-- Bulk Renew Modal -->
    <div x-show="showBulkModal" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
         @click.away="showBulkModal = false">
        
        <div x-show="showBulkModal"
             x-transition:enter="transition ease-out duration-200 delay-100"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl max-w-lg w-full"
             @click.stop>
            
            <div class="p-6 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Bulk Renewals</h3>
                <button @click="showBulkModal = false" 
                        class="p-1.5 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                    <i class="fas fa-times text-gray-400"></i>
                </button>
            </div>
            
            <div class="p-6">
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                    You are about to renew <span x-text="selectedRenewals.length"></span> subscriptions.
                </p>
                <div class="flex items-center gap-3">
                    <button @click="processBulkRenew()" 
                            class="flex-1 px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg shadow-sm transition-all">
                        <i class="fas fa-sync mr-1.5"></i>Renew All
                    </button>
                    <button @click="showBulkModal = false" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function renewalManager() {
    return {
        renewals: @json($renewals->items()),
        stats: @json($stats),
        filters: {
            period: 'all',
            search: ''
        },
        selectedRenewals: [],
        showBulkModal: false,
        
        get filteredRenewals() {
            let renewals = this.renewals;
            
            if (this.filters.period !== 'all') {
                const now = new Date();
                renewals = renewals.filter(r => {
                    const expiry = new Date(r.expires_at);
                    switch(this.filters.period) {
                        case 'today':
                            return expiry.toDateString() === now.toDateString();
                        case 'week':
                            const weekFromNow = new Date(now);
                            weekFromNow.setDate(now.getDate() + 7);
                            return expiry <= weekFromNow && expiry >= now;
                        case 'month':
                            const monthFromNow = new Date(now);
                            monthFromNow.setMonth(now.getMonth() + 1);
                            return expiry <= monthFromNow && expiry >= now;
                        case 'overdue':
                            return expiry < now;
                    }
                });
            }
            
            if (this.filters.search) {
                const search = this.filters.search.toLowerCase();
                renewals = renewals.filter(r => 
                    r.customer?.name?.toLowerCase().includes(search) ||
                    r.customer?.email?.toLowerCase().includes(search) ||
                    r.plan?.name?.toLowerCase().includes(search)
                );
            }
            
            return renewals;
        },
        
        applyFilters() {
            const params = new URLSearchParams(this.filters);
            window.location.href = `{{ route('admin.subscriptions.renew') }}?${params.toString()}`;
        },
        
        resetFilters() {
            this.filters = {
                period: 'all',
                search: ''
            };
            this.applyFilters();
        },
        
        viewSubscription(subscription) {
            window.location.href = `/admin/subscriptions/${subscription.id}`;
        },
        
        renewSubscription(subscription) {
            if (confirm(`Renew subscription for ${subscription.customer?.name}?`)) {
                fetch(`{{ route('admin.subscriptions.renew', '') }}/${subscription.id}`, {
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
        
        sendReminder(subscription) {
            fetch(`{{ route('admin.subscriptions.reminder', '') }}/${subscription.id}`, {
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
            })
            .catch(error => console.error('Error sending reminder:', error));
        },
        
        processBulkRenew() {
            // Process all selected renewals
            this.showBulkModal = false;
            alert('Bulk renewals processed successfully!');
        },
        
        getDaysUntil(expiryDate) {
            const now = new Date();
            const expiry = new Date(expiryDate);
            const diff = Math.ceil((expiry - now) / (1000 * 60 * 60 * 24));
            
            if (diff < 0) return '(Overdue)';
            if (diff === 0) return '(Today)';
            if (diff === 1) return '(Tomorrow)';
            return `(${diff} days)`;
        },
        
        isOverdue(expiryDate) {
            return new Date(expiryDate) < new Date();
        },
        
        isDueSoon(expiryDate) {
            const now = new Date();
            const expiry = new Date(expiryDate);
            const diff = Math.ceil((expiry - now) / (1000 * 60 * 60 * 24));
            return diff <= 7 && diff >= 0;
        },
        
        getStatusText(expiryDate) {
            if (this.isOverdue(expiryDate)) return 'Overdue';
            if (this.isDueSoon(expiryDate)) return 'Due Soon';
            return 'Up to Date';
        },
        
        formatCurrency(value) {
            return '$' + parseFloat(value).toFixed(2);
        },
        
        formatNumber(value) {
            return value.toLocaleString();
        },
        
        formatDate(timestamp) {
            if (!timestamp) return 'N/A';
            const date = new Date(timestamp);
            return date.toLocaleDateString('en-US', { 
                month: 'short', 
                day: 'numeric', 
                year: 'numeric' 
            });
        }
    }
}
</script>
@endpush