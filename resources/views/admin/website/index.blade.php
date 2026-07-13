{{-- resources/views/admin/websites/index.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Websites Management')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">Websites</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- ===== HEADER ===== -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Websites</h1>
            <p class="text-sm text-gray-500 mt-1">Manage all websites created by your users</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.websites.export') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-download mr-2"></i> Export
            </a>
            <a href="{{ route('admin.websites.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-plus mr-2"></i> Add Website
            </a>
        </div>
    </div>

    <!-- ===== STATS CARDS ===== -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Websites</p>
                    <p class="text-xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center">
                    <i class="fas fa-globe"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Published</p>
                    <p class="text-xl font-bold text-green-600">{{ $stats['published'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Draft</p>
                    <p class="text-xl font-bold text-yellow-600">{{ $stats['draft'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center">
                    <i class="fas fa-pen"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Views</p>
                    <p class="text-xl font-bold text-blue-600">{{ $stats['total_views'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-eye"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== FILTERS ===== -->
    <div class="bg-white rounded-xl shadow-sm p-4">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" placeholder="Search websites..." value="{{ request('search') }}" 
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">All Status</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                <select name="sort" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Date Created</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                    <option value="views" {{ request('sort') == 'views' ? 'selected' : '' }}>Views</option>
                </select>
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    <i class="fas fa-search mr-2"></i> Filter
                </button>
                <a href="{{ route('admin.websites.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-times mr-2"></i> Clear
                </a>
            </div>
        </form>
    </div>

    <!-- ===== WEBSITES GRID ===== -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($websites as $website)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow group">
                <!-- Website Preview -->
                <div class="relative h-48 bg-gray-100 overflow-hidden">
                    @if($website->screenshot)
                        <img src="{{ Storage::disk('public')->url($website->screenshot) }}" 
                             alt="{{ $website->name }}" 
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary-50 to-primary-100">
                            <div class="text-center">
                                <i class="fas fa-globe text-4xl text-primary-400"></i>
                                <p class="mt-2 text-sm text-gray-500">No preview available</p>
                            </div>
                        </div>
                    @endif
                    <div class="absolute top-3 right-3 flex items-center space-x-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $website->status == 'published' ? 'bg-green-100 text-green-800' : 
                               ($website->status == 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ ucfirst($website->status) }}
                        </span>
                    </div>
                </div>
                
                <!-- Website Info -->
                <div class="p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $website->name }}</h3>
                            <p class="text-sm text-gray-500 truncate">{{ $website->domain ?? 'No domain' }}</p>
                        </div>
                        <div class="flex items-center space-x-1 ml-2">
                            <span class="text-sm text-gray-400">
                                <i class="fas fa-eye mr-1"></i>{{ $website->views ?? 0 }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="mt-3 flex items-center justify-between">
                        <div class="flex items-center space-x-2 text-xs text-gray-500">
                            <span>{{ $website->user->name ?? 'Unknown' }}</span>
                            <span>•</span>
                            <span>{{ $website->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="flex items-center space-x-1">
                            @if($website->status == 'published')
                                <a href="{{ route('website.preview', $website) }}" target="_blank" 
                                   class="p-1.5 text-gray-400 hover:text-green-600 transition-colors rounded-lg hover:bg-green-50" 
                                   title="Preview">
                                    <i class="fas fa-external-link-alt text-sm"></i>
                                </a>
                            @endif
                            <a href="{{ route('admin.websites.show', $website) }}" 
                               class="p-1.5 text-gray-400 hover:text-blue-600 transition-colors rounded-lg hover:bg-blue-50" 
                               title="View">
                                <i class="fas fa-eye text-sm"></i>
                            </a>
                            <a href="{{ route('admin.websites.edit', $website) }}" 
                               class="p-1.5 text-gray-400 hover:text-primary-600 transition-colors rounded-lg hover:bg-primary-50" 
                               title="Edit">
                                <i class="fas fa-pen text-sm"></i>
                            </a>
                            <a href="{{ route('admin.websites.analytics', $website) }}" 
                               class="p-1.5 text-gray-400 hover:text-purple-600 transition-colors rounded-lg hover:bg-purple-50" 
                               title="Analytics">
                                <i class="fas fa-chart-line text-sm"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="text-center py-12 bg-white rounded-xl shadow-sm">
                    <i class="fas fa-globe text-4xl text-gray-300 mb-3 block"></i>
                    <p class="text-lg font-medium text-gray-900">No websites found</p>
                    <p class="text-sm text-gray-500 mt-1">Get started by creating your first website</p>
                    <a href="{{ route('admin.websites.create') }}" class="inline-flex items-center mt-4 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i> Create Website
                    </a>
                </div>
            </div>
        @endforelse
    </div>
    
    <!-- ===== PAGINATION ===== -->
    <div class="flex items-center justify-between">
        @if($websites instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="text-sm text-gray-500">
                Showing {{ $websites->firstItem() ?? 0 }} to {{ $websites->lastItem() ?? 0 }} of {{ $websites->total() }} results
            </div>
            <div>
                {{ $websites->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-sm text-gray-500">
                Showing {{ $websites->count() }} results
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .card-hover {
        transition: all 0.2s ease;
    }
    .card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    }
</style>
@endpush
@endsection