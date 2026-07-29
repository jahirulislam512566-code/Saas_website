<!-- admin/analytics/revenue.blade.php -->
@extends('layout.app')

@section('title', 'Revenue Analytics')
@section('page-title', 'Revenue Analytics')
@section('page-subtitle', 'Detailed revenue metrics and forecasting')

@section('content')
<div x-data="revenueAnalytics()" x-init="init()">
    <!-- Filters -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm mb-6">
        <div class="flex flex-wrap items-center gap-3">
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">View:</label>
                <select x-model="view" @change="updateView()"
                        class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                    <option value="quarterly">Quarterly</option>
                    <option value="yearly">Yearly</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Metric:</label>
                <select x-model="metric" @change="updateView()"
                        class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    <option value="revenue">Revenue</option>
                    <option value="mrr">MRR</option>
                    <option value="arr">ARR</option>
                    <option value="ltv">LTV</option>
                </select>
            </div>

            <div class="flex items-center gap-2 ml-auto">
                <button @click="exportData()" 
                        class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all">
                    <i class="fas fa-download mr-1.5"></i>Export
                </button>
            </div>
        </div>
    </div>

    <!-- Revenue KPIs -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Revenue</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatCurrency(stats.total_revenue)">$0</p>
            <div class="mt-1 text-sm text-green-600 dark:text-green-400" x-text="'+' + stats.revenue_growth + '%'"></div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">MRR</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatCurrency(stats.mrr)">$0</p>
            <div class="mt-1 text-sm text-green-600 dark:text-green-400" x-text="'+' + stats.mrr_growth + '%'"></div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">ARR</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatCurrency(stats.arr)">$0</p>
            <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">Annualized</div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">LTV</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatCurrency(stats.ltv)">$0</p>
            <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">Customer Lifetime Value</div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">CAC</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatCurrency(stats.cac)">$0</p>
            <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">Customer Acquisition Cost</div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Revenue Trend -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Revenue Trend</h4>
            <div class="h-72">
                <canvas x-ref="revenueTrendChart"></canvas>
            </div>
        </div>

        <!-- Revenue vs Forecast -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Revenue vs Forecast</h4>
            <div class="h-72">
                <canvas x-ref="forecastChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Revenue Breakdown Table -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-200 dark:border-gray-800">
            <h4 class="font-semibold text-gray-900 dark:text-white">Revenue Breakdown by Source</h4>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Source</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Revenue</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">% of Total</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Growth</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Trend</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    <template x-for="source in revenueSources" :key="source.name">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full" :style="'background-color: ' + source.color"></span>
                                    <span class="text-gray-900 dark:text-white" x-text="source.name"></span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-right text-gray-900 dark:text-white" x-text="formatCurrency(source.revenue)"></td>
                            <td class="px-4 py-3 text-right text-gray-600 dark:text-gray-400" x-text="source.percentage + '%'"></td>
                            <td class="px-4 py-3 text-right" :class="source.growth >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'">
                                <span x-text="(source.growth >= 0 ? '+' : '') + source.growth + '%'"></span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                                    <div class="h-1.5 rounded-full" :style="'width: ' + Math.min(source.trend, 100) + '%'" :class="source.trend_color"></div>
                                </div>
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
function revenueAnalytics() {
    return {
        view: 'monthly',
        metric: 'revenue',
        stats: @json($stats),
        revenueSources: @json($revenueSources),
        revenueTrendChart: null,
        forecastChart: null,
        
        init() {
            this.initCharts();
        },
        
        initCharts() {
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#9ca3af' : '#4b5563';
            const gridColor = isDark ? '#1f2937' : '#e5e7eb';
            
            // Revenue Trend Chart
            const ctx1 = this.$refs.revenueTrendChart.getContext('2d');
            this.revenueTrendChart = new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: @json($chartData['trend']['labels']),
                    datasets: [
                        {
                            label: 'Revenue',
                            data: @json($chartData['trend']['revenue']),
                            borderColor: '#4f46e5',
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'MRR',
                            data: @json($chartData['trend']['mrr']),
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
                            ticks: { 
                                color: textColor,
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });

            // Forecast Chart
            const ctx2 = this.$refs.forecastChart.getContext('2d');
            this.forecastChart = new Chart(ctx2, {
                type: 'bar',
                data: {
                    labels: @json($chartData['forecast']['labels']),
                    datasets: [
                        {
                            label: 'Actual',
                            data: @json($chartData['forecast']['actual']),
                            backgroundColor: 'rgba(79, 70, 229, 0.8)',
                            borderColor: '#4f46e5',
                            borderWidth: 1
                        },
                        {
                            label: 'Forecast',
                            data: @json($chartData['forecast']['forecast']),
                            backgroundColor: 'rgba(139, 92, 246, 0.4)',
                            borderColor: '#8b5cf6',
                            borderWidth: 1,
                            borderDash: [5, 5]
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
                            ticks: { 
                                color: textColor,
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        },
        
        updateView() {
            this.fetchData();
        },
        
        fetchData() {
            fetch(`{{ route('admin.analytics.revenue.data') }}?view=${this.view}&metric=${this.metric}`)
                .then(response => response.json())
                .then(data => {
                    this.stats = data.stats;
                    this.revenueSources = data.sources;
                    this.updateCharts(data);
                });
        },
        
        updateCharts(data) {
            if (data.charts) {
                this.revenueTrendChart.data.labels = data.charts.trend.labels;
                this.revenueTrendChart.data.datasets[0].data = data.charts.trend.revenue;
                this.revenueTrendChart.data.datasets[1].data = data.charts.trend.mrr;
                this.revenueTrendChart.update();
                
                this.forecastChart.data.labels = data.charts.forecast.labels;
                this.forecastChart.data.datasets[0].data = data.charts.forecast.actual;
                this.forecastChart.data.datasets[1].data = data.charts.forecast.forecast;
                this.forecastChart.update();
            }
        },
        
        exportData() {
            window.location.href = `{{ route('admin.analytics.revenue.export') }}?view=${this.view}`;
        },
        
        formatCurrency(value) {
            if (value >= 1000000) return '$' + (value / 1000000).toFixed(1) + 'M';
            if (value >= 1000) return '$' + (value / 1000).toFixed(1) + 'K';
            return '$' + value.toLocaleString();
        }
    }
}
</script>
@endpush