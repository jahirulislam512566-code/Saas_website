{{-- resources/views/admin/websites/analytics.blade.php --}}
@extends('admin.layouts.admin')

@section('title', $website->name . ' - Analytics')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <a href="{{ route('admin.websites.index') }}" class="text-gray-500 hover:text-gray-700">Websites</a>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <a href="{{ route('admin.websites.show', $website) }}" class="text-gray-500 hover:text-gray-700">{{ $website->name }}</a>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">Analytics</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Analytics - {{ $website->name }}</h1>
            <p class="text-sm text-gray-500 mt-1">Track website performance and visitor metrics</p>
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
            <a href="{{ route('admin.websites.show', $website) }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Back
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
            <p class="text-sm text-gray-500">Page Views</p>
            <p class="text-xl font-bold text-green-600" id="pageViews">0</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Avg. Time on Site</p>
            <p class="text-xl font-bold text-purple-600" id="avgTime">0m</p>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-gray-900">Visitor Trends</h3>
                <button onclick="setChartType('trend', 'line')" class="text-xs text-primary-600 hover:text-primary-700 font-medium">Line</button>
                <button onclick="setChartType('trend', 'bar')" class="text-xs text-gray-500 hover:text-gray-700 font-medium">Bar</button>
            </div>
            <div class="h-64">
                <canvas id="visitorTrendChart"></canvas>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-sm font-medium text-gray-900 mb-4">Traffic Sources</h3>
            <div class="h-64">
                <canvas id="trafficSourceChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Pages -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-sm font-medium text-gray-900">Top Pages</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Page</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Views</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unique Visitors</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Avg. Time</th>
                    </tr>
                </thead>
                <tbody id="topPagesBody">
                    <tr><td colspan="4" class="px-6 py-8 text-center text-gray-500">Loading data...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let visitorChart, trafficChart;
    let currentPeriod = 'week';
    let chartTypes = {
        trend: 'line'
    };

    document.addEventListener('DOMContentLoaded', function() {
        fetchAnalyticsData();
        setupPeriodSelector();
        setupAutoRefresh();
    });

    async function fetchAnalyticsData() {
        try {
            showLoading();
            
            const response = await fetch(`/admin/api/websites/{{ $website->id }}/analytics?period=${currentPeriod}`, {
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
                updateTopPages(data.data.top_pages);
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

    function updateStats(stats) {
        document.getElementById('totalVisitors').textContent = stats.total_visitors || 0;
        document.getElementById('uniqueVisitors').textContent = stats.unique_visitors || 0;
        document.getElementById('pageViews').textContent = stats.page_views || 0;
        document.getElementById('avgTime').textContent = stats.avg_time || '0m';
    }

    function updateCharts(charts) {
        // Visitor Trend
        if (visitorChart) visitorChart.destroy();
        visitorChart = new Chart(document.getElementById('visitorTrendChart'), {
            type: chartTypes.trend,
            data: {
                labels: charts.trend.labels || [],
                datasets: [{
                    label: 'Visitors',
                    data: charts.trend.data || [],
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
                scales: { y: { beginAtZero: true } }
            }
        });

        // Traffic Sources
        if (trafficChart) trafficChart.destroy();
        trafficChart = new Chart(document.getElementById('trafficSourceChart'), {
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

    function updateTopPages(pages) {
        const tbody = document.getElementById('topPagesBody');
        if (!pages || pages.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" class="px-6 py-8 text-center text-gray-500">No data available</td></tr>';
            return;
        }

        let html = '';
        pages.forEach(page => {
            html += `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">${page.title}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">${page.views}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">${page.unique_visitors}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">${page.avg_time || 'N/A'}</td>
                </tr>
            `;
        });
        tbody.innerHTML = html;
    }

    function setupPeriodSelector() {
        document.getElementById('period').addEventListener('change', function() {
            currentPeriod = this.value;
            fetchAnalyticsData();
        });
    }

    function setupAutoRefresh() {
        setInterval(() => {
            fetchAnalyticsData();
        }, 60000);
    }

    function setChartType(chart, type) {
        chartTypes[chart] = type;
        fetchAnalyticsData();
    }

    function refreshData() {
        fetchAnalyticsData();
    }

    function showLoading() {
        // Show loading states
    }

    function hideLoading() {
        // Hide loading states
    }

    function showError(message) {
        const tbody = document.getElementById('topPagesBody');
        tbody.innerHTML = `
            <tr>
                <td colspan="4" class="px-6 py-8 text-center text-red-500">
                    <i class="fas fa-exclamation-circle text-3xl mb-3 block"></i>
                    <p class="text-lg font-medium">${message}</p>
                    <button onclick="fetchAnalyticsData()" class="mt-4 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                        <i class="fas fa-redo mr-2"></i> Retry
                    </button>
                </td>
            </tr>
        `;
    }
</script>
@endpush
@endsection