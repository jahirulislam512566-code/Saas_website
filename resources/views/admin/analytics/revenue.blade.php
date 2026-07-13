@extends('admin.layouts.admin')

@section('title', 'Revenue Analytics')

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
            <span class="text-gray-500">Revenue</span>
        </div>
    </li>
@endsection

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Revenue Analytics</h1>
            <p class="text-sm text-gray-500 mt-1">Track revenue, MRR, and financial performance</p>
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
            <a href="{{ route('admin.analytics.revenue.export') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-download mr-2"></i> Export
            </a>
        </div>
    </div>

    <!-- Revenue Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">MRR</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($revenueStats['mrr'] ?? 0, 2) }}</p>
                    <p class="text-xs {{ ($revenueStats['mrr_growth'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        <i class="fas fa-{{ ($revenueStats['mrr_growth'] ?? 0) >= 0 ? 'arrow-up' : 'arrow-down' }} mr-1"></i>
                        {{ abs($revenueStats['mrr_growth'] ?? 0) }}% from last period
                    </p>
                </div>
                <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-chart-line text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">ARR</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($revenueStats['arr'] ?? 0, 2) }}</p>
                    <p class="text-xs {{ ($revenueStats['arr_growth'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        <i class="fas fa-{{ ($revenueStats['arr_growth'] ?? 0) >= 0 ? 'arrow-up' : 'arrow-down' }} mr-1"></i>
                        {{ abs($revenueStats['arr_growth'] ?? 0) }}% from last period
                    </p>
                </div>
                <div class="w-12 h-12 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">LTV</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($revenueStats['ltv'] ?? 0, 2) }}</p>
                    <p class="text-xs {{ ($revenueStats['ltv_growth'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        <i class="fas fa-{{ ($revenueStats['ltv_growth'] ?? 0) >= 0 ? 'arrow-up' : 'arrow-down' }} mr-1"></i>
                        {{ abs($revenueStats['ltv_growth'] ?? 0) }}% from last period
                    </p>
                </div>
                <div class="w-12 h-12 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                    <i class="fas fa-user-clock text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">CAC</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($revenueStats['cac'] ?? 0, 2) }}</p>
                    <p class="text-xs {{ ($revenueStats['cac_growth'] ?? 0) >= 0 ? 'text-red-600' : 'text-green-600' }}">
                        <i class="fas fa-{{ ($revenueStats['cac_growth'] ?? 0) >= 0 ? 'arrow-up' : 'arrow-down' }} mr-1"></i>
                        {{ abs($revenueStats['cac_growth'] ?? 0) }}% from last period
                    </p>
                </div>
                <div class="w-12 h-12 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional KPI Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-indigo-500">
            <p class="text-sm font-medium text-gray-500">Churn Rate</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($revenueStats['churn_rate'] ?? 0, 1) }}%</p>
            <p class="text-xs {{ ($revenueStats['churn_rate_trend'] ?? 0) <= 0 ? 'text-green-600' : 'text-red-600' }}">
                <i class="fas fa-{{ ($revenueStats['churn_rate_trend'] ?? 0) <= 0 ? 'arrow-down' : 'arrow-up' }} mr-1"></i>
                {{ abs($revenueStats['churn_rate_trend'] ?? 0) }}% from last period
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-pink-500">
            <p class="text-sm font-medium text-gray-500">Average Revenue Per User</p>
            <p class="text-2xl font-bold text-gray-900">${{ number_format($revenueStats['arpu'] ?? 0, 2) }}</p>
            <p class="text-xs {{ ($revenueStats['arpu_growth'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                <i class="fas fa-{{ ($revenueStats['arpu_growth'] ?? 0) >= 0 ? 'arrow-up' : 'arrow-down' }} mr-1"></i>
                {{ abs($revenueStats['arpu_growth'] ?? 0) }}% from last period
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-teal-500">
            <p class="text-sm font-medium text-gray-500">Payback Period</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($revenueStats['payback_period'] ?? 0, 1) }} months</p>
            <p class="text-xs {{ ($revenueStats['payback_trend'] ?? 0) <= 0 ? 'text-green-600' : 'text-red-600' }}">
                <i class="fas fa-{{ ($revenueStats['payback_trend'] ?? 0) <= 0 ? 'arrow-down' : 'arrow-up' }} mr-1"></i>
                {{ abs($revenueStats['payback_trend'] ?? 0) }} months change
            </p>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue Breakdown -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Revenue Breakdown</h3>
                <span class="text-xs text-gray-500">By source</span>
            </div>
            <div class="h-80">
                <canvas id="revenueBreakdownChart"></canvas>
            </div>
        </div>

        <!-- MRR Growth -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">MRR Growth</h3>
                <div class="flex items-center space-x-2">
                    <button onclick="changeMrrPeriod('weekly')" class="text-xs px-2 py-1 rounded bg-primary-600 text-white">Weekly</button>
                    <button onclick="changeMrrPeriod('monthly')" class="text-xs px-2 py-1 rounded bg-gray-200 text-gray-700 hover:bg-gray-300">Monthly</button>
                    <button onclick="changeMrrPeriod('yearly')" class="text-xs px-2 py-1 rounded bg-gray-200 text-gray-700 hover:bg-gray-300">Yearly</button>
                </div>
            </div>
            <div class="h-80">
                <canvas id="mrrGrowthChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Revenue by Plan -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Revenue by Plan</h3>
                <p class="text-sm text-gray-500">Detailed revenue breakdown by subscription plan</p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="text-xs text-gray-500">Total Revenue: ${{ number_format($totalRevenue ?? 0, 2) }}</span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subscribers</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monthly Revenue</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Yearly Revenue</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Revenue</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Growth</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">% of Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($revenueByPlan ?? [] as $plan)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                <div class="flex items-center space-x-2">
                                    <div class="w-3 h-3 rounded-full" style="background-color: {{ $plan['color'] ?? '#6366f1' }}"></div>
                                    <span>{{ $plan['name'] ?? 'Unknown' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ number_format($plan['subscribers'] ?? 0) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">${{ number_format($plan['monthly_revenue'] ?? 0, 2) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">${{ number_format($plan['yearly_revenue'] ?? 0, 2) }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">${{ number_format($plan['total_revenue'] ?? 0, 2) }}</td>
                            <td class="px-6 py-4">
                                <span class="text-sm {{ ($plan['growth'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    <i class="fas fa-{{ ($plan['growth'] ?? 0) >= 0 ? 'arrow-up' : 'arrow-down' }} mr-1"></i>
                                    {{ abs($plan['growth'] ?? 0) }}%
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <div class="w-20 bg-gray-200 rounded-full h-2">
                                        <div class="bg-primary-600 h-2 rounded-full" style="width: {{ $plan['percentage'] ?? 0 }}%"></div>
                                    </div>
                                    <span class="text-sm text-gray-500">{{ number_format($plan['percentage'] ?? 0, 1) }}%</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-inbox text-gray-300 text-4xl mb-3 block"></i>
                                No revenue data available
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Recent Transactions</h3>
            <a href="{{ route('admin.payments.index') }}" class="text-sm text-primary-600 hover:text-primary-800">
                View All <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentTransactions ?? [] as $transaction)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-500">#{{ $transaction['id'] ?? '' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $transaction['user'] ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $transaction['plan'] ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">${{ number_format($transaction['amount'] ?? 0, 2) }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ ($transaction['status'] ?? '') == 'completed' ? 'bg-green-100 text-green-800' : 
                                       (($transaction['status'] ?? '') == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                       'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($transaction['status'] ?? 'unknown') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $transaction['date'] ?? '' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-inbox text-gray-300 text-4xl mb-3 block"></i>
                                No recent transactions
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
    let revenueBreakdownInstance = null;
    let mrrGrowthInstance = null;

    document.addEventListener('DOMContentLoaded', function() {
        initRevenueCharts();
        setupPeriodSelector();
    });

    function initRevenueCharts() {
        // Revenue Breakdown Chart
        const breakdownCtx = document.getElementById('revenueBreakdownChart').getContext('2d');
        revenueBreakdownInstance = new Chart(breakdownCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($breakdownLabels ?? ['Subscriptions', 'One-time', 'Upgrades', 'Add-ons', 'Other']) !!},
                datasets: [{
                    data: {!! json_encode($breakdownData ?? [40, 25, 20, 10, 5]) !!},
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
                                let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return label + ': $' + value.toFixed(2) + ' (' + percentage + '%)';
                            }
                        }
                    }
                },
                cutout: '65%'
            }
        });

        // MRR Growth Chart
        const mrrCtx = document.getElementById('mrrGrowthChart').getContext('2d');
        mrrGrowthInstance = new Chart(mrrCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($mrrLabels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']) !!},
                datasets: [
                    {
                        label: 'MRR',
                        data: {!! json_encode($mrrData ?? [10000, 12000, 15000, 18000, 22000, 28000]) !!},
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4
                    },
                    {
                        label: 'New MRR',
                        data: {!! json_encode($newMrrData ?? [1000, 1500, 2000, 2500, 3000, 3500]) !!},
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderDash: [5, 5],
                        pointBackgroundColor: '#6366f1',
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
                                return context.dataset.label + ': $' + context.parsed.y.toFixed(2);
                            }
                        }
                    }
                },
                scales: {
                    y: {
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
    }

    function changeMrrPeriod(period) {
        // Update button styles
        const buttons = document.querySelector('#mrrGrowthChart').closest('.bg-white').querySelectorAll('button');
        buttons.forEach(btn => {
            btn.classList.remove('bg-primary-600', 'text-white');
            btn.classList.add('bg-gray-200', 'text-gray-700');
        });
        event.target.classList.remove('bg-gray-200', 'text-gray-700');
        event.target.classList.add('bg-primary-600', 'text-white');

        // Fetch new data
        fetch(`{{ route('admin.analytics.revenue.mrr-data') }}?period=${period}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mrrGrowthInstance.data.labels = data.labels;
                    mrrGrowthInstance.data.datasets[0].data = data.mrr;
                    mrrGrowthInstance.data.datasets[1].data = data.new_mrr;
                    mrrGrowthInstance.update();
                }
            })
            .catch(error => console.error('Error updating MRR chart:', error));
    }

    function setupPeriodSelector() {
        const select = document.getElementById('period-select');
        if (select) {
            select.addEventListener('change', function() {
                const period = this.value;
                window.location.href = `{{ route('admin.analytics.revenue') }}?period=${period}`;
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