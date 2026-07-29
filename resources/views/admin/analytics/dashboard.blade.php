<!-- admin/analytics/dashboard.blade.php -->
@extends('layout.app')

@section('title', 'Analytics Dashboard')
@section('page-title', 'Business Intelligence')
@section('page-subtitle', 'Real-time insights and performance metrics')

@section('content')
<div x-data="analyticsDashboard()" x-init="init()">
    <!-- Time Period Selector -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm mb-6">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <button @click="setPeriod('today')" 
                        class="px-3 py-1.5 text-sm font-medium rounded-lg transition-all"
                        :class="period === 'today' ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800'">
                    Today
                </button>
                <button @click="setPeriod('week')" 
                        class="px-3 py-1.5 text-sm font-medium rounded-lg transition-all"
                        :class="period === 'week' ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800'">
                    Week
                </button>
                <button @click="setPeriod('month')" 
                        class="px-3 py-1.5 text-sm font-medium rounded-lg transition-all"
                        :class="period === 'month' ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800'">
                    Month
                </button>
                <button @click="setPeriod('quarter')" 
                        class="px-3 py-1.5 text-sm font-medium rounded-lg transition-all"
                        :class="period === 'quarter' ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800'">
                    Quarter
                </button>
                <button @click="setPeriod('year')" 
                        class="px-3 py-1.5 text-sm font-medium rounded-lg transition-all"
                        :class="period === 'year' ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800'">
                    Year
                </button>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-500 dark:text-gray-400" x-text="dateRange"></span>
                <button @click="refreshData()" 
                        class="p-2 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <i class="fas fa-sync" :class="{'animate-spin': loading}"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Revenue</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatCurrency(stats.revenue)">$0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/20 flex items-center justify-center">
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
                <span class="text-gray-500 dark:text-gray-400">vs previous period</span>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">MRR</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatCurrency(stats.mrr)">$0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center">
                    <i class="fas fa-calendar-check text-blue-600 dark:text-blue-400"></i>
                </div>
            </div>
            <div class="mt-2 flex items-center gap-2 text-sm">
                <span class="text-green-600 dark:text-green-400" x-show="stats.mrr_growth >= 0">
                    <i class="fas fa-arrow-up mr-1"></i> <span x-text="stats.mrr_growth + '%'"></span>
                </span>
                <span class="text-red-600 dark:text-red-400" x-show="stats.mrr_growth < 0">
                    <i class="fas fa-arrow-down mr-1"></i> <span x-text="Math.abs(stats.mrr_growth) + '%'"></span>
                </span>
                <span class="text-gray-500 dark:text-gray-400">vs last month</span>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Conversion Rate</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="stats.conversion_rate + '%'">0%</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/20 flex items-center justify-center">
                    <i class="fas fa-percent text-purple-600 dark:text-purple-400"></i>
                </div>
            </div>
            <div class="mt-2 flex items-center gap-2 text-sm">
                <span class="text-green-600 dark:text-green-400" x-show="stats.conversion_growth >= 0">
                    <i class="fas fa-arrow-up mr-1"></i> <span x-text="stats.conversion_growth + '%'"></span>
                </span>
                <span class="text-red-600 dark:text-red-400" x-show="stats.conversion_growth < 0">
                    <i class="fas fa-arrow-down mr-1"></i> <span x-text="Math.abs(stats.conversion_growth) + '%'"></span>
                </span>
                <span class="text-gray-500 dark:text-gray-400">vs previous period</span>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Active Customers</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatNumber(stats.active_customers)">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-yellow-100 dark:bg-yellow-900/20 flex items-center justify-center">
                    <i class="fas fa-users text-yellow-600 dark:text-yellow-400"></i>
                </div>
            </div>
            <div class="mt-2 flex items-center gap-2 text-sm">
                <span class="text-green-600 dark:text-green-400" x-show="stats.customer_growth >= 0">
                    <i class="fas fa-arrow-up mr-1"></i> <span x-text="stats.customer_growth + '%'"></span>
                </span>
                <span class="text-red-600 dark:text-red-400" x-show="stats.customer_growth < 0">
                    <i class="fas fa-arrow-down mr-1"></i> <span x-text="Math.abs(stats.customer_growth) + '%'"></span>
                </span>
                <span class="text-gray-500 dark:text-gray-400">vs previous period</span>
            </div>
        </div>
    </div>

    <!-- Charts Row 1 -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Revenue Chart -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h4 class="font-semibold text-gray-900 dark:text-white">Revenue Overview</h4>
                <div class="flex items-center gap-2">
                    <button @click="revenueChartType = 'line'" 
                            class="px-2 py-1 text-xs rounded transition-colors"
                            :class="revenueChartType === 'line' ? 'bg-indigo-600 text-white' : 'text-gray-500 hover:bg-gray-100'">
                        Line
                    </button>
                    <button @click="revenueChartType = 'bar'" 
                            class="px-2 py-1 text-xs rounded transition-colors"
                            :class="revenueChartType === 'bar' ? 'bg-indigo-600 text-white' : 'text-gray-500 hover:bg-gray-100'">
                        Bar
                    </button>
                </div>
            </div>
            <div class="h-72">
                <canvas x-ref="revenueChart"></canvas>
            </div>
        </div>

        <!-- Customer Growth -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Customer Growth</h4>
            <div class="h-72">
                <canvas x-ref="customerChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Charts Row 2 -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Revenue Breakdown -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Revenue Breakdown</h4>
            <div class="h-64">
                <canvas x-ref="breakdownChart"></canvas>
            </div>
        </div>

        <!-- Subscription Plans -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Subscription Plans</h4>
            <div class="h-64">
                <canvas x-ref="plansChart"></canvas>
            </div>
        </div>

        <!-- Traffic Sources -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Traffic Sources</h4>
            <div class="h-64">
                <canvas x-ref="trafficChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
            <h4 class="font-semibold text-gray-900 dark:text-white">Recent Activity</h4>
            <a href="{{ route('admin.activity-logs.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                View All <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="divide-y divide-gray-100 dark:divide-gray-800">
            <template x-for="activity in recentActivities" :key="activity.id">
                <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center"
                             :class="{
                               'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400': activity.type === 'revenue',
                               'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400': activity.type === 'customer',
                               'bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400': activity.type === 'subscription',
                               'bg-yellow-100 text-yellow-600 dark:bg-yellow-900/30 dark:text-yellow-400': activity.type === 'traffic'
                             }">
                            <i :class="activity.icon"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-900 dark:text-white" x-text="activity.message"></p>
                            <p class="text-xs text-gray-500 dark:text-gray-400" x-text="formatTime(activity.timestamp)"></p>
                        </div>
                        <span class="text-xs px-2 py-1 rounded-full"
                              :class="{
                                'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400': activity.status === 'completed',
                                'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400': activity.status === 'pending',
                                'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400': activity.status === 'failed'
                              }"
                              x-text="activity.status"></span>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function analyticsDashboard() {
    return {
        period: 'month',
        dateRange: '',
        loading: false,
        stats: @json($stats),
        recentActivities: @json($recentActivities),
        revenueChartType: 'line',
        revenueChart: null,
        customerChart: null,
        breakdownChart: null,
        plansChart: null,
        trafficChart: null,
        
        init() {
            this.updateDateRange();
            this.initCharts();
            this.startAutoRefresh();
        },
        
        updateDateRange() {
            const now = new Date();
            switch(this.period) {
                case 'today':
                    this.dateRange = now.toLocaleDateString();
                    break;
                case 'week':
                    const weekStart = new Date(now);
                    weekStart.setDate(now.getDate() - 7);
                    this.dateRange = `${weekStart.toLocaleDateString()} - ${now.toLocaleDateString()}`;
                    break;
                case 'month':
                    const monthStart = new Date(now.getFullYear(), now.getMonth(), 1);
                    this.dateRange = `${monthStart.toLocaleDateString()} - ${now.toLocaleDateString()}`;
                    break;
                case 'quarter':
                    const quarterStart = new Date(now.getFullYear(), Math.floor(now.getMonth() / 3) * 3, 1);
                    this.dateRange = `${quarterStart.toLocaleDateString()} - ${now.toLocaleDateString()}`;
                    break;
                case 'year':
                    const yearStart = new Date(now.getFullYear(), 0, 1);
                    this.dateRange = `${yearStart.toLocaleDateString()} - ${now.toLocaleDateString()}`;
                    break;
            }
        },
        
        setPeriod(period) {
            this.period = period;
            this.updateDateRange();
            this.fetchData();
        },
        
        refreshData() {
            this.fetchData();
        },
        
        fetchData() {
            this.loading = true;
            fetch(`{{ route('admin.analytics.data') }}?period=${this.period}`)
                .then(response => response.json())
                .then(data => {
                    this.stats = data.stats;
                    this.recentActivities = data.activities;
                    this.updateCharts(data);
                    this.loading = false;
                })
                .catch(error => {
                    console.error('Error fetching analytics data:', error);
                    this.loading = false;
                });
        },
        
        initCharts() {
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#9ca3af' : '#4b5563';
            const gridColor = isDark ? '#1f2937' : '#e5e7eb';
            
            // Revenue Chart
            const ctx1 = this.$refs.revenueChart.getContext('2d');
            this.revenueChart = new Chart(ctx1, {
                type: this.revenueChartType,
                data: {
                    labels: @json($chartData['revenue']['labels']),
                    datasets: [
                        {
                            label: 'Revenue',
                            data: @json($chartData['revenue']['values']),
                            borderColor: '#4f46e5',
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'MRR',
                            data: @json($chartData['revenue']['mrr']),
                            borderColor: '#8b5cf6',
                            backgroundColor: 'rgba(139, 92, 246, 0.1)',
                            fill: true,
                            tension: 0.4
                        }
                    ]
                },
                options: this.getChartOptions('Revenue', textColor, gridColor)
            });

            // Customer Chart
            const ctx2 = this.$refs.customerChart.getContext('2d');
            this.customerChart = new Chart(ctx2, {
                type: 'line',
                data: {
                    labels: @json($chartData['customers']['labels']),
                    datasets: [
                        {
                            label: 'New Customers',
                            data: @json($chartData['customers']['new']),
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Total Customers',
                            data: @json($chartData['customers']['total']),
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            fill: true,
                            tension: 0.4
                        }
                    ]
                },
                options: this.getChartOptions('Customers', textColor, gridColor)
            });

            // Breakdown Chart
            const ctx3 = this.$refs.breakdownChart.getContext('2d');
            this.breakdownChart = new Chart(ctx3, {
                type: 'doughnut',
                data: {
                    labels: @json($chartData['breakdown']['labels']),
                    datasets: [{
                        data: @json($chartData['breakdown']['values']),
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
                                padding: 15,
                                font: { size: 11 }
                            }
                        }
                    }
                }
            });

            // Plans Chart
            const ctx4 = this.$refs.plansChart.getContext('2d');
            this.plansChart = new Chart(ctx4, {
                type: 'bar',
                data: {
                    labels: @json($chartData['plans']['labels']),
                    datasets: [{
                        label: 'Subscribers',
                        data: @json($chartData['plans']['values']),
                        backgroundColor: ['#4f46e5', '#8b5cf6', '#ec4899', '#f59e0b']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
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

            // Traffic Chart
            const ctx5 = this.$refs.trafficChart.getContext('2d');
            this.trafficChart = new Chart(ctx5, {
                type: 'doughnut',
                data: {
                    labels: @json($chartData['traffic']['labels']),
                    datasets: [{
                        data: @json($chartData['traffic']['values']),
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
                                color: textColor,
                                padding: 12,
                                font: { size: 10 }
                            }
                        }
                    }
                }
            });
        },
        
        getChartOptions(label, textColor, gridColor) {
            return {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            color: textColor,
                            padding: 15
                        }
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
                                if (label === 'Revenue') return '$' + value.toLocaleString();
                                return value.toLocaleString();
                            }
                        }
                    }
                }
            };
        },
        
        updateCharts(data) {
            // Update chart data dynamically
            if (data.charts) {
                this.revenueChart.data.labels = data.charts.revenue.labels;
                this.revenueChart.data.datasets[0].data = data.charts.revenue.values;
                this.revenueChart.data.datasets[1].data = data.charts.revenue.mrr;
                this.revenueChart.update();
                
                this.customerChart.data.labels = data.charts.customers.labels;
                this.customerChart.data.datasets[0].data = data.charts.customers.new;
                this.customerChart.data.datasets[1].data = data.charts.customers.total;
                this.customerChart.update();
            }
        },
        
        startAutoRefresh() {
            setInterval(() => {
                this.fetchData();
            }, 60000); // Refresh every minute
        },
        
        formatCurrency(value) {
            if (value >= 1000000) return '$' + (value / 1000000).toFixed(1) + 'M';
            if (value >= 1000) return '$' + (value / 1000).toFixed(1) + 'K';
            return '$' + value.toLocaleString();
        },
        
        formatNumber(value) {
            if (value >= 1000000) return (value / 1000000).toFixed(1) + 'M';
            if (value >= 1000) return (value / 1000).toFixed(1) + 'K';
            return value.toLocaleString();
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