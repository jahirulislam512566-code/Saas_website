{{-- resources/views/admin/analytics/visitors.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Visitors Analytics')

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
            <span class="text-gray-500">Visitors</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Visitors Analytics</h1>
            <p class="text-sm text-gray-500 mt-1">Track visitor behavior and engagement metrics</p>
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
            <button onclick="window.location.reload()" class="p-2 bg-white rounded-lg shadow-sm border border-gray-200 hover:bg-gray-50">
                <i class="fas fa-sync text-gray-500"></i>
            </button>
            <a href="{{ route('admin.analytics.export', 'visitors') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                <i class="fas fa-download mr-2"></i> Export
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Total Visitors</p>
            <p class="text-xl font-bold text-gray-900" id="totalVisitors">0</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Unique Visitors</p>
            <p class="text-xl font-bold text-blue-600" id="uniqueVisitors">0</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Avg. Session Duration</p>
            <p class="text-xl font-bold text-green-600" id="avgSession">0m</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Bounce Rate</p>
            <p class="text-xl font-bold text-red-600" id="bounceRate">0%</p>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-sm font-medium text-gray-900 mb-4">Visitor Trends</h3>
            <div class="h-64">
                <canvas id="visitorTrendsChart"></canvas>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-sm font-medium text-gray-900 mb-4">Traffic Sources</h3>
            <div class="h-64">
                <canvas id="trafficSourcesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Visitor Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-sm font-medium text-gray-900">Recent Visitors</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Visitor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">IP Address</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pages Viewed</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Duration</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Last Visit</th>
                    </tr>
                </thead>
                <tbody id="visitorsTableBody">
                    <tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">Loading visitors...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let visitorTrendsChart, trafficSourcesChart;

    document.addEventListener('DOMContentLoaded', function() {
        fetchVisitorsData();
        setupPeriodSelector();
    });

    async function fetchVisitorsData() {
        try {
            const period = document.getElementById('period').value;
            const response = await fetch(`/admin/api/analytics/visitors?period=${period}`);
            const data = await response.json();

            if (data.success) {
                updateStats(data.data.stats);
                updateCharts(data.data.charts);
                updateTable(data.data.visitors);
            }
        } catch (error) {
            console.error('Error fetching visitors data:', error);
        }
    }

    function updateStats(stats) {
        document.getElementById('totalVisitors').textContent = stats.total || 0;
        document.getElementById('uniqueVisitors').textContent = stats.unique || 0;
        document.getElementById('avgSession').textContent = stats.avg_session || '0m';
        document.getElementById('bounceRate').textContent = (stats.bounce_rate || 0) + '%';
    }

    function updateCharts(charts) {
        // Visitor Trends
        if (visitorTrendsChart) visitorTrendsChart.destroy();
        visitorTrendsChart = new Chart(document.getElementById('visitorTrendsChart'), {
            type: 'line',
            data: {
                labels: charts.trends.labels || [],
                datasets: [{
                    label: 'Visitors',
                    data: charts.trends.data || [],
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

        // Traffic Sources
        if (trafficSourcesChart) trafficSourcesChart.destroy();
        trafficSourcesChart = new Chart(document.getElementById('trafficSourcesChart'), {
            type: 'doughnut',
            data: {
                labels: charts.sources.labels || [],
                datasets: [{
                    data: charts.sources.data || [],
                    backgroundColor: ['#6366f1', '#3b82f6', '#22c55e', '#f59e0b', '#ef4444']
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

    function updateTable(visitors) {
        const tbody = document.getElementById('visitorsTableBody');
        if (!visitors || visitors.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">No visitors found</td></tr>';
            return;
        }

        let html = '';
        visitors.forEach(visitor => {
            html += `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 text-sm text-gray-900">${visitor.name || 'Guest'}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">${visitor.ip || 'N/A'}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">${visitor.location || 'Unknown'}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">${visitor.pages_viewed || 0}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">${visitor.duration || '0m'}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">${visitor.last_visit || 'N/A'}</td>
                </tr>
            `;
        });
        tbody.innerHTML = html;
    }

    function setupPeriodSelector() {
        document.getElementById('period').addEventListener('change', fetchVisitorsData);
    }
</script>
@endpush
@endsection