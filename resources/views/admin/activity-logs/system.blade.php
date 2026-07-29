<!-- admin/activity-logs/system.blade.php -->
@extends('layout.app')

@section('title', 'System Logs')
@section('page-title', 'System Activity')
@section('page-subtitle', 'Monitor system events, performance, and errors')

@section('content')
<div x-data="systemLogs()" x-init="init()">
    <!-- System Health Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">System Status</p>
                    <div class="mt-1 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-green-500 animate-pulse"></span>
                        <span class="text-sm font-medium text-green-600">Operational</span>
                    </div>
                </div>
                <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/20 flex items-center justify-center">
                    <i class="fas fa-heartbeat text-green-600 dark:text-green-400"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Uptime: 99.98%</p>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Error Rate</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="stats.error_rate + '%'">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-red-100 dark:bg-red-900/20 flex items-center justify-center">
                    <i class="fas fa-exclamation-circle text-red-600 dark:text-red-400"></i>
                </div>
            </div>
            <p class="text-xs text-green-600 dark:text-green-400 mt-2">
                <i class="fas fa-arrow-down mr-1"></i> 2.1% lower than average
            </p>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Response Time</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="stats.response_time + 'ms'">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center">
                    <i class="fas fa-clock text-blue-600 dark:text-blue-400"></i>
                </div>
            </div>
            <p class="text-xs text-green-600 dark:text-green-400 mt-2">
                <i class="fas fa-arrow-down mr-1"></i> 12ms improvement
            </p>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">API Calls</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="stats.api_calls">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/20 flex items-center justify-center">
                    <i class="fas fa-code text-purple-600 dark:text-purple-400"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Last 24 hours</p>
        </div>
    </div>

    <!-- System Logs Table -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Level</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Message</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Source</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Timestamp</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @foreach($logs as $log)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium
                                             {{ $log->level === 'error' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : 
                                                ($log->level === 'warning' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' :
                                                ($log->level === 'info' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' :
                                                'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400')) }}">
                                    {{ ucfirst($log->level) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div>
                                    <p class="text-gray-900 dark:text-white font-medium">{{ $log->message }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $log->context ?? '' }}</p>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400 text-xs">
                                <span class="inline-flex items-center gap-1">
                                    <i class="fas fa-{{ $log->source === 'application' ? 'server' : 
                                               ($log->source === 'database' ? 'database' :
                                               ($log->source === 'cache' ? 'memory' : 'cloud')) }}"></i>
                                    {{ ucfirst($log->source) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400" 
                                x-data="{ timestamp: '{{ $log->timestamp->toDateTimeString() }}' }" 
                                x-text="formatDate(timestamp)"></td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-1">
                                    <button @click="viewDetails({{ $log->id }})" 
                                            class="p-1.5 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>
                                    <button @click="retry({{ $log->id }})" 
                                            class="p-1.5 text-gray-400 hover:text-green-600 dark:hover:text-green-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                        <i class="fas fa-redo text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-800">
            @include('admin.partials.pagination', ['paginator' => $logs])
        </div>
    </div>
</div>
@endsection