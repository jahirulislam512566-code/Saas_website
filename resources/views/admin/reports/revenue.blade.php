<!-- admin/reports/revenue.blade.php -->
@extends('layout.app')

@section('title', 'Revenue Reports')
@section('page-title', 'Revenue Reports')
@section('page-subtitle', 'Track revenue streams, subscriptions, and payment analytics')

@section('content')
<div x-data="revenueReport()" x-init="init()">
    <!-- Filters -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm mb-6">
        <div class="flex flex-wrap items-center gap-3">
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Date Range:</label>
                <select x-model="filters.period" @change="applyFilters()"
                        class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    <option value="week">Last 7 Days</option>
                    <option value="month">Last 30 Days</option>
                    <option value="quarter">Last 90 Days</option>
                    <option value="year">Last 365 Days</option>
                    <option value="custom">Custom</option>
                </select>
            </div>

            <div x-show="filters.period === 'custom'" class="flex items-center gap-2">
                <input type="date" x-model="filters.date_from" 
                       class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                <span class="text-gray-500">to</span>
                <input type="date" x-model="filters.date_to" 
                       class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
            </div>

            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Revenue Type:</label>
                <select x-model="filters.type" @change="applyFilters()"
                        class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    <option value="all">All Revenue</option>
                    <option value="subscriptions">Subscriptions</option>
                    <option value="one_time">One-Time Payments</option>
                    <option value="upgrades">Upgrades</option>
                    <option value="refunds">Refunds</option>
                </select>
            </div>

            <button @click="applyFilters()" 
                    class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all">
                <i class="fas fa-sync mr-1.5"></i>Update
            </button>
        </div>
    </div>

    <!-- Revenue KPIs -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="'$' + formatNumber(stats.total_revenue)">$0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/20 flex items-center justify-center">
                    <i class="fas fa-chart-line text-indigo-600 dark:text-indigo-400"></i>
                </div>
            </div>
            <div class="mt-2 flex items-center gap-1 text-sm text-green-600 dark:text-green-400">
                <i class="fas fa-arrow-up"></i> <span x-text="stats.revenue_growth + '%'"></span> vs last period
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">MRR</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="'$' + formatNumber(stats.mrr)">$0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/20 flex items-center justify-center">
                    <i class="fas fa-calendar-check text-green-600 dark:text-green-400"></i>
                </div>
            </div>
            <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                Monthly Recurring Revenue
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">ARR</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="'$' + formatNumber(stats.arr)">$0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/20 flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-purple-600 dark:text-purple-400"></i>
                </div>
            </div>
            <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                Annual Recurring Revenue
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Churn Rate</p>
                    <p class="text-2xl font-bold text-red-600" x-text="stats.churn_rate + '%'">0%</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-red-100 dark:bg-red-900/20 flex items-center justify-center">
                    <i class="fas fa-user-minus text-red-600 dark:text-red-400"></i>
                </div>
            </div>
            <div class="mt-2 flex items-center gap-1 text-sm" 
                 :class="stats.churn_trend < 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'">
                <i :class="stats.churn_trend < 0 ? 'fas fa-arrow-down' : 'fas fa-arrow-up'"></i>
                <span x-text="Math.abs(stats.churn_trend) + '%'"></span> vs last period
            </div>
        </div>
    </div>

    <!-- Revenue Breakdown -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-2 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Revenue Breakdown</h4>
            <div class="h-80">
                <canvas x-ref="revenueBreakdownChart"></canvas>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Revenue Sources</h4>
            <div class="space-y-3">
                <template x-for="source in revenueSources" :key="source.name">
                    <div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-700 dark:text-gray-300" x-text="source.name"></span>
                            <span class="text-gray-900 dark:text-white font-medium" x-text="'$' + formatNumber(source.value)"></span>
                        </div>
                        <div class="mt-1 w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="h-2 rounded-full transition-all duration-500"
                                 :style="'width: ' + source.percentage + '%'"
                                 :class="source.color"></div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                            <span x-text="source.percentage + '%'"></span>
                            <span x-text="source.count + ' transactions'"></span>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Subscription Analytics -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-200 dark:border-gray-800">
            <h4 class="font-semibold text-gray-900 dark:text-white">Subscription Analytics</h4>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Plan</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Subscribers</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">MRR</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ARR</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Growth</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Churn</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">LTV</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    <template x-for="plan in subscriptionPlans" :key="plan.id">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full" :style="'background-color: ' + plan.color"></span>
                                    <span class="text-gray-900 dark:text-white font-medium" x-text="plan.name"></span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-right text-gray-900 dark:text-white" x-text="formatNumber(plan.subscribers)"></td>
                            <td class="px-4 py-3 text-right text-gray-900 dark:text-white" x-text="'$' + formatNumber(plan.mrr)"></td>
                            <td class="px-4 py-3 text-right text-gray-900 dark:text-white" x-text="'$' + formatNumber(plan.arr)"></td>
                            <td class="px-4 py-3 text-right">
                                <span class="inline-flex items-center gap-1"
                                      :class="plan.growth >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'">
                                    <i :class="plan.growth >= 0 ? 'fas fa-arrow-up' : 'fas fa-arrow-down'"></i>
                                    <span x-text="Math.abs(plan.growth) + '%'"></span>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right text-red-600 dark:text-red-400" x-text="plan.churn + '%'"></td>
                            <td class="px-4 py-3 text-right text-gray-900 dark:text-white" x-text="'$' + formatNumber(plan.ltv)"></td>
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
function revenueReport() {
    return {
        filters: {
            period: 'month',
            date_from: '',
            date_to: '',
            type: 'all'
        },
        stats: @json($stats),
        revenueSources: @json($revenueSources),
        subscriptionPlans: @json($subscriptionPlans),
        revenueBreakdownChart: null,
        
        init() {
            this.initChart();
        },
        
        initChart() {
            const ctx = this.$refs.revenueBreakdownChart.getContext('2d');
            this.revenueBreakdownChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($chartData['labels']),
                    datasets: [
                        {
                            label: 'Subscriptions',
                            data: @json($chartData['subscriptions']),
                            backgroundColor: 'rgba(79, 70, 229, 0.6)',
                            borderColor: '#4f46e5',
                            borderWidth: 1
                        },
                        {
                            label: 'One-Time',
                            data: @json($chartData['one_time']),
                            backgroundColor: 'rgba(139, 92, 246, 0.6)',
                            borderColor: '#8b5cf6',
                            borderWidth: 1
                        },
                        {
                            label: 'Upgrades',
                            data: @json($chartData['upgrades']),
                            backgroundColor: 'rgba(16, 185, 129, 0.6)',
                            borderColor: '#10b981',
                            borderWidth: 1
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
                            stacked: true,
                            grid: { color: document.documentElement.classList.contains('dark') ? '#1f2937' : '#e5e7eb' },
                            ticks: { color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#4b5563' }
                        },
                        y: {
                            stacked: true,
                            grid: { color: document.documentElement.classList.contains('dark') ? '#1f2937' : '#e5e7eb' },
                            ticks: { 
                                color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#4b5563',
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        },
        
        applyFilters() {
            const params = new URLSearchParams(this.filters);
            window.location.href = `{{ route('admin.reports.revenue') }}?${params.toString()}`;
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