<!-- admin/analytics/customers.blade.php -->
@extends('layout.app')

@section('title', 'Customer Analytics')
@section('page-title', 'Customer Analytics')
@section('page-subtitle', 'Customer behavior, segmentation, and lifetime value analysis')

@section('content')
<div x-data="customerAnalytics()" x-init="init()">
    <!-- Customer KPIs -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Customers</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatNumber(stats.total)">0</p>
            <div class="mt-1 text-sm text-green-600 dark:text-green-400" x-text="'+' + stats.growth + '%'"></div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Active</p>
            <p class="text-2xl font-bold text-green-600" x-text="formatNumber(stats.active)">0</p>
            <div class="mt-1 text-sm text-gray-500 dark:text-gray-400" x-text="stats.active_rate + '% active'"></div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Churn Rate</p>
            <p class="text-2xl font-bold text-red-600" x-text="stats.churn + '%'">0%</p>
            <div class="mt-1 text-sm" :class="stats.churn_trend < 0 ? 'text-green-600' : 'text-red-600'">
                <span x-text="(stats.churn_trend < 0 ? '↓' : '↑') + Math.abs(stats.churn_trend) + '%'"></span>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Avg LTV</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatCurrency(stats.ltv)">$0</p>
            <div class="mt-1 text-sm text-green-600 dark:text-green-400" x-text="'+' + stats.ltv_growth + '%'"></div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">NPS Score</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="stats.nps">0</p>
            <div class="mt-1 text-sm text-green-600 dark:text-green-400" x-text="stats.nps_status"></div>
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
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Retention Rates</h4>
            <div class="h-64">
                <canvas x-ref="retentionChart"></canvas>
            </div>
        </div>

        <!-- Cohort Analysis -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Cohort Analysis</h4>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800/50">
                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Cohort</th>
                            <th x-repeat="month in cohortMonths" class="px-2 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-400">
                                <span x-text="month"></span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        <template x-for="cohort in cohorts" :key="cohort.label">
                            <tr>
                                <td class="px-2 py-2 text-gray-900 dark:text-white font-medium" x-text="cohort.label"></td>
                                <td x-repeat="value in cohort.values" class="px-2 py-2 text-center">
                                    <span class="px-2 py-1 rounded text-xs"
                                          :class="{
                                            'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400': value >= 80,
                                            'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400': value >= 50 && value < 80,
                                            'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400': value < 50
                                          }"
                                          x-text="value + '%'"></span>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Customer List -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
            <h4 class="font-semibold text-gray-900 dark:text-white">Top Customers</h4>
            <a href="{{ route('admin.customers.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                View All <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Customer</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Orders</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Spent</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">AOV</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    <template x-for="customer in topCustomers" :key="customer.id">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <img :src="customer.avatar" alt="" class="w-8 h-8 rounded-full">
                                    <div>
                                        <p class="text-gray-900 dark:text-white font-medium" x-text="customer.name"></p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400" x-text="customer.email"></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-right text-gray-900 dark:text-white" x-text="customer.orders"></td>
                            <td class="px-4 py-3 text-right text-gray-900 dark:text-white" x-text="formatCurrency(customer.spent)"></td>
                            <td class="px-4 py-3 text-right text-gray-900 dark:text-white" x-text="formatCurrency(customer.aov)"></td>
                            <td class="px-4 py-3 text-right">
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium"
                                      :class="customer.status === 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400'">
                                    <span class="w-1.5 h-1.5 rounded-full" :class="customer.status === 'active' ? 'bg-green-500' : 'bg-gray-500'"></span>
                                    <span x-text="customer.status"></span>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a :href="'/admin/customers/' + customer.id" class="text-indigo-600 dark:text-indigo-400 hover:underline text-sm">
                                    View Profile
                                </a>
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
function customerAnalytics() {
    return {
        stats: @json($stats),
        topCustomers: @json($topCustomers),
        cohorts: @json($cohorts),
        cohortMonths: ['M0', 'M1', 'M2', 'M3', 'M4', 'M5', 'M6', 'M7', 'M8', 'M9', 'M10', 'M11'],
        growthChart: null,
        segmentsChart: null,
        retentionChart: null,
        
        init() {
            this.initCharts();
        },
        
        initCharts() {
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#9ca3af' : '#4b5563';
            const gridColor = isDark ? '#1f2937' : '#e5e7eb';
            
            // Growth Chart
            const ctx1 = this.$refs.growthChart.getContext('2d');
            this.growthChart = new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: @json($chartData['growth']['labels']),
                    datasets: [
                        {
                            label: 'New Customers',
                            data: @json($chartData['growth']['new']),
                            borderColor: '#4f46e5',
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Total Customers',
                            data: @json($chartData['growth']['total']),
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
                            labels: { color: textColor }
                        }
                    },
                    scales: {
                        x: {
                            grid: { color: gridColor },
                            ticks: { color: textColor }
                        },
                        y: {
                            grid: { color: gridColor },
                            ticks: { color: textColor }
                        }
                    }
                }
            });

            // Segments Chart
            const ctx2 = this.$refs.segmentsChart.getContext('2d');
            this.segmentsChart = new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: @json($chartData['segments']['labels']),
                    datasets: [{
                        data: @json($chartData['segments']['values']),
                        backgroundColor: ['#4f46e5', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: textColor,
                                padding: 15
                            }
                        }
                    }
                }
            });

            // Retention Chart
            const ctx3 = this.$refs.retentionChart.getContext('2d');
            this.retentionChart = new Chart(ctx3, {
                type: 'line',
                data: {
                    labels: ['Day 1', 'Day 7', 'Day 14', 'Day 30', 'Day 60', 'Day 90'],
                    datasets: [{
                        label: 'Retention Rate',
                        data: @json($chartData['retention']),
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: { color: textColor }
                        }
                    },
                    scales: {
                        x: {
                            grid: { color: gridColor },
                            ticks: { color: textColor }
                        },
                        y: {
                            grid: { color: gridColor },
                            ticks: { 
                                color: textColor,
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        }
                    }
                }
            });
        },
        
        formatNumber(value) {
            if (value >= 1000000) return (value / 1000000).toFixed(1) + 'M';
            if (value >= 1000) return (value / 1000).toFixed(1) + 'K';
            return value.toLocaleString();
        },
        
        formatCurrency(value) {
            if (value >= 1000000) return '$' + (value / 1000000).toFixed(1) + 'M';
            if (value >= 1000) return '$' + (value / 1000).toFixed(1) + 'K';
            return '$' + value.toFixed(2);
        }
    }
}
</script>
@endpush