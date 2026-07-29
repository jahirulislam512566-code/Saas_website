<!-- admin/ai/usage.blade.php -->
@extends('layout.app')

@section('title', 'AI Usage')
@section('page-title', 'AI Usage Analytics')
@section('page-subtitle', 'Track AI tool usage, costs, and performance metrics')

@section('content')
<div x-data="aiUsage()" x-init="init()">
    <!-- Usage Filters -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm mb-6">
        <div class="flex flex-wrap items-center gap-3">
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Date Range:</label>
                <select x-model="filters.period" @change="applyFilters()"
                        class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                    <option value="quarter">This Quarter</option>
                    <option value="year">This Year</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Tool:</label>
                <select x-model="filters.tool" @change="applyFilters()"
                        class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    <option value="all">All Tools</option>
                    <option value="writer">AI Writer</option>
                    <option value="seo">SEO Generator</option>
                    <option value="image">Image Generator</option>
                    <option value="website">Website Generator</option>
                    <option value="chat">Chat Assistant</option>
                    <option value="translate">Translation</option>
                    <option value="optimizer">Content Optimizer</option>
                </select>
            </div>

            <button @click="exportUsage()" 
                    class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all ml-auto">
                <i class="fas fa-download mr-1.5"></i>Export
            </button>
        </div>
    </div>

    <!-- Usage Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Requests</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatNumber(stats.total_requests)">0</p>
            <div class="mt-1 text-sm text-green-600 dark:text-green-400" x-text="'+' + stats.requests_growth + '%'"></div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Tokens</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatNumber(stats.total_tokens)">0</p>
            <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                <span x-text="formatNumber(stats.avg_tokens_per_request)"></span> avg per request
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Cost</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatCurrency(stats.total_cost)">$0</p>
            <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                <span x-text="formatCurrency(stats.cost_per_request)"></span> per request
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Success Rate</p>
            <p class="text-2xl font-bold text-green-600" x-text="stats.success_rate + '%'">0%</p>
            <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                <span x-text="stats.failed_requests"></span> failed requests
            </div>
        </div>
    </div>

    <!-- Usage Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Usage Over Time</h4>
            <div class="h-64">
                <canvas x-ref="usageOverTimeChart"></canvas>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Cost Breakdown</h4>
            <div class="h-64">
                <canvas x-ref="costBreakdownChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Usage Table -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
            <h4 class="font-semibold text-gray-900 dark:text-white">Recent Usage Logs</h4>
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-500 dark:text-gray-400" x-text="'Showing ' + usageLogs.length + ' entries'"></span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">User</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tool</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Prompt</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tokens</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Cost</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Duration</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Time</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    <template x-for="log in usageLogs" :key="log.id">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <img :src="log.user?.avatar || 'https://ui-avatars.com/api/?name=' + (log.user?.name || 'U')" 
                                         alt="" class="w-6 h-6 rounded-full">
                                    <span class="text-gray-900 dark:text-white" x-text="log.user?.name || 'Unknown'"></span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium"
                                      :class="{
                                        'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400': log.tool === 'writer',
                                        'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400': log.tool === 'seo',
                                        'bg-pink-100 text-pink-700 dark:bg-pink-900/30 dark:text-pink-400': log.tool === 'image',
                                        'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400': log.tool === 'website',
                                        'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400': log.tool === 'chat'
                                      }"
                                      x-text="log.tool"></span>
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400 max-w-[200px] truncate" x-text="log.prompt"></td>
                            <td class="px-4 py-3 text-right text-gray-900 dark:text-white" x-text="formatNumber(log.tokens)"></td>
                            <td class="px-4 py-3 text-right text-gray-900 dark:text-white" x-text="formatCurrency(log.cost)"></td>
                            <td class="px-4 py-3 text-right text-gray-600 dark:text-gray-400" x-text="log.duration + 'ms'"></td>
                            <td class="px-4 py-3 text-right">
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium"
                                      :class="{
                                        'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400': log.status === 'success',
                                        'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400': log.status === 'processing',
                                        'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400': log.status === 'failed'
                                      }">
                                    <i :class="{
                                        'fas fa-check-circle': log.status === 'success',
                                        'fas fa-spinner fa-spin': log.status === 'processing',
                                        'fas fa-times-circle': log.status === 'failed'
                                    }"></i>
                                    <span x-text="log.status"></span>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right text-gray-500 dark:text-gray-400 text-xs" x-text="formatTime(log.created_at)"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-800">
            @include('admin.partials.pagination', ['paginator' => $usageLogs])
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function aiUsage() {
    return {
        usageLogs: @json($usageLogs->items()),
        stats: @json($stats),
        filters: {
            period: 'month',
            tool: 'all'
        },
        usageOverTimeChart: null,
        costBreakdownChart: null,
        
        init() {
            this.initCharts();
        },
        
        initCharts() {
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#9ca3af' : '#4b5563';
            const gridColor = isDark ? '#1f2937' : '#e5e7eb';
            
            // Usage Over Time Chart
            const ctx1 = this.$refs.usageOverTimeChart.getContext('2d');
            this.usageOverTimeChart = new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: @json($chartData['usage_over_time']['labels']),
                    datasets: [
                        {
                            label: 'Requests',
                            data: @json($chartData['usage_over_time']['requests']),
                            backgroundColor: 'rgba(79, 70, 229, 0.6)',
                            borderColor: '#4f46e5',
                            borderWidth: 1
                        },
                        {
                            label: 'Tokens',
                            data: @json($chartData['usage_over_time']['tokens']),
                            backgroundColor: 'rgba(139, 92, 246, 0.6)',
                            borderColor: '#8b5cf6',
                            borderWidth: 1
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

            // Cost Breakdown Chart
            const ctx2 = this.$refs.costBreakdownChart.getContext('2d');
            this.costBreakdownChart = new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: @json($chartData['cost_breakdown']['labels']),
                    datasets: [{
                        data: @json($chartData['cost_breakdown']['values']),
                        backgroundColor: ['#4f46e5', '#8b5cf6', '#ec4899', '#10b981', '#f59e0b']
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
        
        applyFilters() {
            const params = new URLSearchParams(this.filters);
            window.location.href = `{{ route('admin.ai.usage') }}?${params.toString()}`;
        },
        
        exportUsage() {
            const params = new URLSearchParams(this.filters);
            window.location.href = `{{ route('admin.ai.usage.export') }}?${params.toString()}`;
        },
        
        formatNumber(value) {
            if (value >= 1000000) return (value / 1000000).toFixed(1) + 'M';
            if (value >= 1000) return (value / 1000).toFixed(1) + 'K';
            return value.toLocaleString();
        },
        
        formatCurrency(value) {
            if (value >= 1000) return '$' + (value / 1000).toFixed(2) + 'K';
            return '$' + value.toFixed(4);
        },
        
        formatTime(timestamp) {
            const date = new Date(timestamp);
            return date.toLocaleString('en-US', { 
                month: 'short', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }
    }
}
</script>
@endpush