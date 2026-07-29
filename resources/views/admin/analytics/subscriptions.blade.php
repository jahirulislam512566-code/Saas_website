<!-- admin/analytics/subscriptions.blade.php -->
@extends('layout.app')

@section('title', 'Subscription Analytics')
@section('page-title', 'Subscription Analytics')
@section('page-subtitle', 'Monitor subscription metrics, plans, and retention')

@section('content')
<div x-data="subscriptionAnalytics()" x-init="init()">
    <!-- Subscription KPIs -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Subscribers</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatNumber(stats.total)">0</p>
            <div class="mt-1 text-sm text-green-600 dark:text-green-400" x-text="'+' + stats.growth + '%'"></div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Active Subscriptions</p>
            <p class="text-2xl font-bold text-green-600" x-text="formatNumber(stats.active)">0</p>
            <div class="mt-1 text-sm text-gray-500 dark:text-gray-400" x-text="stats.active_rate + '% active'"></div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Avg Subscription Length</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="stats.avg_length + ' months'">0</p>
            <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">Customer lifetime</div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Revenue per Subscriber</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatCurrency(stats.rps)">$0</p>
            <div class="mt-1 text-sm text-green-600 dark:text-green-400" x-text="'+' + stats.rps_growth + '%'"></div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Subscription Growth -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Subscription Growth</h4>
            <div class="h-64">
                <canvas x-ref="growthChart"></canvas>
            </div>
        </div>

        <!-- Plan Distribution -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Plan Distribution</h4>
            <div class="h-64">
                <canvas x-ref="plansChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Plan Comparison -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden mb-6">
        <div class="p-4 border-b border-gray-200 dark:border-gray-800">
            <h4 class="font-semibold text-gray-900 dark:text-white">Plan Comparison</h4>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Plan</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Subscribers</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">MRR</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ARR</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Churn</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">LTV</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Growth</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    <template x-for="plan in planComparison" :key="plan.name">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full" :style="'background-color: ' + plan.color"></span>
                                    <span class="font-medium text-gray-900 dark:text-white" x-text="plan.name"></span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-right text-gray-900 dark:text-white" x-text="formatNumber(plan.subscribers)"></td>
                            <td class="px-4 py-3 text-right text-gray-900 dark:text-white" x-text="formatCurrency(plan.mrr)"></td>
                            <td class="px-4 py-3 text-right text-gray-900 dark:text-white" x-text="formatCurrency(plan.arr)"></td>
                            <td class="px-4 py-3 text-right text-red-600" x-text="plan.churn + '%'"></td>
                            <td class="px-4 py-3 text-right text-gray-900 dark:text-white" x-text="formatCurrency(plan.ltv)"></td>
                            <td class="px-4 py-3 text-right" :class="plan.growth >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'">
                                <span x-text="(plan.growth >= 0 ? '+' : '') + plan.growth + '%'"></span>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Churn Analysis -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Churn Analysis</h4>
            <div class="space-y-4">
                <div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-700 dark:text-gray-300">Monthly Churn</span>
                        <span class="text-gray-900 dark:text-white font-medium" x-text="stats.monthly_churn + '%'"></span>
                    </div>
                    <div class="mt-1 w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="h-2 rounded-full bg-red-500" :style="'width: ' + stats.monthly_churn + '%'"></div>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-700 dark:text-gray-300">Annual Churn</span>
                        <span class="text-gray-900 dark:text-white font-medium" x-text="stats.annual_churn + '%'"></span>
                    </div>
                    <div class="mt-1 w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="h-2 rounded-full bg-orange-500" :style="'width: ' + stats.annual_churn + '%'"></div>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-700 dark:text-gray-300">Churn Recovery</span>
                        <span class="text-gray-900 dark:text-white font-medium" x-text="stats.churn_recovery + '%'"></span>
                    </div>
                    <div class="mt-1 w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="h-2 rounded-full bg-green-500" :style="'width: ' + stats.churn_recovery + '%'"></div>
                    </div>
                </div>
                <div class="pt-2 text-sm text-gray-500 dark:text-gray-400">
                    <i class="fas fa-info-circle mr-1"></i>
                    <span x-text="'Net churn rate: ' + stats.net_churn + '%'"></span>
                </div>
            </div>
        </div>

        <!-- Subscription Timeline -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Subscription Timeline</h4>
            <div class="space-y-3 max-h-64 overflow-y-auto">
                <template x-for="event in subscriptionEvents" :key="event.id">
                    <div class="flex items-start gap-3 p-2 hover:bg-gray-50 dark:hover:bg-gray-800/50 rounded-lg transition-colors">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0"
                             :class="{
                               'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400': event.type === 'new',
                               'bg-yellow-100 text-yellow-600 dark:bg-yellow-900/30 dark:text-yellow-400': event.type === 'renewed',
                               'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400': event.type === 'cancelled',
                               'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400': event.type === 'upgraded'
                             }">
                            <i :class="event.icon"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-900 dark:text-white" x-text="event.message"></p>
                            <p class="text-xs text-gray-500 dark:text-gray-400" x-text="formatTime(event.timestamp)"></p>
                        </div>
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full"
                              :class="{
                                'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400': event.status === 'completed',
                                'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400': event.status === 'pending'
                              }"
                              x-text="event.status"></span>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function subscriptionAnalytics() {
    return {
        stats: @json($stats),
        planComparison: @json($planComparison),
        subscriptionEvents: @json($subscriptionEvents),
        growthChart: null,
        plansChart: null,
        
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
                            label: 'New Subscriptions',
                            data: @json($chartData['growth']['new']),
                            borderColor: '#4f46e5',
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Total Subscriptions',
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

            // Plans Chart
            const ctx2 = this.$refs.plansChart.getContext('2d');
            this.plansChart = new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: @json($chartData['plans']['labels']),
                    datasets: [{
                        data: @json($chartData['plans']['values']),
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
        },
        
        formatTime(timestamp) {
            const date = new Date(timestamp);
            const now = new Date();
            const diff = now - date;
            
            if (diff < 60000) return 'Just now';
            if (diff < 3600000) return Math.floor(diff / 60000) + 'm ago';
            if (diff < 86400000) return Math.floor(diff / 3600000) + 'h ago';
            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        }
    }
}
</script>
@endpush