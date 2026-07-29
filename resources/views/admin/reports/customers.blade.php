<!-- admin/reports/customers.blade.php -->
@extends('layout.app')

@section('title', 'Customer Reports')
@section('page-title', 'Customer Reports')
@section('page-subtitle', 'Analyze customer acquisition, retention, and lifetime value')

@section('content')
<div x-data="customerReport()" x-init="init()">
    <!-- Customer KPIs -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Customers</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatNumber(stats.total)">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/20 flex items-center justify-center">
                    <i class="fas fa-users text-indigo-600 dark:text-indigo-400"></i>
                </div>
            </div>
            <div class="mt-2 flex items-center gap-2 text-sm text-green-600 dark:text-green-400">
                <i class="fas fa-arrow-up"></i> <span x-text="stats.growth + '%'"></span> vs last month
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Active Customers</p>
                    <p class="text-2xl font-bold text-green-600" x-text="formatNumber(stats.active)">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/20 flex items-center justify-center">
                    <i class="fas fa-user-check text-green-600 dark:text-green-400"></i>
                </div>
            </div>
            <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                <span x-text="stats.active_rate + '%'"></span> of total customers
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Avg. Lifetime Value</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="'$' + formatNumber(stats.ltv)">$0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/20 flex items-center justify-center">
                    <i class="fas fa-coins text-purple-600 dark:text-purple-400"></i>
                </div>
            </div>
            <div class="mt-2 flex items-center gap-2 text-sm text-green-600 dark:text-green-400">
                <i class="fas fa-arrow-up"></i> <span x-text="stats.ltv_growth + '%'"></span> vs last month
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Churn Rate</p>
                    <p class="text-2xl font-bold text-red-600" x-text="stats.churn + '%'">0%</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-red-100 dark:bg-red-900/20 flex items-center justify-center">
                    <i class="fas fa-user-slash text-red-600 dark:text-red-400"></i>
                </div>
            </div>
            <div class="mt-2 flex items-center gap-2 text-sm" 
                 :class="stats.churn_trend < 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'">
                <i :class="stats.churn_trend < 0 ? 'fas fa-arrow-down' : 'fas fa-arrow-up'"></i>
                <span x-text="Math.abs(stats.churn_trend) + '%'"></span> vs last month
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Customer Growth -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Customer Growth</h4>
            <div class="h-64">
                <canvas x-ref="growthChart"></canvas>
            </div>
        </div>

        <!-- Customer Segments -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Customer Segments</h4>
            <div class="h-64">
                <canvas x-ref="segmentsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Customer Retention -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden mb-6">
        <div class="p-4 border-b border-gray-200 dark:border-gray-800">
            <h4 class="font-semibold text-gray-900 dark:text-white">Customer Retention</h4>
        </div>
        <div class="p-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4 text-center">
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="stats.retention_day_7 + '%'"></p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">7-Day Retention</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4 text-center">
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="stats.retention_day_30 + '%'"></p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">30-Day Retention</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4 text-center">
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="stats.retention_day_90 + '%'"></p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">90-Day Retention</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Customers -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
            <h4 class="font-semibold text-gray-900 dark:text-white">Top Customers by Revenue</h4>
            <button @click="viewAllCustomers" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                View All <i class="fas fa-arrow-right ml-1"></i>
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Customer</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Orders</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Spent</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Avg Order Value</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    <template x-for="customer in topCustomers" :key="customer.id">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <img :src="customer.avatar || 'https://ui-avatars.com/api/?name=' + (customer.name || 'U')" 
                                         alt="" class="w-8 h-8 rounded-full">
                                    <span class="text-gray-900 dark:text-white font-medium" x-text="customer.name"></span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400" x-text="customer.email"></td>
                            <td class="px-4 py-3 text-right text-gray-900 dark:text-white" x-text="formatNumber(customer.orders)"></td>
                            <td class="px-4 py-3 text-right text-gray-900 dark:text-white" x-text="'$' + formatNumber(customer.spent)"></td>
                            <td class="px-4 py-3 text-right text-gray-900 dark:text-white" x-text="'$' + formatNumber(customer.aov)"></td>
                            <td class="px-4 py-3 text-right">
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium"
                                      :class="customer.status === 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400'">
                                    <span class="w-1.5 h-1.5 rounded-full" 
                                          :class="customer.status === 'active' ? 'bg-green-500' : 'bg-gray-500'"></span>
                                    <span x-text="customer.status"></span>
                                </span>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function customerReport() {
    return {
        stats: @json($stats),
        topCustomers: @json($topCustomers),
        growthChart: null,
        segmentsChart: null,
        
        init() {
            this.initCharts();
        },
        
        initCharts() {
            // Growth Chart
            const ctx1 = this.$refs.growthChart.getContext('2d');
            this.growthChart = new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: @json($growthData['labels']),
                    datasets: [
                        {
                            label: 'New Customers',
                            data: @json($growthData['new']),
                            borderColor: '#4f46e5',
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Total Customers',
                            data: @json($growthData['total']),
                            borderColor: '#8b5cf6',
                            backgroundColor: 'rgba(139, 92, 246, 0.1)',
                            fill: true,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#4b5563'
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { color: document.documentElement.classList.contains('dark') ? '#1f2937' : '#e5e7eb' },
                            ticks: { color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#4b5563' }
                        },
                        y: {
                            grid: { color: document.documentElement.classList.contains('dark') ? '#1f2937' : '#e5e7eb' },
                            ticks: { color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#4b5563' }
                        }
                    }
                }
            });

            // Segments Chart
            const ctx2 = this.$refs.segmentsChart.getContext('2d');
            this.segmentsChart = new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: @json($segmentsData['labels']),
                    datasets: [{
                        data: @json($segmentsData['values']),
                        backgroundColor: ['#4f46e5', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981', '#3b82f6']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#4b5563'
                            }
                        }
                    }
                }
            });
        },
        
        viewAllCustomers() {
            window.location.href = '{{ route("admin.customers.index") }}';
        },
        
        formatNumber(value) {
            if (value >= 1000000) return (value / 1000000).toFixed(1) + 'M';
            if (value >= 1000) return (value / 1000).toFixed(1) + 'K';
            return value.toLocaleString();
        }
    }
}
</script>
@endpush