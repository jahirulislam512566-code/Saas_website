<!-- admin/ai/dashboard.blade.php -->
@extends('layout.app')

@section('title', 'AI Dashboard')
@section('page-title', 'AI Tools')
@section('page-subtitle', 'Leverage artificial intelligence to boost productivity')

@section('content')
<div x-data="aiDashboard()" x-init="init()">
    <!-- AI Usage Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total AI Requests</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatNumber(stats.total_requests)">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/20 flex items-center justify-center">
                    <i class="fas fa-robot text-indigo-600 dark:text-indigo-400"></i>
                </div>
            </div>
            <div class="mt-2 flex items-center gap-2 text-sm text-green-600 dark:text-green-400">
                <i class="fas fa-arrow-up"></i> <span x-text="stats.requests_growth + '%'"></span> vs last month
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Tokens Used</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatNumber(stats.tokens_used)">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/20 flex items-center justify-center">
                    <i class="fas fa-code text-purple-600 dark:text-purple-400"></i>
                </div>
            </div>
            <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                <span x-text="stats.tokens_remaining.toLocaleString()"></span> tokens remaining
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Active Users</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatNumber(stats.active_users)">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/20 flex items-center justify-center">
                    <i class="fas fa-users text-green-600 dark:text-green-400"></i>
                </div>
            </div>
            <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                <span x-text="stats.total_users"></span> total users
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Avg Response Time</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="stats.avg_response_time + 'ms'">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-yellow-100 dark:bg-yellow-900/20 flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600 dark:text-yellow-400"></i>
                </div>
            </div>
            <div class="mt-2 flex items-center gap-2 text-sm text-green-600 dark:text-green-400">
                <i class="fas fa-arrow-down"></i> <span x-text="stats.response_improvement + '%'"></span> faster
            </div>
        </div>
    </div>

    <!-- AI Tools Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <a href="{{ route('admin.ai.writer') }}" 
           class="group bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm hover:shadow-lg transition-all hover:border-indigo-300 dark:hover:border-indigo-700">
            <div class="flex flex-col items-center text-center">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/20 group-hover:scale-110 transition-transform">
                    <i class="fas fa-pen-fancy text-xl text-white"></i>
                </div>
                <h4 class="mt-3 font-semibold text-gray-900 dark:text-white">AI Writer</h4>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Generate high-quality content</p>
                <span class="mt-2 text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Popular</span>
            </div>
        </a>

        <a href="{{ route('admin.ai.seo') }}" 
           class="group bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm hover:shadow-lg transition-all hover:border-indigo-300 dark:hover:border-indigo-700">
            <div class="flex flex-col items-center text-center">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center shadow-lg shadow-blue-500/20 group-hover:scale-110 transition-transform">
                    <i class="fas fa-magnifying-glass-chart text-xl text-white"></i>
                </div>
                <h4 class="mt-3 font-semibold text-gray-900 dark:text-white">SEO Generator</h4>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Optimize content for search</p>
                <span class="mt-2 text-xs px-2 py-0.5 rounded-full bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400">New</span>
            </div>
        </a>

        <a href="{{ route('admin.ai.image') }}" 
           class="group bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm hover:shadow-lg transition-all hover:border-indigo-300 dark:hover:border-indigo-700">
            <div class="flex flex-col items-center text-center">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-pink-500 to-rose-600 flex items-center justify-center shadow-lg shadow-pink-500/20 group-hover:scale-110 transition-transform">
                    <i class="fas fa-image text-xl text-white"></i>
                </div>
                <h4 class="mt-3 font-semibold text-gray-900 dark:text-white">Image Generator</h4>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Create stunning visuals</p>
                <span class="mt-2 text-xs px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">Beta</span>
            </div>
        </a>

        <a href="{{ route('admin.ai.generator') }}" 
           class="group bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm hover:shadow-lg transition-all hover:border-indigo-300 dark:hover:border-indigo-700">
            <div class="flex flex-col items-center text-center">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-green-500/20 group-hover:scale-110 transition-transform">
                    <i class="fas fa-wand-magic-sparkles text-xl text-white"></i>
                </div>
                <h4 class="mt-3 font-semibold text-gray-900 dark:text-white">Website Generator</h4>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Build websites with AI</p>
                <span class="mt-2 text-xs px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400">Coming Soon</span>
            </div>
        </a>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Usage Chart -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h4 class="font-semibold text-gray-900 dark:text-white">AI Usage Trends</h4>
                <select x-model="usagePeriod" @change="updateUsageChart()"
                        class="px-2 py-1 text-xs bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg">
                    <option value="week">Week</option>
                    <option value="month">Month</option>
                    <option value="quarter">Quarter</option>
                </select>
            </div>
            <div class="h-64">
                <canvas x-ref="usageChart"></canvas>
            </div>
        </div>

        <!-- Tool Distribution -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Tool Distribution</h4>
            <div class="h-64">
                <canvas x-ref="distributionChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent AI Activities -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
            <h4 class="font-semibold text-gray-900 dark:text-white">Recent AI Activities</h4>
            <a href="{{ route('admin.ai.usage') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                View All <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="divide-y divide-gray-100 dark:divide-gray-800">
            <template x-for="activity in recentActivities" :key="activity.id">
                <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center"
                             :class="{
                               'bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400': activity.tool === 'writer',
                               'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400': activity.tool === 'seo',
                               'bg-pink-100 text-pink-600 dark:bg-pink-900/30 dark:text-pink-400': activity.tool === 'image',
                               'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400': activity.tool === 'website'
                             }">
                            <i :class="activity.icon"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-medium text-gray-900 dark:text-white" x-text="activity.user"></span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">used</span>
                                <span class="text-sm text-gray-900 dark:text-white" x-text="activity.tool_name"></span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400" x-text="activity.description"></p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs text-gray-500 dark:text-gray-400" x-text="formatTime(activity.timestamp)"></span>
                            <span class="text-xs px-2 py-0.5 rounded-full"
                                  :class="{
                                    'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400': activity.status === 'completed',
                                    'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400': activity.status === 'processing',
                                    'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400': activity.status === 'failed'
                                  }"
                                  x-text="activity.status"></span>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
        <button @click="openQuickPrompt()" 
                class="flex items-center justify-center gap-2 p-3 bg-indigo-50 dark:bg-indigo-900/10 hover:bg-indigo-100 dark:hover:bg-indigo-900/20 rounded-xl border border-indigo-200 dark:border-indigo-800 transition-colors">
            <i class="fas fa-bolt text-indigo-600 dark:text-indigo-400"></i>
            <span class="text-sm font-medium text-indigo-700 dark:text-indigo-300">Quick Prompt</span>
        </button>
        <button @click="openChat()" 
                class="flex items-center justify-center gap-2 p-3 bg-purple-50 dark:bg-purple-900/10 hover:bg-purple-100 dark:hover:bg-purple-900/20 rounded-xl border border-purple-200 dark:border-purple-800 transition-colors">
            <i class="fas fa-comment-dots text-purple-600 dark:text-purple-400"></i>
            <span class="text-sm font-medium text-purple-700 dark:text-purple-300">Chat Assistant</span>
        </button>
        <button @click="openTranslator()" 
                class="flex items-center justify-center gap-2 p-3 bg-green-50 dark:bg-green-900/10 hover:bg-green-100 dark:hover:bg-green-900/20 rounded-xl border border-green-200 dark:border-green-800 transition-colors">
            <i class="fas fa-language text-green-600 dark:text-green-400"></i>
            <span class="text-sm font-medium text-green-700 dark:text-green-300">Translate</span>
        </button>
        <button @click="openOptimizer()" 
                class="flex items-center justify-center gap-2 p-3 bg-orange-50 dark:bg-orange-900/10 hover:bg-orange-100 dark:hover:bg-orange-900/20 rounded-xl border border-orange-200 dark:border-orange-800 transition-colors">
            <i class="fas fa-arrow-trend-up text-orange-600 dark:text-orange-400"></i>
            <span class="text-sm font-medium text-orange-700 dark:text-orange-300">Content Optimizer</span>
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
function aiDashboard() {
    return {
        stats: @json($stats),
        recentActivities: @json($recentActivities),
        usagePeriod: 'month',
        usageChart: null,
        distributionChart: null,
        
        init() {
            this.initCharts();
        },
        
        initCharts() {
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#9ca3af' : '#4b5563';
            const gridColor = isDark ? '#1f2937' : '#e5e7eb';
            
            // Usage Chart
            const ctx1 = this.$refs.usageChart.getContext('2d');
            this.usageChart = new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: @json($chartData['usage']['labels']),
                    datasets: [
                        {
                            label: 'Requests',
                            data: @json($chartData['usage']['requests']),
                            borderColor: '#4f46e5',
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Tokens',
                            data: @json($chartData['usage']['tokens']),
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

            // Distribution Chart
            const ctx2 = this.$refs.distributionChart.getContext('2d');
            this.distributionChart = new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: @json($chartData['distribution']['labels']),
                    datasets: [{
                        data: @json($chartData['distribution']['values']),
                        backgroundColor: ['#4f46e5', '#3b82f6', '#ec4899', '#10b981', '#f59e0b', '#8b5cf6']
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
        
        updateUsageChart() {
            // Fetch new data based on period
            fetch(`{{ route('admin.ai.usage.data') }}?period=${this.usagePeriod}`)
                .then(response => response.json())
                .then(data => {
                    this.usageChart.data.labels = data.labels;
                    this.usageChart.data.datasets[0].data = data.requests;
                    this.usageChart.data.datasets[1].data = data.tokens;
                    this.usageChart.update();
                });
        },
        
        openQuickPrompt() {
            window.location.href = '{{ route("admin.ai.prompts") }}';
        },
        
        openChat() {
            window.location.href = '{{ route("admin.ai.chat") }}';
        },
        
        openTranslator() {
            window.location.href = '{{ route("admin.ai.translate") }}';
        },
        
        openOptimizer() {
            window.location.href = '{{ route("admin.ai.optimizer") }}';
        },
        
        formatNumber(value) {
            if (value >= 1000000) return (value / 1000000).toFixed(1) + 'M';
            if (value >= 1000) return (value / 1000).toFixed(1) + 'K';
            return value.toLocaleString();
        },
        
        formatTime(timestamp) {
            const date = new Date(timestamp);
            const now = new Date();
            const diff = now - date;
            
            if (diff < 60000) return 'Just now';
            if (diff < 3600000) return Math.floor(diff / 60000) + 'm ago';
            if (diff < 86400000) return Math.floor(diff / 3600000) + 'h ago';
            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        }
    }
}
</script>
@endpush