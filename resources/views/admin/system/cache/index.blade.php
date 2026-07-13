{{-- resources/views/admin/system/cache/index.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Cache Management')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <a href="{{ route('admin.system.index') }}" class="text-gray-500 hover:text-gray-700">System</a>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">Cache</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- ===== HEADER ===== -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Cache Management</h1>
            <p class="text-sm text-gray-500 mt-1">Manage application cache and performance</p>
        </div>
        <div class="flex items-center space-x-3">
            <button onclick="clearAllCache()" 
                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                <i class="fas fa-trash mr-2"></i> Clear All Cache
            </button>
        </div>
    </div>

    <!-- ===== STATS ===== -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Cache Driver</p>
                    <p class="text-xl font-bold text-gray-900">{{ $cacheInfo['driver'] ?? 'file' }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center">
                    <i class="fas fa-database"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Cache Size</p>
                    <p class="text-xl font-bold text-blue-600">{{ $cacheInfo['size'] ?? '0 MB' }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-hdd"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Cache Files</p>
                    <p class="text-xl font-bold text-green-600">{{ $cacheInfo['files'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="fas fa-file"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Cache Status</p>
                    <p class="text-xl font-bold text-green-600">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                            Active
                        </span>
                    </p>
                </div>
                <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== CACHE TYPES ===== -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Application Cache -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-white">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                        <i class="fas fa-code text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Application Cache</h3>
                        <p class="text-xs text-gray-500">Framework cache</p>
                    </div>
                </div>
            </div>
            <div class="p-4 space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Size</span>
                    <span class="text-gray-900">{{ $cacheInfo['app_size'] ?? 'N/A' }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Files</span>
                    <span class="text-gray-900">{{ $cacheInfo['app_files'] ?? 'N/A' }}</span>
                </div>
                <button onclick="clearCache('app')" 
                        class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                    <i class="fas fa-trash mr-2"></i> Clear App Cache
                </button>
            </div>
        </div>

        <!-- View Cache -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-white">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center">
                        <i class="fas fa-eye text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">View Cache</h3>
                        <p class="text-xs text-gray-500">Compiled views</p>
                    </div>
                </div>
            </div>
            <div class="p-4 space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Size</span>
                    <span class="text-gray-900">{{ $cacheInfo['view_size'] ?? 'N/A' }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Files</span>
                    <span class="text-gray-900">{{ $cacheInfo['view_files'] ?? 'N/A' }}</span>
                </div>
                <button onclick="clearCache('views')" 
                        class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors text-sm">
                    <i class="fas fa-trash mr-2"></i> Clear View Cache
                </button>
            </div>
        </div>

        <!-- Route Cache -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-white">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-lg bg-green-100 text-green-600 flex items-center justify-center">
                        <i class="fas fa-route text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Route Cache</h3>
                        <p class="text-xs text-gray-500">Cached routes</p>
                    </div>
                </div>
            </div>
            <div class="p-4 space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Status</span>
                    <span class="text-gray-900">{{ $cacheInfo['route_status'] ?? 'Not Cached' }}</span>
                </div>
                <button onclick="clearCache('routes')" 
                        class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                    <i class="fas fa-trash mr-2"></i> Clear Route Cache
                </button>
            </div>
        </div>

        <!-- Config Cache -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-white">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-lg bg-yellow-100 text-yellow-600 flex items-center justify-center">
                        <i class="fas fa-cog text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Config Cache</h3>
                        <p class="text-xs text-gray-500">Configuration cache</p>
                    </div>
                </div>
            </div>
            <div class="p-4 space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Status</span>
                    <span class="text-gray-900">{{ $cacheInfo['config_status'] ?? 'Not Cached' }}</span>
                </div>
                <button onclick="clearCache('config')" 
                        class="w-full px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors text-sm">
                    <i class="fas fa-trash mr-2"></i> Clear Config Cache
                </button>
            </div>
        </div>

        <!-- Event Cache -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-pink-50 to-white">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-lg bg-pink-100 text-pink-600 flex items-center justify-center">
                        <i class="fas fa-bolt text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Event Cache</h3>
                        <p class="text-xs text-gray-500">Cached events</p>
                    </div>
                </div>
            </div>
            <div class="p-4 space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Status</span>
                    <span class="text-gray-900">{{ $cacheInfo['event_status'] ?? 'Not Cached' }}</span>
                </div>
                <button onclick="clearCache('events')" 
                        class="w-full px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition-colors text-sm">
                    <i class="fas fa-trash mr-2"></i> Clear Event Cache
                </button>
            </div>
        </div>

        <!-- Optimize -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-white">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center">
                        <i class="fas fa-rocket text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Optimize</h3>
                        <p class="text-xs text-gray-500">Optimize application</p>
                    </div>
                </div>
            </div>
            <div class="p-4 space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Status</span>
                    <span class="text-gray-900">{{ $cacheInfo['optimize_status'] ?? 'Not Optimized' }}</span>
                </div>
                <button onclick="optimizeApplication()" 
                        class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm">
                    <i class="fas fa-rocket mr-2"></i> Optimize App
                </button>
            </div>
        </div>
    </div>

    <!-- ===== CACHE ACTIVITY ===== -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-sm font-medium text-gray-900">Cache Activity</h3>
        </div>
        <div class="divide-y divide-gray-200">
            @forelse($activities ?? [] as $activity)
                <div class="px-6 py-3 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-900">{{ $activity->description }}</p>
                            <p class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="text-xs text-gray-400">{{ $activity->user->name ?? 'System' }}</span>
                    </div>
                </div>
            @empty
                <div class="px-6 py-8 text-center text-gray-500">No cache activity</div>
            @endforelse
        </div>
    </div>
</div>

<!-- ===== FORMS ===== -->
<form id="clear-cache-form" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="type" id="cache-type">
</form>

<form id="optimize-form" method="POST" style="display: none;">
    @csrf
</form>

@push('scripts')
<script>
    function clearCache(type) {
        if (confirm(`Clear ${type} cache?`)) {
            const form = document.getElementById('clear-cache-form');
            document.getElementById('cache-type').value = type;
            form.action = `/admin/system/cache/clear`;
            form.submit();
        }
    }

    function clearAllCache() {
        if (confirm('Clear all cache? This will clear application, view, route, and config cache.')) {
            const form = document.getElementById('clear-cache-form');
            document.getElementById('cache-type').value = 'all';
            form.action = `/admin/system/cache/clear-all`;
            form.submit();
        }
    }

    function optimizeApplication() {
        if (confirm('Optimize the application? This will cache routes, config, and views.')) {
            const form = document.getElementById('optimize-form');
            form.action = `/admin/system/cache/optimize`;
            form.submit();
        }
    }
</script>
@endpush

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