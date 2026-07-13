{{-- resources/views/admin/analytics/subscriptions.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Subscription Analytics')

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
            <span class="text-gray-500">Subscriptions</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Subscription Analytics</h1>
            <p class="text-sm text-gray-500 mt-1">Monitor subscription growth and churn metrics</p>
        </div>
        <div class="flex items-center space-x-3">
            <div class="flex items-center space-x-2 bg-white rounded-lg shadow-sm px-3 py-2 border border-gray-200">
                <i class="fas fa-calendar-alt text-gray-400 text-sm"></i>
                <select id="period" class="border-0 bg-transparent text-sm focus:ring-0">
                    <option value="week">This Week</option>
                    <option value="month" selected>This Month</option>
                    <option value="quarter">This Quarter</option>
                    <option value="year">This Year</option>
                </select>
            </div>
            <a href="{{ route('admin.analytics.export', 'subscriptions') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                <i class="fas fa-download mr-2"></i> Export
            </a>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Active Subscriptions</p>
            <p class="text-xl font-bold text-gray-900" id="activeSubscriptions">0</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">New (This Month)</p>
            <p class="text-xl font-bold text-green-600" id="newSubscriptions">0</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Churned</p>
            <p class="text-xl font-bold text-red-600" id="churnedSubscriptions">0</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Churn Rate</p>
            <p class="text-xl font-bold text-orange-600" id="churnRate">0%</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-sm font-medium text-gray-900 mb-4">Subscription Growth</h3>
            <div class="h-64"><canvas id="subscriptionGrowthChart"></canvas></div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-sm font-medium text-gray-900 mb-4">Plan Distribution</h3>
            <div class="h-64"><canvas id="planDistributionChart"></canvas></div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-sm font-medium text-gray-900">Recent Subscriptions</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Plan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Started</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Expires</th>
                    </tr>
                </thead>
                <tbody id="subscriptionsTableBody">
                    <tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">Loading subscriptions...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let growthChart, distributionChart;

    document.addEventListener('DOMContentLoaded', function() {
        fetchSubscriptionsData();
        setupPeriodSelector();
    });

    async function fetchSubscriptionsData() {
        try {
            const period = document.getElementById('period').value;
            const response = await fetch(`/admin/api/analytics/subscriptions?period=${period}`);
            const data = await response.json();

            if (data.success) {
                updateStats(data.data.stats);
                updateCharts(data.data.charts);
                updateTable(data.data.subscriptions);
            }
        } catch (error) {
            console.error('Error fetching subscriptions data:', error);
        }
    }

    function updateStats(stats) {
        document.getElementById('activeSubscriptions').textContent = stats.active || 0;
        document.getElementById('newSubscriptions').textContent = stats.new || 0;
        document.getElementById('churnedSubscriptions').textContent = stats.churned || 0;
        document.getElementById('churnRate').textContent = (stats.churn_rate || 0) + '%';
    }

    function updateCharts(charts) {
        if (growthChart) growthChart.destroy();
        growthChart = new Chart(document.getElementById('subscriptionGrowthChart'), {
            type: 'line',
            data: {
                labels: charts.growth.labels || [],
                datasets: [
                    {
                        label: 'New',
                        data: charts.growth.new || [],
                        borderColor: '#22c55e',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Churned',
                        data: charts.growth.churned || [],
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        fill: true,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'top' } },
                scales: { y: { beginAtZero: true } }
            }
        });

        if (distributionChart) distributionChart.destroy();
        distributionChart = new Chart(document.getElementById('planDistributionChart'), {
            type: 'doughnut',
            data: {
                labels: charts.distribution.labels || [],
                datasets: [{
                    data: charts.distribution.data || [],
                    backgroundColor: ['#6366f1', '#8b5cf6', '#3b82f6', '#22c55e', '#f59e0b']
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

    function updateTable(subscriptions) {
        const tbody = document.getElementById('subscriptionsTableBody');
        if (!subscriptions || subscriptions.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">No subscriptions found</td></tr>';
            return;
        }

        let html = '';
        subscriptions.forEach(s => {
            const statusColors = {
                'active': 'bg-green-100 text-green-800',
                'trialing': 'bg-blue-100 text-blue-800',
                'past_due': 'bg-orange-100 text-orange-800',
                'canceled': 'bg-red-100 text-red-800',
                'unpaid': 'bg-red-100 text-red-800'
            };
            const color = statusColors[s.status] || 'bg-gray-100 text-gray-800';

            html += `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 text-sm text-gray-900">${s.user_name}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">${s.plan_name}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${color}">
                            ${s.status}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">$${s.amount}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">${s.started}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">${s.expires || 'N/A'}</td>
                </tr>
            `;
        });
        tbody.innerHTML = html;
    }

    function setupPeriodSelector() {
        document.getElementById('period').addEventListener('change', fetchSubscriptionsData);
    }
</script>
@endpush
@endsection