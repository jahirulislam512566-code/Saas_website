@extends('admin.layouts.admin')

@section('title', 'Traffic Report')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <a href="{{ route('admin.reports.index') }}" class="text-gray-500 hover:text-gray-700">Reports</a>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">Traffic Report</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Traffic Report</h1>
            <p class="text-sm text-gray-500 mt-1">Website traffic analysis and visitor insights</p>
        </div>
        <div class="flex items-center space-x-3">
            <button type="button" onclick="window.print()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-print mr-2"></i> Print
            </button>
            <button type="button" onclick="exportReport('traffic')" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-download mr-2"></i> Export
            </button>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm p-4">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                <select name="period" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Today</option>
                    <option value="week" {{ request('period') == 'week' ? 'selected' : '' }}>This Week</option>
                    <option value="month" {{ request('period') == 'month' ? 'selected' : '' }}>This Month</option>
                    <option value="quarter" {{ request('period') == 'quarter' ? 'selected' : '' }}>This Quarter</option>
                    <option value="year" {{ request('period') == 'year' ? 'selected' : '' }}>This Year</option>
                    <option value="custom" {{ request('period') == 'custom' ? 'selected' : '' }}>Custom</option>
                </select>
            </div>
            
            <div id="custom-date-range" style="{{ request('period') == 'custom' ? '' : 'display: none;' }}">
                <label class="block text-sm font-medium text-gray-700 mb-1">From</label>
                <input type="date" name="from" value="{{ request('from') }}" 
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
            </div>
            
            <div id="custom-date-end" style="{{ request('period') == 'custom' ? '' : 'display: none;' }}">
                <label class="block text-sm font-medium text-gray-700 mb-1">To</label>
                <input type="date" name="to" value="{{ request('to') }}" 
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    <i class="fas fa-search mr-2"></i> Generate
                </button>
                <a href="{{ route('admin.reports.traffic') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-times mr-2"></i> Reset
                </a>
            </div>
        </form>
    </div>
    
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-primary-500">
            <p class="text-sm text-gray-500">Total Visits</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($reportData['total_visits'] ?? 0) }}</p>
            <span class="text-xs {{ ($reportData['visits_growth'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                <i class="fas fa-{{ ($reportData['visits_growth'] ?? 0) >= 0 ? 'arrow-up' : 'arrow-down' }} mr-1"></i>
                {{ abs($reportData['visits_growth'] ?? 0) }}% from previous period
            </span>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-green-500">
            <p class="text-sm text-gray-500">Unique Visitors</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($reportData['unique_visitors'] ?? 0) }}</p>
            <span class="text-xs {{ ($reportData['unique_growth'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                <i class="fas fa-{{ ($reportData['unique_growth'] ?? 0) >= 0 ? 'arrow-up' : 'arrow-down' }} mr-1"></i>
                {{ abs($reportData['unique_growth'] ?? 0) }}% from previous period
            </span>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-blue-500">
            <p class="text-sm text-gray-500">Page Views</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($reportData['page_views'] ?? 0) }}</p>
            <span class="text-xs {{ ($reportData['page_views_growth'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                <i class="fas fa-{{ ($reportData['page_views_growth'] ?? 0) >= 0 ? 'arrow-up' : 'arrow-down' }} mr-1"></i>
                {{ abs($reportData['page_views_growth'] ?? 0) }}% from previous period
            </span>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-yellow-500">
            <p class="text-sm text-gray-500">Avg. Session Duration</p>
            <p class="text-2xl font-bold text-gray-900">{{ $reportData['avg_duration'] ?? '0:00' }}</p>
            <span class="text-xs {{ ($reportData['duration_growth'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                <i class="fas fa-{{ ($reportData['duration_growth'] ?? 0) >= 0 ? 'arrow-up' : 'arrow-down' }} mr-1"></i>
                {{ abs($reportData['duration_growth'] ?? 0) }}% from previous period
            </span>
        </div>
    </div>
    
    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Traffic Overview</h3>
            <div class="h-72" x-data="trafficChart()">
                <canvas id="trafficChart"></canvas>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Traffic Sources</h3>
            <div class="h-72" x-data="sourcesChart()">
                <canvas id="sourcesChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Top Pages -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Top Pages</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Page</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Views</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unique Visitors</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg. Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bounce Rate</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($topPages ?? [] as $page)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $page['url'] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ number_format($page['views'] ?? 0) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ number_format($page['unique'] ?? 0) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $page['avg_time'] ?? '0:00' }}</td>
                            <td class="px-6 py-4">
                                <span class="text-sm {{ ($page['bounce_rate'] ?? 0) < 50 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ number_format($page['bounce_rate'] ?? 0, 1) }}%
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-file text-4xl text-gray-300 mb-3 block"></i>
                                <p class="text-lg font-medium">No page data available</p>
                                <p class="text-sm mt-1">Try adjusting your date range or filters</p>
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
    document.querySelector('select[name="period"]').addEventListener('change', function() {
        const customDateRange = document.getElementById('custom-date-range');
        const customDateEnd = document.getElementById('custom-date-end');
        if (this.value === 'custom') {
            customDateRange.style.display = 'block';
            customDateEnd.style.display = 'block';
        } else {
            customDateRange.style.display = 'none';
            customDateEnd.style.display = 'none';
        }
    });
    
    function trafficChart() {
        return {
            init() {
                const ctx = document.getElementById('trafficChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($chartData['traffic_labels'] ?? []) !!},
                        datasets: [
                            {
                                label: 'Visits',
                                data: {!! json_encode($chartData['visits_data'] ?? []) !!},
                                borderColor: '#6366f1',
                                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                                fill: true,
                                tension: 0.4
                            },
                            {
                                label: 'Unique Visitors',
                                data: {!! json_encode($chartData['unique_data'] ?? []) !!},
                                borderColor: '#10b981',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
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
                                position: 'top',
                                labels: {
                                    usePointStyle: true,
                                    padding: 20
                                }
                            }
                        }
                    }
                });
            }
        }
    }
    
    function sourcesChart() {
        return {
            init() {
                const ctx = document.getElementById('sourcesChart').getContext('2d');
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Direct', 'Search', 'Social Media', 'Referral', 'Email'],
                        datasets: [{
                            data: {!! json_encode($chartData['sources_data'] ?? [20, 30, 25, 15, 10]) !!},
                            backgroundColor: ['#6366f1', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981'],
                            borderWidth: 2,
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
                                    pointStyle: 'circle'
                                }
                            }
                        }
                    }
                });
            }
        }
    }
    
    function exportReport(type) {
        const period = document.querySelector('select[name="period"]').value;
        const from = document.querySelector('input[name="from"]')?.value || '';
        const to = document.querySelector('input[name="to"]')?.value || '';
        window.location.href = `{{ route('admin.reports.export') }}?type=${type}&period=${period}&from=${from}&to=${to}`;
    }
</script>
@endpush
@endsection