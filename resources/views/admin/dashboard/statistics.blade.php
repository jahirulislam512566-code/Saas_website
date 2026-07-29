<!-- admin/dashboard/statistics.blade.php -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-7 gap-4 mb-6">
    <!-- Total Revenue -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm hover:shadow-md transition-all group">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Revenue</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatCurrency(stats.total_revenue)">$0</p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fas fa-dollar-sign text-green-600 dark:text-green-400"></i>
            </div>
        </div>
        <div class="mt-2 flex items-center gap-2 text-sm">
            <span class="text-green-600 dark:text-green-400" x-show="stats.revenue_growth >= 0">
                <i class="fas fa-arrow-up mr-1"></i> <span x-text="stats.revenue_growth + '%'"></span>
            </span>
            <span class="text-red-600 dark:text-red-400" x-show="stats.revenue_growth < 0">
                <i class="fas fa-arrow-down mr-1"></i> <span x-text="Math.abs(stats.revenue_growth) + '%'"></span>
            </span>
            <span class="text-gray-500 dark:text-gray-400">vs last month</span>
        </div>
    </div>

    <!-- Monthly Revenue -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm hover:shadow-md transition-all group">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Monthly Revenue</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatCurrency(stats.monthly_revenue)">$0</p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fas fa-calendar-alt text-indigo-600 dark:text-indigo-400"></i>
            </div>
        </div>
        <div class="mt-2 flex items-center gap-2 text-sm">
            <span class="text-green-600 dark:text-green-400" x-show="stats.monthly_growth >= 0">
                <i class="fas fa-arrow-up mr-1"></i> <span x-text="stats.monthly_growth + '%'"></span>
            </span>
            <span class="text-red-600 dark:text-red-400" x-show="stats.monthly_growth < 0">
                <i class="fas fa-arrow-down mr-1"></i> <span x-text="Math.abs(stats.monthly_growth) + '%'"></span>
            </span>
            <span class="text-gray-500 dark:text-gray-400">vs last month</span>
        </div>
    </div>

    <!-- Active Customers -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm hover:shadow-md transition-all group">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Active Customers</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatNumber(stats.active_customers)">0</p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fas fa-user-check text-blue-600 dark:text-blue-400"></i>
            </div>
        </div>
        <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
            <span x-text="stats.active_customers_percent + '%'"></span> of total customers
        </div>
    </div>

    <!-- New Signups -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm hover:shadow-md transition-all group">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">New Signups</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatNumber(stats.new_signups)">0</p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fas fa-user-plus text-purple-600 dark:text-purple-400"></i>
            </div>
        </div>
        <div class="mt-2 flex items-center gap-2 text-sm">
            <span class="text-green-600 dark:text-green-400" x-show="stats.signup_growth >= 0">
                <i class="fas fa-arrow-up mr-1"></i> <span x-text="stats.signup_growth + '%'"></span>
            </span>
            <span class="text-red-600 dark:text-red-400" x-show="stats.signup_growth < 0">
                <i class="fas fa-arrow-down mr-1"></i> <span x-text="Math.abs(stats.signup_growth) + '%'"></span>
            </span>
            <span class="text-gray-500 dark:text-gray-400">vs last month</span>
        </div>
    </div>

    <!-- Active Websites -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm hover:shadow-md transition-all group">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Active Websites</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatNumber(stats.active_websites)">0</p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-teal-100 dark:bg-teal-900/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fas fa-globe text-teal-600 dark:text-teal-400"></i>
            </div>
        </div>
        <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
            <span x-text="stats.websites_percent + '%'"></span> of total websites
        </div>
    </div>

    <!-- Active Subscriptions -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm hover:shadow-md transition-all group">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Subscriptions</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatNumber(stats.active_subscriptions)">0</p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-yellow-100 dark:bg-yellow-900/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fas fa-sync-alt text-yellow-600 dark:text-yellow-400"></i>
            </div>
        </div>
        <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
            MRR: <span x-text="formatCurrency(stats.mrr)"></span>
        </div>
    </div>

    <!-- Pending Orders -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm hover:shadow-md transition-all group">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Pending Orders</p>
                <p class="text-2xl font-bold text-yellow-600" x-text="formatNumber(stats.pending_orders)">0</p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-orange-100 dark:bg-orange-900/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fas fa-clock text-orange-600 dark:text-orange-400"></i>
            </div>
        </div>
        <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
            <span x-text="stats.pending_orders_value ? formatCurrency(stats.pending_orders_value) : '$0'"></span> in pending
        </div>
    </div>

    <!-- Support Tickets -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm hover:shadow-md transition-all group">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Support Tickets</p>
                <p class="text-2xl font-bold text-red-600" x-text="formatNumber(stats.support_tickets)">0</p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-red-100 dark:bg-red-900/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fas fa-headset text-red-600 dark:text-red-400"></i>
            </div>
        </div>
        <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
            <span x-text="stats.urgent_tickets || 0"></span> urgent tickets
        </div>
    </div>
</div>

@push('scripts')
<script>
function formatCurrency(value) {
    if (value >= 1000000) return '$' + (value / 1000000).toFixed(1) + 'M';
    if (value >= 1000) return '$' + (value / 1000).toFixed(1) + 'K';
    return '$' + value.toFixed(2);
}

function formatNumber(value) {
    if (value >= 1000000) return (value / 1000000).toFixed(1) + 'M';
    if (value >= 1000) return (value / 1000).toFixed(1) + 'K';
    return value.toLocaleString();
}
</script>
@endpush