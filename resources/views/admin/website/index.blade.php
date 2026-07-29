<!-- admin/websites/index.blade.php -->
@extends('layout.app')

@section('title', 'Websites')
@section('page-title', 'Websites Management')
@section('page-subtitle', 'Manage all customer websites and their status')

@section('content')
<div x-data="websiteManager()" x-init="init()">
    <!-- Website Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatNumber(stats.total)">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/20 flex items-center justify-center">
                    <i class="fas fa-globe text-indigo-600 dark:text-indigo-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Published</p>
                    <p class="text-2xl font-bold text-green-600" x-text="formatNumber(stats.published)">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/20 flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 dark:text-green-400"></i>
                </div>
            </div>
            <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                <span x-text="stats.published_percent + '%'"></span> of total
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Draft</p>
                    <p class="text-2xl font-bold text-yellow-600" x-text="formatNumber(stats.draft)">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-yellow-100 dark:bg-yellow-900/20 flex items-center justify-center">
                    <i class="fas fa-pencil-alt text-yellow-600 dark:text-yellow-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Archived</p>
                    <p class="text-2xl font-bold text-gray-600" x-text="formatNumber(stats.archived)">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                    <i class="fas fa-archive text-gray-600 dark:text-gray-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Suspended</p>
                    <p class="text-2xl font-bold text-red-600" x-text="formatNumber(stats.suspended)">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-red-100 dark:bg-red-900/20 flex items-center justify-center">
                    <i class="fas fa-ban text-red-600 dark:text-red-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Views</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatNumber(stats.views)">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/20 flex items-center justify-center">
                    <i class="fas fa-eye text-purple-600 dark:text-purple-400"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Actions -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm mb-6">
        <div class="flex flex-wrap items-center gap-3">
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Status:</label>
                <select x-model="filters.status" @change="applyFilters()"
                        class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    <option value="all">All Status</option>
                    <option value="published">Published</option>
                    <option value="draft">Draft</option>
                    <option value="archived">Archived</option>
                    <option value="suspended">Suspended</option>
                    <option value="deleted">Deleted</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Customer:</label>
                <select x-model="filters.customer" @change="applyFilters()"
                        class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    <option value="all">All Customers</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex-1 min-w-[200px]">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" x-model="filters.search" 
                           placeholder="Search websites..." 
                           class="w-full pl-9 pr-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <button @click="applyFilters()" 
                    class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all">
                <i class="fas fa-filter mr-1.5"></i>Apply
            </button>
            <button @click="resetFilters()" 
                    class="px-4 py-1.5 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                <i class="fas fa-undo mr-1.5"></i>Reset
            </button>
            
            <a href="{{ route('admin.websites.create') }}" 
               class="px-4 py-1.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all ml-auto">
                <i class="fas fa-plus mr-1.5"></i>New Website
            </a>
        </div>
    </div>

    <!-- Websites Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($websites as $website)
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm hover:shadow-md transition-all group">
                <!-- Thumbnail -->
                <div class="relative h-48 bg-gray-100 dark:bg-gray-800 rounded-t-xl overflow-hidden">
                    @if($website->screenshot)
                        <img src="{{ $website->screenshot }}" alt="{{ $website->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400 dark:text-gray-600">
                            <i class="fas fa-globe text-4xl"></i>
                        </div>
                    @endif
                    <div class="absolute top-3 right-3 flex items-center gap-1">
                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                     {{ $website->status === 'published' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 
                                        ($website->status === 'draft' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' :
                                        ($website->status === 'archived' ? 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400' :
                                        ($website->status === 'suspended' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' :
                                        'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400'))) }}">
                            {{ ucfirst($website->status) }}
                        </span>
                    </div>
                    @if($website->is_featured)
                        <div class="absolute top-3 left-3">
                            <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400 rounded-full">
                                <i class="fas fa-star mr-1"></i>Featured
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Content -->
                <div class="p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <a href="{{ route('admin.websites.show', $website) }}" 
                               class="font-semibold text-gray-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                {{ $website->name }}
                            </a>
                            <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ $website->domain }}</p>
                        </div>
                        <div class="flex items-center gap-1 ml-2">
                            <a href="{{ route('admin.websites.edit', $website) }}" 
                               class="p-1.5 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                <i class="fas fa-edit text-xs"></i>
                            </a>
                            <a href="{{ route('admin.websites.analytics', $website) }}" 
                               class="p-1.5 text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                <i class="fas fa-chart-line text-xs"></i>
                            </a>
                            <button @click="deleteWebsite({{ $website->id }})" 
                                    class="p-1.5 text-gray-400 hover:text-red-600 dark:hover:text-red-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                <i class="fas fa-trash-alt text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Meta -->
                    <div class="mt-3 flex flex-wrap items-center gap-3 text-xs text-gray-500 dark:text-gray-400">
                        <span class="flex items-center gap-1">
                            <i class="fas fa-user"></i>
                            {{ $website->customer->name ?? 'Unknown' }}
                        </span>
                        <span class="flex items-center gap-1">
                            <i class="fas fa-calendar-alt"></i>
                            {{ $website->created_at->format('M d, Y') }}
                        </span>
                        <span class="flex items-center gap-1">
                            <i class="fas fa-eye"></i>
                            {{ number_format($website->views ?? 0) }}
                        </span>
                    </div>

                    <!-- Progress / Actions -->
                    <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-800">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 mr-2">
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                                    <div class="h-1.5 rounded-full bg-indigo-500" 
                                         style="width: {{ $website->completion_percentage ?? 0 }}%"></div>
                                </div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $website->completion_percentage ?? 0 }}% complete
                                </span>
                            </div>
                            <div class="flex items-center gap-1">
                                @if($website->status === 'published')
                                    <a href="https://{{ $website->domain }}" target="_blank" 
                                       class="p-1.5 text-green-600 dark:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg transition-colors">
                                        <i class="fas fa-external-link-alt text-xs"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Empty State -->
    @if($websites->isEmpty())
        <div class="text-center py-12">
            <div class="flex flex-col items-center">
                <i class="fas fa-globe text-4xl text-gray-300 dark:text-gray-700 mb-3"></i>
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">No websites found</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Get started by creating your first website</p>
                <a href="{{ route('admin.websites.create') }}" 
                   class="mt-4 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all">
                    <i class="fas fa-plus mr-1.5"></i>Create Website
                </a>
            </div>
        </div>
    @endif

    <!-- Pagination -->
    <div class="mt-6">
        {{ $websites->links() }}
    </div>
</div>

@push('scripts')
<script>
function websiteManager() {
    return {
        stats: @json($stats),
        filters: {
            status: 'all',
            customer: 'all',
            search: ''
        },
        
        applyFilters() {
            const params = new URLSearchParams(this.filters);
            window.location.href = `{{ route('admin.websites.index') }}?${params.toString()}`;
        },
        
        resetFilters() {
            this.filters = {
                status: 'all',
                customer: 'all',
                search: ''
            };
            this.applyFilters();
        },
        
        deleteWebsite(id) {
            if (confirm('Are you sure you want to delete this website?')) {
                fetch(`{{ route('admin.websites.destroy', '') }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                })
                .catch(error => console.error('Error deleting website:', error));
            }
        },
        
        formatNumber(value) {
            return value.toLocaleString();
        }
    }
}
</script>
@endpush