<!-- admin/activity-logs/security.blade.php -->
@extends('layout.app')

@section('title', 'Security Logs')
@section('page-title', 'Security Activity')
@section('page-subtitle', 'Monitor security events and potential threats')

@section('content')
<div x-data="securityLogs()" x-init="init()">
    <!-- Security Overview -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Security Stats Cards -->
        @include('admin.partials.stats-cards', ['stats' => [
            [
                'label' => 'Security Events',
                'value' => $stats['total'],
                'icon' => 'shield-alt',
                'color' => 'indigo',
                'change' => 5.2
            ],
            [
                'label' => 'Threats Blocked',
                'value' => $stats['blocked'],
                'icon' => 'ban',
                'color' => 'red',
                'change' => -12.4
            ],
            [
                'label' => 'Suspicious Activities',
                'value' => $stats['suspicious'],
                'icon' => 'exclamation-triangle',
                'color' => 'yellow',
                'change' => 18.6
            ],
            [
                'label' => 'Security Score',
                'value' => $stats['score'] . '%',
                'icon' => 'check-double',
                'color' => 'green',
                'change' => 2.3
            ]
        ]])
    </div>

    <!-- Security Filters -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm mb-6">
        <div class="flex flex-wrap items-center gap-3">
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Severity:</label>
                <select x-model="filters.severity" 
                        class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    <option value="all">All</option>
                    <option value="critical">Critical</option>
                    <option value="high">High</option>
                    <option value="medium">Medium</option>
                    <option value="low">Low</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Type:</label>
                <select x-model="filters.type" 
                        class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    <option value="all">All Types</option>
                    <option value="brute_force">Brute Force</option>
                    <option value="sql_injection">SQL Injection</option>
                    <option value="xss">XSS Attack</option>
                    <option value="unauthorized">Unauthorized Access</option>
                    <option value="csrf">CSRF Attempt</option>
                    <option value="file_upload">Malicious File Upload</option>
                </select>
            </div>

            <button @click="applyFilters()" 
                    class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all">
                Apply Filters
            </button>
            <button @click="resetFilters()" 
                    class="px-4 py-1.5 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                Reset
            </button>
        </div>
    </div>

    <!-- Security Logs Table -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Severity</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Source IP</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Target</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Timestamp</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @foreach($logs as $log)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium
                                             {{ $log->severity === 'critical' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : 
                                                ($log->severity === 'high' ? 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400' :
                                                ($log->severity === 'medium' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' :
                                                'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400')) }}">
                                    <i class="fas fa-{{ $log->severity === 'critical' ? 'exclamation-circle' : 
                                               ($log->severity === 'high' ? 'exclamation-triangle' :
                                               ($log->severity === 'medium' ? 'warning' : 'info-circle')) }}"></i>
                                    {{ ucfirst($log->severity) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-900 dark:text-white">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-{{ $log->type === 'brute_force' ? 'key' : 
                                               ($log->type === 'sql_injection' ? 'database' :
                                               ($log->type === 'xss' ? 'code' :
                                               ($log->type === 'unauthorized' ? 'lock' :
                                               ($log->type === 'csrf' ? 'shield' : 'file-upload')))) }}"></i>
                                    {{ str_replace('_', ' ', ucfirst($log->type)) }}
                                </div>
                            </td>
                            <td class="px-4 py-3 font-mono text-xs text-gray-600 dark:text-gray-400">{{ $log->source_ip }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400 text-xs">{{ $log->target }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400" 
                                x-data="{ timestamp: '{{ $log->timestamp->toDateTimeString() }}' }" 
                                x-text="formatDate(timestamp)"></td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium
                                             {{ $log->status === 'blocked' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 
                                                ($log->status === 'investigating' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' :
                                                'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400') }}">
                                    <i class="fas fa-{{ $log->status === 'blocked' ? 'check-circle' : 
                                               ($log->status === 'investigating' ? 'clock' : 'minus-circle') }}"></i>
                                    {{ ucfirst($log->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <button @click="viewDetails({{ $log->id }})" 
                                        class="p-1.5 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                    <i class="fas fa-eye text-xs"></i>
                                </button>
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