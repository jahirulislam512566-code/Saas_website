<!-- admin/websites/analytics.blade.php -->
@extends('layout.app')

@section('title', 'Website Analytics')
@section('page-title', 'Website Analytics')
@section('page-subtitle', 'Track performance metrics for {{ $website->name }}')

@section('content')
<div x-data="websiteAnalytics()" x-init="init()">
    <!-- Date Range -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm mb-6">
        <div class="flex flex-wrap items-center gap-3">
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Period:</label>
                <select x-model="period" @change="updateAnalytics()"
                        class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    <option value="7d">Last 7 Days</option>
                    <option value="30d">Last 30 Days</option>
                    <option value="90d">Last 90 Days</option>
                    <option value="12m">Last 12 Months</option>
                    <option value="custom">Custom Range</option>
                </select>
            </div>

            <div x-show="period === 'custom'" class="flex items-center gap-2">
                <input type="date" x-model="date_from" 
                       class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                <span class="text-gray-500">to</span>
                <input type="date" x-model="date_to" 
                       class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
            </div>

            <button @click="updateAnalytics()" 
                    class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all">
                <i class="fas fa-sync mr-1.5"></i>Update
            </button>
            
            <button @click="exportAnalytics()" 
                    class="px-4 py-1.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all ml-auto">
                <i class="fas fa-download mr-1.5"></i>Export
            </button>
        </div>
    </div>

    <!-- Analytics Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Views</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatNumber(stats.views)">0</p>
            <div class="mt-1 text-sm text-green-600 dark:text-green-400">
                <i class="fas fa-arrow-up mr-1"></i> <span x-text="stats.views_growth + '%'"></span> vs previous
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Unique Visitors</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatNumber(stats.visitors)">0</p>
            <div class="mt-1 text-sm text-green-600 dark:text-green-400">
                <i class="fas fa-arrow-up mr-1"></i> <span x-text="stats.visitors_growth + '%'"></span> vs previous
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Bounce Rate</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="stats.bounce_rate + '%'">0%</p>
            <div class="mt-1 text-sm text-green-600 dark:text-green-400">
                <i class="fas fa-arrow-down mr-1"></i> <span x-text="stats.bounce_change + '%'"></span> vs previous
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Avg Session Duration</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="stats.avg_session + 's'">0s</p>
            <div class="mt-1 text-sm text-green-600 dark:text-green-400">
                <i class="fas fa-arrow-up mr-1"></i> <span x-text="stats.session_growth + '%'"></span> vs previous
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Traffic Overview</h4>
            <div class="h-64">
                <canvas x-ref="trafficChart"></canvas>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Traffic Sources</h4>
            <div class="h-64">
                <canvas x-ref="sourcesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Pages -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-200 dark:border-gray-800">
            <h4 class="font-semibold text-gray-900 dark:text-white">Top Pages</h4>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Page</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Views</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Unique Visitors</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Avg Time</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Bounce Rate</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @foreach($topPages as $page)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-4 py-3">
                                <span class="text-gray-900 dark:text-white font-medium">{{ $page['title'] }}</span>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $page['url'] }}</p>
                            </td>
                            <td class="px-4 py-3 text-right text-gray-900 dark:text-white">{{ number_format($page['views']) }}</td>
                            <td class="px-4 py-3 text-right text-gray-900 dark:text-white">{{ number_format($page['unique_visitors']) }}</td>
                            <td class="px-4 py-3 text-right text-gray-600 dark:text-gray-400">{{ $page['avg_time'] }}s</td>
                            <td class="px-4 py-3 text-right">
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                             {{ $page['bounce_rate'] < 40 ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 
                                                ($page['bounce_rate'] < 70 ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' : 
                                                'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400') }}">
                                    {{ $page['bounce_rate'] }}%
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Live Visitors -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden mt-6">
        <div class="p-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
            <h4 class="font-semibold text-gray-900 dark:text-white">Live Visitors</h4>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                <span class="text-sm text-gray-500 dark:text-gray-400" x-text="liveVisitors + ' online'"></span>
            </div>
        </div>
        <div class="p-4">
            <div class="flex flex-wrap gap-2">
                <template x-for="visitor in activeVisitors" :key="visitor.id">
                    <div class="flex items-center gap-2 px-3 py-1.5 bg-gray-50 dark:bg-gray-800 rounded-full">
                        <img :src="visitor.avatar" alt="" class="w-6 h-6 rounded-full">
                        <span class="text-sm text-gray-700 dark:text-gray-300" x-text="visitor.name"></span>
                        <span class="text-xs text-gray-500 dark:text-gray-400" x-text="visitor.page"></span>
                    </div>
                </template>
                <div x-show="activeVisitors.length === 0" class="text-gray-500 dark:text-gray-400 text-sm">
                    No active visitors
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function websiteAnalytics() {
    return {
        period: '30d',
        date_from: '',
        date_to: '',
        stats: @json($stats),
        topPages: @json($topPages),
        activeVisitors: @json($activeVisitors),
        liveVisitors: @json($liveVisitors),
        trafficChart: null,
        sourcesChart: null,
        
        init() {
            this.initCharts();
            this.startRealTimeUpdates();
        },
        
        initCharts() {
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#9ca3af' : '#4b5563';
            const gridColor = isDark ? '#1f2937' : '#e5e7eb';
            
            // Traffic Chart
            const ctx1 = this.$refs.trafficChart.getContext('2d');
            this.trafficChart = new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: @json($chartData['traffic']['labels']),
                    datasets: [
                        {
                            label: 'Views',
                            data: @json($chartData['traffic']['views']),
                            borderColor: '#4f46e5',
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Visitors',
                            data: @json($chartData['traffic']['visitors']),
                            borderColor: '#8b5cf6',
                            backgroundColor: 'rgba(139, 92, 246, 0.1)',
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
                            labels: { color: textColor }
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

            // Sources Chart
            const ctx2 = this.$refs.sourcesChart.getContext('2d');
            this.sourcesChart = new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: @json($chartData['sources']['labels']),
                    datasets: [{
                        data: @json($chartData['sources']['values']),
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
                                padding: 15
                            }
                        }
                    }
                }
            });
        },
        
        updateAnalytics() {
            const params = new URLSearchParams({
                period: this.period,
                date_from: this.date_from,
                date_to: this.date_to
            });
            window.location.href = `{{ route('admin.websites.analytics', $website) }}?${params.toString()}`;
        },
        
        exportAnalytics() {
            const params = new URLSearchParams({
                period: this.period,
                date_from: this.date_from,
                date_to: this.date_to
            });
            window.location.href = `{{ route('admin.websites.analytics.export', $website) }}?${params.toString()}`;
        },
        
        startRealTimeUpdates() {
            setInterval(() => {
                fetch(`{{ route('admin.websites.analytics.live', $website) }}`)
                    .then(response => response.json())
                    .then(data => {
                        this.activeVisitors = data.visitors;
                        this.liveVisitors = data.count;
                    })
                    .catch(error => console.error('Error fetching live visitors:', error));
            }, 30000);
        },
        
        formatNumber(value) {
            return value.toLocaleString();
        }
    }
}
</script>
@endpush