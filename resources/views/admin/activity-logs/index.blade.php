<!-- admin/activity-logs/index.blade.php -->
@extends('layout.app')

@section('title', 'Activity Logs')
@section('page-title', 'Activity Logs')
@section('page-subtitle', 'Monitor all system activities and user actions')

@section('content')
<div x-data="activityLogs()" x-init="init()">
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Activities</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="stats.total">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/20 flex items-center justify-center">
                    <i class="fas fa-list text-indigo-600 dark:text-indigo-400"></i>
                </div>
            </div>
            <p class="text-xs text-green-600 dark:text-green-400 mt-2">
                <i class="fas fa-arrow-up mr-1"></i> 12% increase
            </p>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Today</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="stats.today">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/20 flex items-center justify-center">
                    <i class="fas fa-calendar-day text-green-600 dark:text-green-400"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Last 24 hours</p>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Unique Users</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="stats.users">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/20 flex items-center justify-center">
                    <i class="fas fa-users text-purple-600 dark:text-purple-400"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Active users today</p>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Error Rate</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="stats.errors + '%'">0%</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-red-100 dark:bg-red-900/20 flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400"></i>
                </div>
            </div>
            <p class="text-xs text-green-600 dark:text-green-400 mt-2">
                <i class="fas fa-arrow-down mr-1"></i> 3% lower than yesterday
            </p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm mb-6">
        <div class="flex flex-wrap items-center gap-3">
            <!-- Type Filter -->
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                    <i class="fas fa-filter mr-1.5 text-gray-400"></i>Type:
                </label>
                <select x-model="filters.type" 
                        class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="all">All Types</option>
                    <option value="login">Login</option>
                    <option value="payment">Payment</option>
                    <option value="website">Website</option>
                    <option value="subscription">Subscription</option>
                    <option value="settings">Settings</option>
                    <option value="admin">Admin</option>
                    <option value="api">API</option>
                    <option value="security">Security</option>
                </select>
            </div>

            <!-- User Filter -->
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                    <i class="fas fa-user mr-1.5 text-gray-400"></i>User:
                </label>
                <select x-model="filters.user" 
                        class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="all">All Users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Date Range -->
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                    <i class="fas fa-calendar mr-1.5 text-gray-400"></i>Date:
                </label>
                <input type="date" x-model="filters.date_from" 
                       class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                <span class="text-gray-500 dark:text-gray-400 text-sm">to</span>
                <input type="date" x-model="filters.date_to" 
                       class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
            </div>

            <!-- Status -->
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                    <i class="fas fa-circle mr-1.5 text-gray-400"></i>Status:
                </label>
                <select x-model="filters.status" 
                        class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="all">All</option>
                    <option value="success">Success</option>
                    <option value="failed">Failed</option>
                    <option value="pending">Pending</option>
                </select>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-2 ml-auto">
                <button @click="applyFilters()" 
                        class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all">
                    <i class="fas fa-search mr-1.5"></i>Apply
                </button>
                <button @click="resetFilters()" 
                        class="px-4 py-1.5 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                    <i class="fas fa-undo mr-1.5"></i>Reset
                </button>
                <button @click="exportLogs()" 
                        class="px-4 py-1.5 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                    <i class="fas fa-download mr-1.5"></i>Export
                </button>
            </div>
        </div>
    </div>

    <!-- Activity Logs Table -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <input type="checkbox" @click="toggleAll" class="rounded border-gray-300 dark:border-gray-700">
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <a href="#" @click.prevent="sortBy('timestamp')" class="flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-300">
                                Timestamp
                                <i class="fas fa-sort text-xs"></i>
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">User</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Activity</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">IP Address</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    <template x-for="log in filteredLogs" :key="log.id">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-4 py-3">
                                <input type="checkbox" x-model="selectedLogs" :value="log.id" 
                                       class="rounded border-gray-300 dark:border-gray-700">
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-900 dark:text-white" x-text="formatDate(log.timestamp)"></td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <img :src="log.user.avatar" alt="" class="w-6 h-6 rounded-full">
                                    <span class="text-gray-900 dark:text-white" x-text="log.user.name"></span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div>
                                    <p class="text-gray-900 dark:text-white font-medium" x-text="log.action"></p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400" x-text="log.description"></p>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium"
                                      :class="getTypeClass(log.type)">
                                    <i :class="getTypeIcon(log.type)"></i>
                                    <span x-text="log.type"></span>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400 font-mono text-xs" x-text="log.ip"></td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium"
                                      :class="getStatusClass(log.status)">
                                    <i :class="log.status === 'success' ? 'fas fa-check-circle' : log.status === 'failed' ? 'fas fa-times-circle' : 'fas fa-clock'"></i>
                                    <span x-text="log.status"></span>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button @click="viewDetails(log)" 
                                            class="p-1.5 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>
                                    <button @click="deleteLog(log)" 
                                            class="p-1.5 text-gray-400 hover:text-red-600 dark:hover:text-red-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="filteredLogs.length === 0">
                        <td colspan="8" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-inbox text-3xl text-gray-300 dark:text-gray-700 mb-2"></i>
                                <p>No activity logs found</p>
                                <p class="text-xs mt-1">Try adjusting your filters</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-800">
            @include('admin.partials.pagination', ['paginator' => $logs])
        </div>
    </div>

    <!-- Detail Modal -->
    <div x-show="showDetailModal" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
         @click.away="showDetailModal = false">
        
        <div x-show="showDetailModal"
             x-transition:enter="transition ease-out duration-200 delay-100"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden"
             @click.stop>
            
            <div class="p-6 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Activity Details</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400" x-text="selectedLog?.description"></p>
                </div>
                <button @click="showDetailModal = false" 
                        class="p-1.5 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                    <i class="fas fa-times text-gray-400"></i>
                </button>
            </div>
            
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-8rem)]">
                <template x-if="selectedLog">
                    <div class="space-y-4">
                        <!-- Basic Info -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">User</label>
                                <div class="flex items-center gap-2 mt-1">
                                    <img :src="selectedLog.user.avatar" alt="" class="w-8 h-8 rounded-full">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white" x-text="selectedLog.user.name"></p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400" x-text="selectedLog.user.email"></p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Timestamp</label>
                                <p class="mt-1 text-gray-900 dark:text-white" x-text="formatDateTime(selectedLog.timestamp)"></p>
                            </div>
                        </div>

                        <!-- Activity Info -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Activity Type</label>
                                <p class="mt-1">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium"
                                          :class="getTypeClass(selectedLog.type)">
                                        <i :class="getTypeIcon(selectedLog.type)"></i>
                                        <span x-text="selectedLog.type"></span>
                                    </span>
                                </p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</label>
                                <p class="mt-1">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium"
                                          :class="getStatusClass(selectedLog.status)">
                                        <i :class="selectedLog.status === 'success' ? 'fas fa-check-circle' : selectedLog.status === 'failed' ? 'fas fa-times-circle' : 'fas fa-clock'"></i>
                                        <span x-text="selectedLog.status"></span>
                                    </span>
                                </p>
                            </div>
                        </div>

                        <!-- IP & Device Info -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">IP Address</label>
                                <p class="mt-1 font-mono text-sm text-gray-900 dark:text-white" x-text="selectedLog.ip"></p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">User Agent</label>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 break-all" x-text="selectedLog.user_agent || 'N/A'"></p>
                            </div>
                        </div>

                        <!-- Metadata -->
                        <div>
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Metadata</label>
                            <div class="mt-1 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg overflow-x-auto">
                                <pre class="text-xs font-mono text-gray-600 dark:text-gray-400" x-text="JSON.stringify(selectedLog.metadata, null, 2)"></pre>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-800">
                            <button @click="showDetailModal = false" 
                                    class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                                Close
                            </button>
                            <button @click="deleteLog(selectedLog)" 
                                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg shadow-sm transition-all">
                                <i class="fas fa-trash-alt mr-1.5"></i>Delete
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function activityLogs() {
    return {
        logs: @json($logs->items()),
        stats: @json($stats),
        filters: {
            type: 'all',
            user: 'all',
            date_from: '',
            date_to: '',
            status: 'all',
            search: ''
        },
        selectedLogs: [],
        showDetailModal: false,
        selectedLog: null,
        sortField: 'timestamp',
        sortDirection: 'desc',
        
        get filteredLogs() {
            let logs = this.logs;
            
            // Apply type filter
            if (this.filters.type !== 'all') {
                logs = logs.filter(log => log.type === this.filters.type);
            }
            
            // Apply user filter
            if (this.filters.user !== 'all') {
                logs = logs.filter(log => log.user.id == this.filters.user);
            }
            
            // Apply status filter
            if (this.filters.status !== 'all') {
                logs = logs.filter(log => log.status === this.filters.status);
            }
            
            // Apply date range
            if (this.filters.date_from) {
                logs = logs.filter(log => log.timestamp >= this.filters.date_from);
            }
            if (this.filters.date_to) {
                logs = logs.filter(log => log.timestamp <= this.filters.date_to + ' 23:59:59');
            }
            
            // Sort
            logs.sort((a, b) => {
                let aVal = a[this.sortField];
                let bVal = b[this.sortField];
                if (typeof aVal === 'string') aVal = aVal.toLowerCase();
                if (typeof bVal === 'string') bVal = bVal.toLowerCase();
                if (aVal < bVal) return this.sortDirection === 'asc' ? -1 : 1;
                if (aVal > bVal) return this.sortDirection === 'asc' ? 1 : -1;
                return 0;
            });
            
            return logs;
        },
        
        init() {
            this.fetchLogs();
            // Auto-refresh every 30 seconds
            setInterval(() => this.fetchLogs(), 30000);
        },
        
        fetchLogs() {
            fetch('{{ route("admin.activity-logs.fetch") }}')
                .then(response => response.json())
                .then(data => {
                    this.logs = data.logs;
                    this.stats = data.stats;
                })
                .catch(error => console.error('Error fetching logs:', error));
        },
        
        applyFilters() {
            this.fetchLogs();
        },
        
        resetFilters() {
            this.filters = {
                type: 'all',
                user: 'all',
                date_from: '',
                date_to: '',
                status: 'all',
                search: ''
            };
            this.fetchLogs();
        },
        
        sortBy(field) {
            if (this.sortField === field) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortField = field;
                this.sortDirection = 'asc';
            }
        },
        
        toggleAll(e) {
            const checked = e.target.checked;
            this.selectedLogs = checked ? this.filteredLogs.map(log => log.id) : [];
        },
        
        viewDetails(log) {
            this.selectedLog = log;
            this.showDetailModal = true;
        },
        
        deleteLog(log) {
            if (confirm(`Are you sure you want to delete this log entry?`)) {
                fetch(`{{ route("admin.activity-logs.destroy", "") }}/${log.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.logs = this.logs.filter(l => l.id !== log.id);
                        this.showDetailModal = false;
                        // Show success notification
                    }
                })
                .catch(error => console.error('Error deleting log:', error));
            }
        },
        
        exportLogs() {
            const params = new URLSearchParams(this.filters);
            window.location.href = `{{ route("admin.activity-logs.export") }}?${params.toString()}`;
        },
        
        formatDate(timestamp) {
            const date = new Date(timestamp);
            return date.toLocaleString('en-US', {
                month: 'short',
                day: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        },
        
        formatDateTime(timestamp) {
            const date = new Date(timestamp);
            return date.toLocaleString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                timeZoneName: 'short'
            });
        },
        
        getTypeClass(type) {
            const classes = {
                login: 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                payment: 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                website: 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
                subscription: 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                settings: 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400',
                admin: 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                api: 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400',
                security: 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400'
            };
            return classes[type] || 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400';
        },
        
        getTypeIcon(type) {
            const icons = {
                login: 'fas fa-sign-in-alt',
                payment: 'fas fa-credit-card',
                website: 'fas fa-globe',
                subscription: 'fas fa-sync-alt',
                settings: 'fas fa-cog',
                admin: 'fas fa-user-shield',
                api: 'fas fa-code',
                security: 'fas fa-shield-alt'
            };
            return icons[type] || 'fas fa-circle';
        },
        
        getStatusClass(status) {
            const classes = {
                success: 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                failed: 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                pending: 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400'
            };
            return classes[status] || 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400';
        }
    }
}
</script>
@endpush