<!-- admin/analytics/traffic.blade.php -->
@extends('layout.app')

@section('title', 'Traffic Analytics')
@section('page-title', 'Traffic Analytics')
@section('page-subtitle', 'Website traffic, page views, and user behavior analysis')

@section('content')
<div x-data="trafficAnalytics()" x-init="init()">
    <!-- Traffic KPIs -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Visits</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatNumber(stats.visits)">0</p>
            <div class="mt-1 text-sm text-green-600 dark:text-green-400" x-text="'+' + stats.visits_growth + '%'"></div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Page Views</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatNumber(stats.page_views)">0</p>
            <div class="mt-1 text-sm text-green-600 dark:text-green-400" x-text="'+' + stats.page_views_growth + '%'"></div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Bounce Rate</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="stats.bounce_rate + '%'">0%</p>
            <div class="mt-1 text-sm text-green-600 dark:text-green-400" x-text="'↓ ' + stats.bounce_change + '%'"></div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Avg Session Duration</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="stats.avg_session + 'm'">0m</p>
            <div class="mt-1 text-sm text-green-600 dark:text-green-400" x-text="'+' + stats.session_growth + '%'"></div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Downloads</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatNumber(stats.downloads)">0</p>
            <div class="mt-1 text-sm text-green-600 dark:text-green-400" x-text="'+' + stats.downloads_growth + '%'"></div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Traffic Trends -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Traffic Trends</h4>
            <div class="h-64">
                <canvas x-ref="trafficTrendChart"></canvas>
            </div>
        </div>

        <!-- Traffic Sources -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Traffic Sources</h4>
            <div class="h-64">
                <canvas x-ref="sourcesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Page Views & Downloads -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Top Pages</h4>
            <div class="space-y-3 max-h-64 overflow-y-auto">
                <template x-for="page in topPages" :key="page.url">
                    <div class="flex items-center justify-between p-2 hover:bg-gray-50 dark:hover:bg-gray-800/50 rounded-lg transition-colors">
                        <div class="flex-1">
                            <p class="text-sm text-gray-900 dark:text-white font-medium" x-text="page.title"></p>
                            <p class="text-xs text-gray-500 dark:text-gray-400" x-text="page.url"></p>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="text-sm text-gray-900 dark:text-white" x-text="formatNumber(page.views)"></span>
                            <span class="text-xs text-gray-500 dark:text-gray-400" x-text="page.percentage + '%'"></span>
                            <div class="w-16 bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                                <div class="h-1.5 rounded-full bg-indigo-500" :style="'width: ' + page.percentage + '%'"></div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Popular Downloads</h4>
            <div class="space-y-3 max-h-64 overflow-y-auto">
                <template x-for="download in popularDownloads" :key="download.id">
                    <div class="flex items-center justify-between p-2 hover:bg-gray-50 dark:hover:bg-gray-800/50 rounded-lg transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center">
                                <i class="fas fa-file-pdf text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-900 dark:text-white font-medium" x-text="download.name"></p>
                                <p class="text-xs text-gray-500 dark:text-gray-400" x-text="download.size"></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-sm text-gray-900 dark:text-white" x-text="formatNumber(downloads.downloads)"></span>
                            <span class="text-xs text-gray-500 dark:text-gray-400" x-text="download.percentage + '%'"></span>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Real-time Visitors -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
            <h4 class="font-semibold text-gray-900 dark:text-white">Real-time Visitors</h4>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                <span class="text-sm text-gray-500 dark:text-gray-400" x-text="onlineVisitors + ' online'"></span>
            </div>
        </div>
        <div class="p-4">
            <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-2">
                <template x-for="visitor in activeVisitors" :key="visitor.id">
                    <div class="text-center p-2 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <img :src="visitor.avatar" alt="" class="w-10 h-10 rounded-full mx-auto mb-1">
                        <p class="text-xs text-gray-900 dark:text-white truncate" x-text="visitor.name"></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400" x-text="visitor.location"></p>
                        <span class="text-[10px] text-green-500" x-text="visitor.page"></span>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function trafficAnalytics() {
    return {
        stats: @json($stats),
        topPages: @json($topPages),
        popularDownloads: @json($popularDownloads),
        activeVisitors: @json($activeVisitors),
        onlineVisitors: @json($onlineVisitors),
        trafficTrendChart: null,
        sourcesChart: null,
        
        init() {
            this.initCharts();
            this.startRealTimeUpdates();
        },
        
        initCharts() {
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#9ca3af' : '#4b5563';
            const gridColor = isDark ? '#1f2937' : '#e5e7eb';
            
            // Traffic Trend Chart
            const ctx1 = this.$refs.trafficTrendChart.getContext('2d');
            this.trafficTrendChart = new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: @json($chartData['trend']['labels']),
                    datasets: [
                        {
                            label: 'Visits',
                            data: @json($chartData['trend']['visits']),
                            borderColor: '#4f46e5',
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Page Views',
                            data: @json($chartData['trend']['page_views']),
                            borderColor: '#8b5cf6',
                            backgroundColor: 'rgba(139, 92, 246, 0.1)',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Downloads',
                            data: @json($chartData['trend']['downloads']),
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
                type: 'bar',
                data: {
                    labels: @json($chartData['sources']['labels']),
                    datasets: [{
                        label: 'Visitors',
                        data: @json($chartData['sources']['values']),
                        backgroundColor: ['#4f46e5', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981', '#3b82f6']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
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
        },
        
        startRealTimeUpdates() {
            // Simulate real-time updates every 5 seconds
            setInterval(() => {
                this.onlineVisitors = Math.floor(Math.random() * 50) + 20;
                // Update active visitors randomly
                if (Math.random() > 0.7) {
                    this.activeVisitors = this.activeVisitors.map(v => ({
                        ...v,
                        page: ['Home', 'Dashboard', 'Products', 'Support', 'Blog'][Math.floor(Math.random() * 5)]
                    }));
                }
            }, 5000);
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