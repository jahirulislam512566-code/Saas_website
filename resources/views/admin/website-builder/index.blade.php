<!-- admin/website-builder/index.blade.php -->
@extends('layout.app')

@section('title', 'Website Builder')
@section('page-title', 'Website Builder')
@section('page-subtitle', 'Drag and drop website builder for your customers')

@section('content')
<div x-data="builderDashboard()" x-init="init()">
    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Websites</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatNumber(stats.total_websites)">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/20 flex items-center justify-center">
                    <i class="fas fa-globe text-indigo-600 dark:text-indigo-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Blocks</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatNumber(stats.blocks)">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/20 flex items-center justify-center">
                    <i class="fas fa-cubes text-green-600 dark:text-green-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Templates</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatNumber(stats.templates)">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/20 flex items-center justify-center">
                    <i class="fas fa-layer-group text-purple-600 dark:text-purple-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Components</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatNumber(stats.components)">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center">
                    <i class="fas fa-puzzle-piece text-blue-600 dark:text-blue-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Themes</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatNumber(stats.themes)">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-yellow-100 dark:bg-yellow-900/20 flex items-center justify-center">
                    <i class="fas fa-palette text-yellow-600 dark:text-yellow-400"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <a href="{{ route('admin.website-builder.builder') }}" 
           class="group bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm hover:shadow-lg transition-all hover:border-indigo-300 dark:hover:border-indigo-700">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/20 group-hover:scale-110 transition-transform">
                    <i class="fas fa-edit text-white text-xl"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 dark:text-white">Launch Builder</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Drag & drop editor</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.website-builder.blocks') }}" 
           class="group bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm hover:shadow-lg transition-all hover:border-green-300 dark:hover:border-green-700">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-green-500/20 group-hover:scale-110 transition-transform">
                    <i class="fas fa-cubes text-white text-xl"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 dark:text-white">Manage Blocks</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Create & customize blocks</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.website-builder.themes') }}" 
           class="group bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm hover:shadow-lg transition-all hover:border-purple-300 dark:hover:border-purple-700">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center shadow-lg shadow-purple-500/20 group-hover:scale-110 transition-transform">
                    <i class="fas fa-palette text-white text-xl"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 dark:text-white">Themes</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Global styles & themes</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.website-builder.settings') }}" 
           class="group bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm hover:shadow-lg transition-all hover:border-gray-300 dark:hover:border-gray-700">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-gray-500 to-gray-600 flex items-center justify-center shadow-lg shadow-gray-500/20 group-hover:scale-110 transition-transform">
                    <i class="fas fa-cog text-white text-xl"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 dark:text-white">Settings</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Builder configuration</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Recent Builds -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Websites -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
                <h4 class="font-semibold text-gray-900 dark:text-white">Recent Websites</h4>
                <a href="{{ route('admin.websites.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($recentWebsites as $website)
                    <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/20 flex items-center justify-center">
                                <i class="fas fa-globe text-indigo-600 dark:text-indigo-400"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900 dark:text-white">{{ $website->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $website->domain }}</p>
                            </div>
                            <span class="text-xs px-2 py-0.5 rounded-full
                                         {{ $website->status === 'published' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 
                                            'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' }}">
                                {{ ucfirst($website->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                        <p>No websites built yet</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Templates -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
                <h4 class="font-semibold text-gray-900 dark:text-white">Popular Templates</h4>
                <a href="{{ route('admin.website-builder.templates') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($popularTemplates as $template)
                    <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/20 flex items-center justify-center">
                                <i class="fas fa-layer-group text-purple-600 dark:text-purple-400"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900 dark:text-white">{{ $template->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $template->category }}</p>
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                <i class="fas fa-download mr-1"></i> {{ number_format($template->downloads ?? 0) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                        <p>No templates available</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Builder Features -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 text-center hover:shadow-md transition-shadow">
            <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/20 flex items-center justify-center mx-auto mb-2">
                <i class="fas fa-arrows-alt text-indigo-600 dark:text-indigo-400"></i>
            </div>
            <p class="text-sm font-medium text-gray-900 dark:text-white">Drag & Drop</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Visual page builder</p>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 text-center hover:shadow-md transition-shadow">
            <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/20 flex items-center justify-center mx-auto mb-2">
                <i class="fas fa-mobile-alt text-green-600 dark:text-green-400"></i>
            </div>
            <p class="text-sm font-medium text-gray-900 dark:text-white">Responsive</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Mobile-first design</p>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 text-center hover:shadow-md transition-shadow">
            <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/20 flex items-center justify-center mx-auto mb-2">
                <i class="fas fa-css3-alt text-purple-600 dark:text-purple-400"></i>
            </div>
            <p class="text-sm font-medium text-gray-900 dark:text-white">Custom CSS</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Advanced styling</p>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 text-center hover:shadow-md transition-shadow">
            <div class="w-10 h-10 rounded-lg bg-yellow-100 dark:bg-yellow-900/20 flex items-center justify-center mx-auto mb-2">
                <i class="fas fa-rocket text-yellow-600 dark:text-yellow-400"></i>
            </div>
            <p class="text-sm font-medium text-gray-900 dark:text-white">Live Preview</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Real-time updates</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
function builderDashboard() {
    return {
        stats: @json($stats),
        
        formatNumber(value) {
            return value.toLocaleString();
        }
    }
}
</script>
@endpush