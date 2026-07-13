{{-- resources/views/admin/analytics/dashboard.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Analytics Dashboard')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">Analytics</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Analytics Dashboard</h1>
            <p class="text-sm text-gray-500 mt-1">Monitor your business performance in real-time</p>
        </div>
        <div class="flex items-center space-x-3">
            <div class="flex items-center space-x-2 bg-white rounded-lg shadow-sm px-3 py-2 border border-gray-200">
                <i class="fas fa-calendar-alt text-gray-400 text-sm"></i>
                <select id="period" class="border-0 bg-transparent text-sm focus:ring-0">
                    <option value="today">Today</option>
                    <option value="week" selected>This Week</option>
                    <option value="month">This Month</option>
                    <option value="quarter">This Quarter</option>
                    <option value="year">This Year</option>
                </select>
            </div>
            <button onclick="refreshData()" class="p-2 bg-white rounded-lg shadow-sm border border-gray-200 hover:bg-gray-50 transition-colors">
                <i class="fas fa-sync text-gray-500"></i>
            </button>
            <a href="{{ route('admin.analytics.reports') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-file-alt mr-2"></i> View Reports
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Revenue</p>
                    <p class="text-xl font-bold text-gray-900" id="totalRevenue">$0.00</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
            <div class="mt-2 flex items-center text-xs">
                <span class="text-green-600" id="revenueGrowth">+0%</span>
                <span class="text-gray-400 ml-1">vs last period</span>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Visitors</p>
                    <p class="text-xl font-bold text-gray-900" id="totalVisitors">0</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="mt-2 flex items-center text-xs">
                <span class="text-blue-600" id="visitorGrowth">+0%</span>
                <span class="text-gray-400 ml-1">vs last period</span>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Active Subscriptions</p>
                    <p class="text-xl font-bold text-gray-900" id="activeSubscriptions">0</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                    <i class="fas fa-receipt"></i>
                </div>
            </div>
            <div class="mt-2 flex items-center text-xs">
                <span class="text-purple-600" id="subscriptionGrowth">+0%</span>
                <span class="text-gray-400 ml-1">vs last period</span>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Conversion Rate</p>
                    <p class="text-xl font-bold text-gray-900" id="conversionRate">0%</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
            <div class="mt-2 flex items-center text-xs">
                <span class="text-yellow-600" id="conversionGrowth">+0%</span>
                <span class="text-gray-400 ml-1">vs last period</span>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue Chart -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-gray-900">Revenue Overview</h3>
                <div class="flex items-center space-x-2">
                    <button onclick="setChartType('revenue', 'line')" class="text-xs text-primary-600 hover:text-primary-700 font-medium">Line</button>
                    <button onclick="setChartType('revenue', 'bar')" class="text-xs text-gray-500 hover:text-gray-700 font-medium">Bar</button>
                </div>
            </div>
            <div class="h-64">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Visitors Chart -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-gray-900">Visitor Traffic</h3>
                <div class="flex items-center space-x-2">
                    <button onclick="setChartType('visitors', 'line')" class="text-xs text-primary-600 hover:text-primary-700 font-medium">Line</button>
                    <button onclick="setChartType('visitors', 'bar')" class="text-xs text-gray-500 hover:text-gray-700 font-medium">Bar</button>
                </div>
            </div>
            <div class="h-64">
                <canvas id="visitorsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Bottom Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Subscriptions by Plan -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-sm font-medium text-gray-900 mb-4">Subscriptions by Plan</h3>
            <div class="h-48">
                <canvas id="planDistributionChart"></canvas>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-xl shadow-sm p-6 lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-gray-900">Recent Activity</h3>
                <a href="{{ route('admin.activities.index') }}" class="text-xs text-primary-600 hover:text-primary-700">
                    View All
                </a>
            </div>
            <div class="space-y-3" id="recentActivity">
                <div class="text-center text-gray-500 text-sm py-4">Loading activities...</div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let revenueChart, visitorsChart, planDistributionChart;
    let currentPeriod = 'week';
    let chartTypes = {
        revenue: 'line',
        visitors: 'line'
    };

    // ============================================
    // INITIALIZATION
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
        fetchAnalyticsData();
        setupPeriodSelector();
        setupAutoRefresh();
    });

    // ============================================
    // FETCH DATA
    // ============================================
    async function fetchAnalyticsData() {
        try {
            showLoading();
            
            const response = await fetch(`/admin/api/analytics/dashboard?period=${currentPeriod}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) throw new Error('Failed to fetch data');

            const data = await response.json();
            
            if (data.success) {
                updateStats(data.data.stats);
                updateCharts(data.data.charts);
                updateRecentActivity(data.data.activities);
            } else {
                showError(data.message || 'Failed to load analytics data');
            }
        } catch (error) {
            console.error('Error fetching analytics:', error);
            showError('Unable to load analytics data. Please try again.');
        } finally {
            hideLoading();
        }
    }

    // ============================================
    // UPDATE STATS
    // ============================================
    function updateStats(stats) {
        document.getElementById('totalRevenue').textContent = '$' + (stats.total_revenue || 0).toFixed(2);
        document.getElementById('totalVisitors').textContent = stats.total_visitors || 0;
        document.getElementById('activeSubscriptions').textContent = stats.active_subscriptions || 0;
        document.getElementById('conversionRate').textContent = (stats.conversion_rate || 0) + '%';
        
        document.getElementById('revenueGrowth').textContent = (stats.revenue_growth || 0) + '%';
        document.getElementById('visitorGrowth').textContent = (stats.visitor_growth || 0) + '%';
        document.getElementById('subscriptionGrowth').textContent = (stats.subscription_growth || 0) + '%';
        document.getElementById('conversionGrowth').textContent = (stats.conversion_growth || 0) + '%';
    }

    // ============================================
    // UPDATE CHARTS
    // ============================================
    function updateCharts(charts) {
        // Revenue Chart
        if (revenueChart) revenueChart.destroy();
        revenueChart = new Chart(document.getElementById('revenueChart'), {
            type: chartTypes.revenue,
            data: {
                labels: charts.revenue.labels || [],
                datasets: [{
                    label: 'Revenue',
                    data: charts.revenue.data || [],
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) { return '$' + value; }
                        }
                    }
                }
            }
        });

        // Visitors Chart
        if (visitorsChart) visitorsChart.destroy();
        visitorsChart = new Chart(document.getElementById('visitorsChart'), {
            type: chartTypes.visitors,
            data: {
                labels: charts.visitors.labels || [],
                datasets: [{
                    label: 'Visitors',
                    data: charts.visitors.data || [],
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Plan Distribution Chart
        if (planDistributionChart) planDistributionChart.destroy();
        planDistributionChart = new Chart(document.getElementById('planDistributionChart'), {
            type: 'doughnut',
            data: {
                labels: charts.plan_distribution?.labels || [],
                datasets: [{
                    data: charts.plan_distribution?.data || [],
                    backgroundColor: [
                        '#6366f1', '#8b5cf6', '#3b82f6', 
                        '#22c55e', '#f59e0b', '#ef4444'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 12,
                            font: { size: 11 }
                        }
                    }
                }
            }
        });
    }

    // ============================================
    // UPDATE RECENT ACTIVITY
    // ============================================
    function updateRecentActivity(activities) {
        const container = document.getElementById('recentActivity');
        
        if (!activities || activities.length === 0) {
            container.innerHTML = `
                <div class="text-center text-gray-500 text-sm py-4">No recent activity</div>
            `;
            return;
        }

        let html = '';
        activities.forEach(activity => {
            const colors = {
                'created': 'green',
                'updated': 'blue',
                'deleted': 'red',
                'viewed': 'gray',
                'logged_in': 'indigo',
                'logged_out': 'yellow'
            };
            const color = colors[activity.action] || 'gray';
            
            html += `
                <div class="flex items-center space-x-3 p-2 hover:bg-gray-50 rounded-lg transition-colors">
                    <div class="w-8 h-8 rounded-full bg-${color}-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas ${activity.icon || 'fa-circle'} text-${color}-600 text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-900 truncate">
                            <span class="font-medium">${activity.user_name || 'System'}</span>
                            ${activity.description}
                        </p>
                        <p class="text-xs text-gray-500">${activity.time_ago || activity.created_at}</p>
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;
    }

    // ============================================
    // PERIOD SELECTOR
    // ============================================
    function setupPeriodSelector() {
        document.getElementById('period').addEventListener('change', function() {
            currentPeriod = this.value;
            fetchAnalyticsData();
        });
    }

    // ============================================
    // CHART TYPE TOGGLE
    // ============================================
    function setChartType(chart, type) {
        chartTypes[chart] = type;
        fetchAnalyticsData();
    }

    // ============================================
    // REFRESH
    // ============================================
    function refreshData() {
        fetchAnalyticsData();
    }

    // ============================================
    // AUTO-REFRESH
    // ============================================
    function setupAutoRefresh() {
        setInterval(() => {
            fetchAnalyticsData();
        }, 60000); // Refresh every 60 seconds
    }

    // ============================================
    // LOADING STATES
    // ============================================
    function showLoading() {
        document.getElementById('totalRevenue').innerHTML = '<div class="animate-pulse h-6 w-24 bg-gray-200 rounded"></div>';
        document.getElementById('totalVisitors').innerHTML = '<div class="animate-pulse h-6 w-16 bg-gray-200 rounded"></div>';
        document.getElementById('activeSubscriptions').innerHTML = '<div class="animate-pulse h-6 w-16 bg-gray-200 rounded"></div>';
        document.getElementById('conversionRate').innerHTML = '<div class="animate-pulse h-6 w-16 bg-gray-200 rounded"></div>';
    }

    function hideLoading() {
        // Stats will be updated by updateStats()
    }

    function showError(message) {
        // You can implement a toast notification here
        console.error(message);
    }
</script>
@endpush

@push('styles')
<style>
    .card-hover {
        transition: all 0.2s ease;
    }
    .card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    }
</style>
@endpush
@endsection