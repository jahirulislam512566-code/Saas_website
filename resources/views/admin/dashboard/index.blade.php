{{-- resources/views/admin/dashboard/index.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- ===== WELCOME SECTION ===== -->
    <div class="bg-linear-to-r from-primary-600 to-primary-800 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 ">Welcome back, {{ auth()->user()->name }}! 👋</h1>
                <p class="text-slate-800 mt-1">Here's what's happening with your business today</p>
            </div>
            <div class="flex items-center space-x-3">
                <span class="text-sm text-primary-200">
                    <i class="far fa-calendar-alt mr-1"></i>
                    {{ now()->format('l, F j, Y') }}
                </span>
                <button onclick="refreshDashboard()" 
                        class="px-3 py-1.5 bg-white/20 hover:bg-white/30 rounded-lg transition-colors text-sm">
                    <i class="fas fa-sync"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- ===== STATS CARDS ===== -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-900" id="totalRevenue">${{ number_format($stats['total_revenue'] ?? 0, 2) }}</p>
                    <span class="text-xs text-green-600">
                        <i class="fas fa-arrow-up mr-1"></i> +{{ $stats['revenue_growth'] ?? 0 }}%
                    </span>
                </div>
                <div class="w-12 h-12 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Users</p>
                    <p class="text-2xl font-bold text-gray-900" id="totalUsers">{{ number_format($stats['total_users'] ?? 0) }}</p>
                    <span class="text-xs text-blue-600">
                        <i class="fas fa-arrow-up mr-1"></i> +{{ $stats['user_growth'] ?? 0 }}%
                    </span>
                </div>
                <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Active Subscriptions</p>
                    <p class="text-2xl font-bold text-gray-900" id="activeSubscriptions">{{ number_format($stats['active_subscriptions'] ?? 0) }}</p>
                    <span class="text-xs text-purple-600">
                        <i class="fas fa-arrow-up mr-1"></i> +{{ $stats['subscription_growth'] ?? 0 }}%
                    </span>
                </div>
                <div class="w-12 h-12 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                    <i class="fas fa-receipt text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Churn Rate</p>
                    <p class="text-2xl font-bold text-gray-900" id="churnRate">{{ $stats['churn_rate'] ?? 0 }}%</p>
                    <span class="text-xs text-red-600">
                        <i class="fas fa-arrow-down mr-1"></i> -{{ $stats['churn_change'] ?? 0 }}%
                    </span>
                </div>
                <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center">
                    <i class="fas fa-chart-line text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== CHARTS SECTION ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue Chart -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-gray-900">Revenue Overview</h3>
                <div class="flex items-center space-x-2">
                    <button onclick="setChartPeriod('week')" 
                            class="text-xs px-2 py-1 rounded {{ $chartPeriod == 'week' ? 'bg-primary-100 text-primary-700' : 'text-gray-500 hover:text-gray-700' }}">
                        Week
                    </button>
                    <button onclick="setChartPeriod('month')" 
                            class="text-xs px-2 py-1 rounded {{ $chartPeriod == 'month' ? 'bg-primary-100 text-primary-700' : 'text-gray-500 hover:text-gray-700' }}">
                        Month
                    </button>
                    <button onclick="setChartPeriod('year')" 
                            class="text-xs px-2 py-1 rounded {{ $chartPeriod == 'year' ? 'bg-primary-100 text-primary-700' : 'text-gray-500 hover:text-gray-700' }}">
                        Year
                    </button>
                    <button onclick="toggleChartType('revenue')" 
                            class="text-xs text-gray-400 hover:text-gray-600">
                        <i class="fas fa-chart-bar"></i>
                    </button>
                </div>
            </div>
            <div class="h-64">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- User Growth Chart -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-gray-900">User Growth</h3>
                <div class="flex items-center space-x-2">
                    <button onclick="setChartPeriod('week')" 
                            class="text-xs px-2 py-1 rounded {{ $chartPeriod == 'week' ? 'bg-primary-100 text-primary-700' : 'text-gray-500 hover:text-gray-700' }}">
                        Week
                    </button>
                    <button onclick="setChartPeriod('month')" 
                            class="text-xs px-2 py-1 rounded {{ $chartPeriod == 'month' ? 'bg-primary-100 text-primary-700' : 'text-gray-500 hover:text-gray-700' }}">
                        Month
                    </button>
                    <button onclick="setChartPeriod('year')" 
                            class="text-xs px-2 py-1 rounded {{ $chartPeriod == 'year' ? 'bg-primary-100 text-primary-700' : 'text-gray-500 hover:text-gray-700' }}">
                        Year
                    </button>
                    <button onclick="toggleChartType('users')" 
                            class="text-xs text-gray-400 hover:text-gray-600">
                        <i class="fas fa-chart-bar"></i>
                    </button>
                </div>
            </div>
            <div class="h-64">
                <canvas id="userGrowthChart"></canvas>
            </div>
        </div>
    </div>

    <!-- ===== MIDDLE SECTION ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Plan Distribution -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-sm font-medium text-gray-900 mb-4">Plan Distribution</h3>
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
                @forelse($activities as $activity)
                    <div class="flex items-center space-x-3 p-2 hover:bg-gray-50 rounded-lg transition-colors">
                        <div class="w-8 h-8 rounded-full bg-{{ $activity->color ?? 'gray' }}-100 flex items-center justify-center flex-shrink-0">
                            <i class="fas {{ $activity->icon ?? 'fa-circle' }} text-{{ $activity->color ?? 'gray' }}-600 text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900 truncate">
                                <span class="font-medium">{{ $activity->user_name ?? 'System' }}</span>
                                {{ $activity->description }}
                            </p>
                            <p class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="text-xs text-gray-400">{{ $activity->action }}</span>
                    </div>
                @empty
                    <div class="text-center text-gray-500 text-sm py-4">No recent activity</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- ===== BOTTOM SECTION ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Subscriptions -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-sm font-medium text-gray-900">Recent Subscriptions</h3>
                <a href="{{ route('admin.subscriptions.index') }}" class="text-xs text-primary-600 hover:text-primary-700">
                    View All
                </a>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recentSubscriptions as $subscription)
                    <div class="px-6 py-3 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $subscription->user->name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500">{{ $subscription->plan->name ?? 'N/A' }}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $subscription->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($subscription->status) }}
                                </span>
                                <span class="text-xs text-gray-400">{{ $subscription->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-4 text-center text-gray-500">No recent subscriptions</div>
                @endforelse
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-sm font-medium text-gray-900 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('admin.posts.create') }}" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors group">
                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">New Post</p>
                        <p class="text-xs text-gray-500">Create content</p>
                    </div>
                </a>
                <a href="{{ route('admin.users.create') }}" class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors group">
                    <div class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center group-hover:bg-green-200 transition-colors">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">New User</p>
                        <p class="text-xs text-gray-500">Add user</p>
                    </div>
                </a>
                <a href="{{ route('admin.plans.create') }}" class="flex items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors group">
                    <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                        <i class="fas fa-crown"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">New Plan</p>
                        <p class="text-xs text-gray-500">Add plan</p>
                    </div>
                </a>
                <a href="{{ route('admin.websites.create') }}" class="flex items-center p-3 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors group">
                    <div class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center group-hover:bg-orange-200 transition-colors">
                        <i class="fas fa-globe"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">New Website</p>
                        <p class="text-xs text-gray-500">Add website</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let revenueChart, userGrowthChart, planDistributionChart;
    let currentPeriod = 'month';
    let chartTypes = {
        revenue: 'line',
        users: 'line'
    };

    // ============================================
    // INITIALIZATION
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
        fetchDashboardData();
        setupAutoRefresh();
        setupKeyboardShortcuts();
    });

    // ============================================
    // FETCH DATA
    // ============================================
    async function fetchDashboardData() {
        try {
            showLoading();
            
            const response = await fetch(`/admin/api/dashboard?period=${currentPeriod}`, {
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
                updateActivities(data.data.activities);
                updateSubscriptions(data.data.subscriptions);
            } else {
                showError(data.message || 'Failed to load dashboard data');
            }
        } catch (error) {
            console.error('Error fetching dashboard data:', error);
            showError('Unable to load dashboard data. Please try again.');
        } finally {
            hideLoading();
        }
    }

    // ============================================
    // UPDATE STATS
    // ============================================
    function updateStats(stats) {
        document.getElementById('totalRevenue').textContent = '$' + (stats.total_revenue || 0).toFixed(2);
        document.getElementById('totalUsers').textContent = (stats.total_users || 0).toLocaleString();
        document.getElementById('activeSubscriptions').textContent = (stats.active_subscriptions || 0).toLocaleString();
        document.getElementById('churnRate').textContent = (stats.churn_rate || 0) + '%';
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
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: function(v) { return '$' + v; } }
                    }
                }
            }
        });

        // User Growth Chart
        if (userGrowthChart) userGrowthChart.destroy();
        userGrowthChart = new Chart(document.getElementById('userGrowthChart'), {
            type: chartTypes.users,
            data: {
                labels: charts.users.labels || [],
                datasets: [{
                    label: 'Users',
                    data: charts.users.data || [],
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });

        // Plan Distribution Chart
        if (planDistributionChart) planDistributionChart.destroy();
        planDistributionChart = new Chart(document.getElementById('planDistributionChart'), {
            type: 'doughnut',
            data: {
                labels: charts.plan_distribution.labels || [],
                datasets: [{
                    data: charts.plan_distribution.data || [],
                    backgroundColor: ['#6366f1', '#8b5cf6', '#3b82f6', '#22c55e', '#f59e0b', '#ef4444']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 12, padding: 12, font: { size: 11 } }
                    }
                }
            }
        });
    }

    // ============================================
    // UPDATE ACTIVITIES
    // ============================================
    function updateActivities(activities) {
        const container = document.getElementById('recentActivity');
        if (!activities || activities.length === 0) {
            container.innerHTML = '<div class="text-center text-gray-500 text-sm py-4">No recent activity</div>';
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
    // UPDATE SUBSCRIPTIONS
    // ============================================
    function updateSubscriptions(subscriptions) {
        // Subscriptions are rendered server-side, but we can update stats
        if (subscriptions && subscriptions.length > 0) {
            // Update any subscription-related stats if needed
        }
    }

    // ============================================
    // CHART CONTROLS
    // ============================================
    function setChartPeriod(period) {
        currentPeriod = period;
        fetchDashboardData();
    }

    function toggleChartType(chart) {
        chartTypes[chart] = chartTypes[chart] === 'line' ? 'bar' : 'line';
        fetchDashboardData();
    }

    // ============================================
    // REFRESH
    // ============================================
    function refreshDashboard() {
        fetchDashboardData();
    }

    // ============================================
    // AUTO-REFRESH
    // ============================================
    function setupAutoRefresh() {
        setInterval(() => {
            fetchDashboardData();
        }, 60000); // Refresh every 60 seconds
    }

    // ============================================
    // KEYBOARD SHORTCUTS
    // ============================================
    function setupKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            // Ctrl+R to refresh
            if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
                e.preventDefault();
                refreshDashboard();
            }
        });
    }

    // ============================================
    // LOADING STATES
    // ============================================
    function showLoading() {
        // Add loading indicators if needed
    }

    function hideLoading() {
        // Remove loading indicators
    }

    function showError(message) {
        // Show error toast
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