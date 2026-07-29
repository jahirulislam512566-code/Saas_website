<!-- admin/dashboard/widgets.blade.php -->
<!-- Quick Actions -->
<div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
    <div class="p-4 border-b border-gray-200 dark:border-gray-800">
        <h4 class="font-semibold text-gray-900 dark:text-white">Quick Actions</h4>
    </div>
    <div class="p-4 grid grid-cols-2 gap-2">
        <a href="{{ route('admin.websites.create') }}" 
           class="flex flex-col items-center gap-1 p-3 bg-gray-50 dark:bg-gray-800 hover:bg-indigo-50 dark:hover:bg-indigo-900/10 rounded-lg transition-colors group">
            <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fas fa-plus text-indigo-600 dark:text-indigo-400"></i>
            </div>
            <span class="text-xs font-medium text-gray-700 dark:text-gray-300">New Website</span>
        </a>
        
        {{-- <a href="{{ route('admin.users.create') }}" 
           class="flex flex-col items-center gap-1 p-3 bg-gray-50 dark:bg-gray-800 hover:bg-indigo-50 dark:hover:bg-indigo-900/10 rounded-lg transition-colors group">
            <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fas fa-user-plus text-blue-600 dark:text-blue-400"></i>
            </div>
            <span class="text-xs font-medium text-gray-700 dark:text-gray-300">Add User</span>
        </a> --}}
        
        <a href="{{ route('admin.payments.create') }}" 
           class="flex flex-col items-center gap-1 p-3 bg-gray-50 dark:bg-gray-800 hover:bg-indigo-50 dark:hover:bg-indigo-900/10 rounded-lg transition-colors group">
            <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fas fa-dollar-sign text-green-600 dark:text-green-400"></i>
            </div>
            <span class="text-xs font-medium text-gray-700 dark:text-gray-300">New Payment</span>
        </a>
        
        {{-- <a href="{{ route('admin.support.tickets') }}" 
           class="flex flex-col items-center gap-1 p-3 bg-gray-50 dark:bg-gray-800 hover:bg-indigo-50 dark:hover:bg-indigo-900/10 rounded-lg transition-colors group">
            <div class="w-10 h-10 rounded-lg bg-red-100 dark:bg-red-900/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fas fa-headset text-red-600 dark:text-red-400"></i>
            </div>
            <span class="text-xs font-medium text-gray-700 dark:text-gray-300">Support</span>
        </a> --}}
    </div>
</div>

<!-- Server Status -->
<div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
    <div class="p-4 border-b border-gray-200 dark:border-gray-800">
        <h4 class="font-semibold text-gray-900 dark:text-white">Server Status</h4>
    </div>
    <div class="p-4 space-y-3">
        <div>
            <div class="flex items-center justify-between text-sm mb-1">
                <span class="text-gray-600 dark:text-gray-400">CPU Usage</span>
                <span class="text-gray-900 dark:text-white" x-text="server.cpu + '%'">0%</span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                <div class="h-1.5 rounded-full transition-all" 
                     :class="server.cpu < 70 ? 'bg-green-500' : (server.cpu < 85 ? 'bg-yellow-500' : 'bg-red-500')"
                     :style="'width: ' + server.cpu + '%'"></div>
            </div>
        </div>
        
        <div>
            <div class="flex items-center justify-between text-sm mb-1">
                <span class="text-gray-600 dark:text-gray-400">Memory Usage</span>
                <span class="text-gray-900 dark:text-white" x-text="server.memory + '%'">0%</span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                <div class="h-1.5 rounded-full transition-all"
                     :class="server.memory < 80 ? 'bg-green-500' : (server.memory < 90 ? 'bg-yellow-500' : 'bg-red-500')"
                     :style="'width: ' + server.memory + '%'"></div>
            </div>
        </div>
        
        <div>
            <div class="flex items-center justify-between text-sm mb-1">
                <span class="text-gray-600 dark:text-gray-400">Disk Usage</span>
                <span class="text-gray-900 dark:text-white" x-text="server.disk + '%'">0%</span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                <div class="h-1.5 rounded-full transition-all"
                     :class="server.disk < 85 ? 'bg-green-500' : (server.disk < 95 ? 'bg-yellow-500' : 'bg-red-500')"
                     :style="'width: ' + server.disk + '%'"></div>
            </div>
        </div>
        
        <div class="flex items-center justify-between text-sm pt-2 border-t border-gray-100 dark:border-gray-800">
            <span class="text-gray-600 dark:text-gray-400">Uptime</span>
            <span class="text-green-600 dark:text-green-400">
                {{-- <i class="fas fa-check-circle mr-1"></i> {{ server.uptime }} --}}
            </span>
        </div>
        
        <div class="flex items-center justify-between text-sm">
            <span class="text-gray-600 dark:text-gray-400">Response Time</span>
            {{-- <span class="text-gray-900 dark:text-white">{{ server.response_time }}ms</span> --}}
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
    <div class="p-4 border-b border-gray-200 dark:border-gray-800">
        <h4 class="font-semibold text-gray-900 dark:text-white">Quick Stats</h4>
    </div>
    <div class="p-4 space-y-3">
        <div class="flex items-center justify-between">
            <span class="text-sm text-gray-600 dark:text-gray-400">Total Revenue</span>
            <span class="text-sm font-bold text-gray-900 dark:text-white" x-text="formatCurrency(stats.total_revenue)">$0</span>
        </div>
        <div class="flex items-center justify-between">
            <span class="text-sm text-gray-600 dark:text-gray-400">Total Customers</span>
            <span class="text-sm font-bold text-gray-900 dark:text-white" x-text="formatNumber(stats.total_customers)">0</span>
        </div>
        <div class="flex items-center justify-between">
            <span class="text-sm text-gray-600 dark:text-gray-400">Total Websites</span>
            <span class="text-sm font-bold text-gray-900 dark:text-white" x-text="formatNumber(stats.total_websites)">0</span>
        </div>
        <div class="flex items-center justify-between">
            <span class="text-sm text-gray-600 dark:text-gray-400">Conversion Rate</span>
            <span class="text-sm font-bold text-green-600 dark:text-green-400" x-text="stats.conversion_rate + '%'">0%</span>
        </div>
        <div class="flex items-center justify-between">
            <span class="text-sm text-gray-600 dark:text-gray-400">Avg Order Value</span>
            <span class="text-sm font-bold text-gray-900 dark:text-white" x-text="formatCurrency(stats.aov)">$0</span>
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