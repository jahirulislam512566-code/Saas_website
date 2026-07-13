@extends('admin.layouts.admin')

@section('title', 'Sales Analytics')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <a href="{{ route('admin.analytics.dashboard') }}" class="text-gray-500 hover:text-gray-700">Analytics</a>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">Sales</span>
        </div>
    </li>
@endsection

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Sales Analytics</h1>
            <p class="text-sm text-gray-500 mt-1">Track your sales performance and revenue metrics</p>
        </div>
        <div class="flex items-center space-x-3">
            <div class="flex items-center space-x-2">
                <label class="text-sm text-gray-600">Period:</label>
                <select id="period-select" class="rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                    <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Today</option>
                    <option value="week" {{ request('period') == 'week' ? 'selected' : '' }}>This Week</option>
                    <option value="month" {{ request('period') == 'month' ? 'selected' : '' }}>This Month</option>
                    <option value="quarter" {{ request('period') == 'quarter' ? 'selected' : '' }}>This Quarter</option>
                    <option value="year" {{ request('period') == 'year' ? 'selected' : '' }}>This Year</option>
                </select>
            </div>
            <button type="button" onclick="refreshData()" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-sync-alt mr-2"></i> Refresh
            </button>
            <a href="{{ route('admin.analytics.sales.export') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-download mr-2"></i> Export
            </a>
        </div>
    </div>

    <!-- Sales Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($salesStats['total_sales'] ?? 0, 2) }}</p>
                    <p class="text-xs {{ ($salesStats['growth'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        <i class="fas fa-{{ ($salesStats['growth'] ?? 0) >= 0 ? 'arrow-up' : 'arrow-down' }} mr-1"></i>
                        {{ abs($salesStats['growth'] ?? 0) }}% from last period
                    </p>
                </div>
                <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Sales</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($salesStats['count'] ?? 0) }}</p>
                    <p class="text-xs {{ ($salesStats['count_growth'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        <i class="fas fa-{{ ($salesStats['count_growth'] ?? 0) >= 0 ? 'arrow-up' : 'arrow-down' }} mr-1"></i>
                        {{ abs($salesStats['count_growth'] ?? 0) }}% from last period
                    </p>
                </div>
                <div class="w-12 h-12 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Average Order Value</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($salesStats['average_order'] ?? 0, 2) }}</p>
                    <p class="text-xs {{ ($salesStats['aov_growth'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        <i class="fas fa-{{ ($salesStats['aov_growth'] ?? 0) >= 0 ? 'arrow-up' : 'arrow-down' }} mr-1"></i>
                        {{ abs($salesStats['aov_growth'] ?? 0) }}% from last period
                    </p>
                </div>
                <div class="w-12 h-12 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                    <i class="fas fa-calculator text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Refund Rate</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($salesStats['refund_rate'] ?? 0, 1) }}%</p>
                    <p class="text-xs {{ ($salesStats['refund_growth'] ?? 0) <= 0 ? 'text-green-600' : 'text-red-600' }}">
                        <i class="fas fa-{{ ($salesStats['refund_growth'] ?? 0) <= 0 ? 'arrow-down' : 'arrow-up' }} mr-1"></i>
                        {{ abs($salesStats['refund_growth'] ?? 0) }}% from last period
                    </p>
                </div>
                <div class="w-12 h-12 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center">
                    <i class="fas fa-undo-alt text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Sales Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-indigo-500">
            <p class="text-sm text-gray-500">Conversion Rate</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($salesStats['conversion_rate'] ?? 0, 1) }}%</p>
            <span class="text-xs text-green-600">
                <i class="fas fa-arrow-up mr-1"></i> {{ number_format($salesStats['conversion_growth'] ?? 0, 1) }}%
            </span>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-pink-500">
            <p class="text-sm text-gray-500">Customer Lifetime Value</p>
            <p class="text-2xl font-bold text-gray-900">${{ number_format($salesStats['ltv'] ?? 0, 2) }}</p>
            <span class="text-xs text-green-600">
                <i class="fas fa-arrow-up mr-1"></i> {{ number_format($salesStats['ltv_growth'] ?? 0, 1) }}%
            </span>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-teal-500">
            <p class="text-sm text-gray-500">Customer Acquisition Cost</p>
            <p class="text-2xl font-bold text-gray-900">${{ number_format($salesStats['cac'] ?? 0, 2) }}</p>
            <span class="text-xs text-green-600">
                <i class="fas fa-arrow-down mr-1"></i> {{ number_format($salesStats['cac_growth'] ?? 0, 1) }}%
            </span>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-rose-500">
            <p class="text-sm text-gray-500">LTV:CAC Ratio</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($salesStats['ltv_cac_ratio'] ?? 0, 1) }}x</p>
            <span class="text-xs {{ ($salesStats['ratio_growth'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                <i class="fas fa-{{ ($salesStats['ratio_growth'] ?? 0) >= 0 ? 'arrow-up' : 'arrow-down' }} mr-1"></i>
                {{ abs($salesStats['ratio_growth'] ?? 0) }}%
            </span>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Sales Trend -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Sales Trend</h3>
                <div class="flex items-center space-x-2">
                    <button onclick="changeSalesTrend('daily')" class="text-xs px-2 py-1 rounded bg-primary-600 text-white">Daily</button>
                    <button onclick="changeSalesTrend('weekly')" class="text-xs px-2 py-1 rounded bg-gray-200 text-gray-700 hover:bg-gray-300">Weekly</button>
                    <button onclick="changeSalesTrend('monthly')" class="text-xs px-2 py-1 rounded bg-gray-200 text-gray-700 hover:bg-gray-300">Monthly</button>
                </div>
            </div>
            <div class="h-80">
                <canvas id="salesTrendChart"></canvas>
            </div>
        </div>

        <!-- Top Plans -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Top Selling Plans</h3>
                <span class="text-xs text-gray-500">By revenue</span>
            </div>
            <div class="h-80">
                <canvas id="topPlansChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Sales by Payment Method -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Sales by Payment Method</h3>
            <div class="h-72">
                <canvas id="paymentMethodChart"></canvas>
            </div>
        </div>

        <!-- Sales Performance -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Sales Performance</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Target Achievement</span>
                        <span class="font-medium text-gray-900">{{ number_format($performance['target_achievement'] ?? 0, 1) }}%</span>
                    </div>
                    <div class="mt-1 w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-primary-600 h-2.5 rounded-full" style="width: {{ $performance['target_achievement'] ?? 0 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Growth Rate</span>
                        <span class="font-medium text-gray-900">{{ number_format($performance['growth_rate'] ?? 0, 1) }}%</span>
                    </div>
                    <div class="mt-1 w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-green-500 h-2.5 rounded-full" style="width: {{ min($performance['growth_rate'] ?? 0, 100) }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Customer Retention</span>
                        <span class="font-medium text-gray-900">{{ number_format($performance['retention_rate'] ?? 0, 1) }}%</span>
                    </div>
                    <div class="mt-1 w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-blue-500 h-2.5 rounded-full" style="width: {{ $performance['retention_rate'] ?? 0 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Upsell Rate</span>
                        <span class="font-medium text-gray-900">{{ number_format($performance['upsell_rate'] ?? 0, 1) }}%</span>
                    </div>
                    <div class="mt-1 w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-purple-500 h-2.5 rounded-full" style="width: {{ $performance['upsell_rate'] ?? 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Sales Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Recent Sales</h3>
                <p class="text-sm text-gray-500">Latest transactions and orders</p>
            </div>
            <a href="{{ route('admin.payments.index') }}" class="text-sm text-primary-600 hover:text-primary-800">
                View All <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentSales ?? [] as $sale)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm font-mono text-gray-900">#{{ $sale['id'] ?? '' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="flex items-center space-x-2">
                                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-medium text-gray-600">
                                        {{ substr($sale['customer'] ?? 'U', 0, 2) }}
                                    </div>
                                    <span>{{ $sale['customer'] ?? 'Unknown' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $sale['plan'] ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">${{ number_format($sale['amount'] ?? 0, 2) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ ucfirst($sale['method'] ?? 'N/A') }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ ($sale['status'] ?? '') == 'completed' ? 'bg-green-100 text-green-800' : 
                                       (($sale['status'] ?? '') == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                       (($sale['status'] ?? '') == 'failed' ? 'bg-red-100 text-red-800' : 
                                       (($sale['status'] ?? '') == 'refunded' ? 'bg-gray-100 text-gray-800' : 'bg-gray-100 text-gray-800'))) }}">
                                    {{ ucfirst($sale['status'] ?? 'unknown') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $sale['date'] ?? '' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-inbox text-gray-300 text-4xl mb-3 block"></i>
                                No sales data available
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let salesTrendInstance = null;
    let topPlansInstance = null;
    let paymentMethodInstance = null;

    document.addEventListener('DOMContentLoaded', function() {
        initSalesCharts();
        setupPeriodSelector();
    });

    function initSalesCharts() {
        // Sales Trend Chart
        const trendCtx = document.getElementById('salesTrendChart').getContext('2d');
        salesTrendInstance = new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($salesTrendLabels ?? ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']) !!},
                datasets: [
                    {
                        label: 'Revenue',
                        data: {!! json_encode($salesTrendData ?? [1200, 1900, 1500, 2200, 2800, 2100, 1800]) !!},
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#6366f1',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4
                    },
                    {
                        label: 'Sales Count',
                        data: {!! json_encode($salesCountData ?? [10, 15, 12, 20, 25, 18, 14]) !!},
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderDash: [5, 5],
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                if (context.datasetIndex === 0) {
                                    return context.dataset.label + ': $' + context.parsed.y.toFixed(2);
                                }
                                return context.dataset.label + ': ' + context.parsed.y;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });

        // Top Plans Chart
        const plansCtx = document.getElementById('topPlansChart').getContext('2d');
        topPlansInstance = new Chart(plansCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($topPlanLabels ?? ['Pro', 'Enterprise', 'Basic', 'Premium', 'Free']) !!},
                datasets: [{
                    label: 'Revenue',
                    data: {!! json_encode($topPlanData ?? [25000, 18000, 12000, 8000, 5000]) !!},
                    backgroundColor: ['#6366f1', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981'],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Revenue: $' + context.parsed.x.toFixed(2);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            },
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    y: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 12
                            }
                        }
                    }
                }
            }
        });

        // Payment Method Chart
        const paymentCtx = document.getElementById('paymentMethodChart').getContext('2d');
        paymentMethodInstance = new Chart(paymentCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($paymentMethodLabels ?? ['Credit Card', 'PayPal', 'Bank Transfer', 'Crypto', 'Other']) !!},
                datasets: [{
                    data: {!! json_encode($paymentMethodData ?? [40, 25, 20, 10, 5]) !!},
                    backgroundColor: ['#6366f1', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981'],
                    borderWidth: 3,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.parsed;
                                let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                let percentage = ((value / total) * 100).toFixed(1);
                                return label + ': $' + value.toFixed(2) + ' (' + percentage + '%)';
                            }
                        }
                    }
                },
                cutout: '65%'
            }
        });
    }

    function changeSalesTrend(period) {
        // Update button styles
        const buttons = document.querySelector('#salesTrendChart').closest('.bg-white').querySelectorAll('button');
        buttons.forEach(btn => {
            btn.classList.remove('bg-primary-600', 'text-white');
            btn.classList.add('bg-gray-200', 'text-gray-700');
        });
        event.target.classList.remove('bg-gray-200', 'text-gray-700');
        event.target.classList.add('bg-primary-600', 'text-white');

        // Fetch new data
        fetch(`{{ route('admin.analytics.sales.trend-data') }}?period=${period}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    salesTrendInstance.data.labels = data.labels;
                    salesTrendInstance.data.datasets[0].data = data.revenue;
                    salesTrendInstance.data.datasets[1].data = data.count;
                    salesTrendInstance.update();
                }
            })
            .catch(error => console.error('Error updating sales trend:', error));
    }

    function setupPeriodSelector() {
        const select = document.getElementById('period-select');
        if (select) {
            select.addEventListener('change', function() {
                const period = this.value;
                window.location.href = `{{ route('admin.analytics.sales') }}?period=${period}`;
            });
        }
    }

    function refreshData() {
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Loading...';
        button.disabled = true;

        setTimeout(() => {
            location.reload();
        }, 500);
    }
</script>
@endpush
@endsection