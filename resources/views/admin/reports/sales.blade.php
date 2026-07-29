<!-- admin/reports/sales.blade.php -->
@extends('layout.app')

@section('title', 'Sales Reports')
@section('page-title', 'Sales Reports')
@section('page-subtitle', 'Comprehensive sales analytics and performance metrics')

@section('content')
<div x-data="salesReport()" x-init="init()">
    <!-- Date Range & Filters -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm mb-6">
        <div class="flex flex-wrap items-center gap-3">
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Period:</label>
                <select x-model="filters.period" @change="applyFilters()"
                        class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    <option value="today">Today</option>
                    <option value="yesterday">Yesterday</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                    <option value="quarter">This Quarter</option>
                    <option value="year">This Year</option>
                    <option value="custom">Custom Range</option>
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
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Channel:</label>
                <select x-model="filters.channel" @change="applyFilters()"
                        class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    <option value="all">All Channels</option>
                    <option value="direct">Direct</option>
                    <option value="referral">Referral</option>
                    <option value="social">Social Media</option>
                    <option value="email">Email</option>
                    <option value="organic">Organic Search</option>
                </select>
            </div>

            <button @click="applyFilters()" 
                    class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all">
                <i class="fas fa-sync mr-1.5"></i>Update
            </button>
            
            <button @click="exportReport()" 
                    class="px-4 py-1.5 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors ml-auto">
                <i class="fas fa-download mr-1.5"></i>Export
            </button>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="'$' + formatNumber(stats.revenue)">$0</p>
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

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Sales</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatNumber(stats.sales)">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-blue-600 dark:text-blue-400"></i>
                </div>
            </div>
            <div class="mt-2 flex items-center gap-2 text-sm">
                <span class="text-green-600 dark:text-green-400" x-show="stats.sales_growth >= 0">
                    <i class="fas fa-arrow-up mr-1"></i> <span x-text="stats.sales_growth + '%'"></span>
                </span>
                <span class="text-red-600 dark:text-red-400" x-show="stats.sales_growth < 0">
                    <i class="fas fa-arrow-down mr-1"></i> <span x-text="Math.abs(stats.sales_growth) + '%'"></span>
                </span>
                <span class="text-gray-500 dark:text-gray-400">vs previous period</span>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Average Order Value</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="'$' + formatNumber(stats.aov)">$0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/20 flex items-center justify-center">
                    <i class="fas fa-calculator text-purple-600 dark:text-purple-400"></i>
                </div>
            </div>
            <div class="mt-2 flex items-center gap-2 text-sm">
                <span class="text-green-600 dark:text-green-400" x-show="stats.aov_growth >= 0">
                    <i class="fas fa-arrow-up mr-1"></i> <span x-text="stats.aov_growth + '%'"></span>
                </span>
                <span class="text-red-600 dark:text-red-400" x-show="stats.aov_growth < 0">
                    <i class="fas fa-arrow-down mr-1"></i> <span x-text="Math.abs(stats.aov_growth) + '%'"></span>
                </span>
                <span class="text-gray-500 dark:text-gray-400">vs previous period</span>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Conversion Rate</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="stats.conversion_rate + '%'">0%</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-yellow-100 dark:bg-yellow-900/20 flex items-center justify-center">
                    <i class="fas fa-percent text-yellow-600 dark:text-yellow-400"></i>
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
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Revenue Chart -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h4 class="font-semibold text-gray-900 dark:text-white">Revenue Trend</h4>
                <div class="flex items-center gap-2 text-sm">
                    <button @click="chartType = 'line'" class="px-2 py-1 rounded text-xs font-medium transition-colors"
                            :class="chartType === 'line' ? 'bg-indigo-600 text-white' : 'text-gray-500 hover:text-gray-700'">
                        Line
                    </button>
                    <button @click="chartType = 'bar'" class="px-2 py-1 rounded text-xs font-medium transition-colors"
                            :class="chartType === 'bar' ? 'bg-indigo-600 text-white' : 'text-gray-500 hover:text-gray-700'">
                        Bar
                    </button>
                </div>
            </div>
            <div class="h-72">
                <canvas x-ref="revenueChart"></canvas>
            </div>
        </div>

        <!-- Sales Breakdown -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Sales by Channel</h4>
            <div class="h-72">
                <canvas x-ref="channelChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Products / Services -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
            <h4 class="font-semibold text-gray-900 dark:text-white">Top Performing Products</h4>
            <button @click="viewAllProducts" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                View All <i class="fas fa-arrow-right ml-1"></i>
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Product</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Category</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Units Sold</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Revenue</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Growth</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    <template x-for="product in topProducts" :key="product.id">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <img :src="product.image || 'https://via.placeholder.com/40'" alt="" class="w-8 h-8 rounded-lg object-cover">
                                    <div>
                                        <p class="text-gray-900 dark:text-white font-medium" x-text="product.name"></p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400" x-text="product.sku"></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400" x-text="product.category"></td>
                            <td class="px-4 py-3 text-right text-gray-900 dark:text-white" x-text="formatNumber(product.units)"></td>
                            <td class="px-4 py-3 text-right text-gray-900 dark:text-white" x-text="'$' + formatNumber(product.revenue)"></td>
                            <td class="px-4 py-3 text-right">
                                <span class="inline-flex items-center gap-1 text-sm"
                                      :class="product.growth >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'">
                                    <i :class="product.growth >= 0 ? 'fas fa-arrow-up' : 'fas fa-arrow-down'"></i>
                                    <span x-text="Math.abs(product.growth) + '%'"></span>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <button @click="viewProduct(product)" 
                                        class="text-indigo-600 dark:text-indigo-400 hover:underline text-sm">
                                    Details
                                </button>
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
function salesReport() {
    return {
        filters: {
            period: 'month',
            date_from: '',
            date_to: '',
            channel: 'all'
        },
        stats: @json($stats),
        chartType: 'line',
        topProducts: @json($topProducts),
        revenueChart: null,
        channelChart: null,
        
        init() {
            this.initCharts();
        },
        
        initCharts() {
            // Revenue Chart
            const ctx1 = this.$refs.revenueChart.getContext('2d');
            this.revenueChart = new Chart(ctx1, {
                type: this.chartType,
                data: {
                    labels: @json($chartData['labels']),
                    datasets: [{
                        label: 'Revenue',
                        data: @json($chartData['revenue']),
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        fill: true,
                        tension: 0.4
                    }, {
                        label: 'Sales',
                        data: @json($chartData['sales']),
                        borderColor: '#8b5cf6',
                        backgroundColor: 'rgba(139, 92, 246, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
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

            // Channel Chart
            const ctx2 = this.$refs.channelChart.getContext('2d');
            this.channelChart = new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: @json($channelData['labels']),
                    datasets: [{
                        data: @json($channelData['values']),
                        backgroundColor: [
                            '#4f46e5', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981', '#3b82f6'
                        ],
                        borderWidth: 2,
                        borderColor: document.documentElement.classList.contains('dark') ? '#1f2937' : '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#4b5563',
                                padding: 15
                            }
                        }
                    }
                }
            });
        },
        
        applyFilters() {
            const params = new URLSearchParams(this.filters);
            window.location.href = `{{ route('admin.reports.sales') }}?${params.toString()}`;
        },
        
        exportReport() {
            const params = new URLSearchParams(this.filters);
            window.location.href = `{{ route('admin.reports.sales.export') }}?${params.toString()}`;
        },
        
        viewProduct(product) {
            // Navigate to product details
            window.location.href = `/admin/reports/product/${product.id}`;
        },
        
        viewAllProducts() {
            window.location.href = '{{ route("admin.reports.products") }}';
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