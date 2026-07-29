<!-- admin/subscriptions/history.blade.php -->
@extends('layout.app')

@section('title', 'Subscription History')
@section('page-title', 'Subscription History')
@section('page-subtitle', 'Complete audit trail of subscription activities')

@section('content')
<div x-data="subscriptionHistory()" x-init="init()">
    <!-- History Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Events</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatNumber(stats.total)">0</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Created</p>
            <p class="text-2xl font-bold text-green-600" x-text="formatNumber(stats.created)">0</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Renewed</p>
            <p class="text-2xl font-bold text-blue-600" x-text="formatNumber(stats.renewed)">0</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Cancelled</p>
            <p class="text-2xl font-bold text-red-600" x-text="formatNumber(stats.cancelled)">0</p>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm mb-6">
        <div class="flex flex-wrap items-center gap-3">
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Event Type:</label>
                <select x-model="filters.type" @change="applyFilters()"
                        class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    <option value="all">All Events</option>
                    <option value="created">Created</option>
                    <option value="renewed">Renewed</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="paused">Paused</option>
                    <option value="resumed">Resumed</option>
                    <option value="updated">Updated</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Date:</label>
                <input type="date" x-model="filters.date_from" 
                       class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                <span class="text-gray-500">to</span>
                <input type="date" x-model="filters.date_to" 
                       class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
            </div>

            <button @click="applyFilters()" 
                    class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all">
                <i class="fas fa-filter mr-1.5"></i>Apply
            </button>
            <button @click="resetFilters()" 
                    class="px-4 py-1.5 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                <i class="fas fa-undo mr-1.5"></i>Reset
            </button>
            
            <button @click="exportHistory()" 
                    class="px-4 py-1.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all ml-auto">
                <i class="fas fa-download mr-1.5"></i>Export
            </button>
        </div>
    </div>

    <!-- History Timeline -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-200 dark:border-gray-800">
            <h4 class="font-semibold text-gray-900 dark:text-white">Activity Timeline</h4>
        </div>
        <div class="divide-y divide-gray-100 dark:divide-gray-800">
            <template x-for="event in filteredHistory" :key="event.id">
                <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0"
                             :class="{
                               'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400': event.type === 'created',
                               'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400': event.type === 'renewed',
                               'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400': event.type === 'cancelled',
                               'bg-yellow-100 text-yellow-600 dark:bg-yellow-900/30 dark:text-yellow-400': event.type === 'paused',
                               'bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400': event.type === 'resumed',
                               'bg-gray-100 text-gray-600 dark:bg-gray-900/30 dark:text-gray-400': event.type === 'updated'
                             }">
                            <i :class="{
                                'fas fa-plus-circle': event.type === 'created',
                                'fas fa-sync': event.type === 'renewed',
                                'fas fa-times-circle': event.type === 'cancelled',
                                'fas fa-pause-circle': event.type === 'paused',
                                'fas fa-play-circle': event.type === 'resumed',
                                'fas fa-edit': event.type === 'updated'
                            }"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <span class="font-medium text-gray-900 dark:text-white" x-text="event.title"></span>
                                <span class="text-xs px-2 py-0.5 rounded-full"
                                      :class="{
                                        'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400': event.status === 'success',
                                        'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400': event.status === 'failed',
                                        'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400': event.status === 'pending'
                                      }"
                                      x-text="event.status"></span>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400" x-text="event.description"></p>
                            <div class="flex items-center gap-4 mt-1 text-xs text-gray-500 dark:text-gray-400">
                                <span x-text="formatDateTime(event.created_at)"></span>
                                <span x-show="event.user" x-text="'By: ' + event.user"></span>
                                <span x-show="event.subscription_id" x-text="'Subscription: #' + event.subscription_id"></span>
                            </div>
                        </div>
                        <a x-show="event.subscription_id" :href="'/admin/subscriptions/' + event.subscription_id" 
                           class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline flex-shrink-0">
                            View Subscription <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </template>
            <div x-show="filteredHistory.length === 0" class="p-8 text-center text-gray-500 dark:text-gray-400">
                <div class="flex flex-col items-center">
                    <i class="fas fa-history text-3xl text-gray-300 dark:text-gray-700 mb-2"></i>
                    <p>No history events found</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function subscriptionHistory() {
    return {
        history: @json($history->items()),
        stats: @json($stats),
        filters: {
            type: 'all',
            date_from: '',
            date_to: ''
        },
        
        get filteredHistory() {
            let history = this.history;
            
            if (this.filters.type !== 'all') {
                history = history.filter(h => h.type === this.filters.type);
            }
            
            return history;
        },
        
        applyFilters() {
            const params = new URLSearchParams(this.filters);
            window.location.href = `{{ route('admin.subscriptions.history') }}?${params.toString()}`;
        },
        
        resetFilters() {
            this.filters = {
                type: 'all',
                date_from: '',
                date_to: ''
            };
            this.applyFilters();
        },
        
        exportHistory() {
            const params = new URLSearchParams(this.filters);
            window.location.href = `{{ route('admin.subscriptions.history.export') }}?${params.toString()}`;
        },
        
        formatNumber(value) {
            return value.toLocaleString();
        },
        
        formatDateTime(timestamp) {
            const date = new Date(timestamp);
            return date.toLocaleString('en-US', { 
                month: 'short', 
                day: 'numeric', 
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }
    }
}
</script>
@endpush