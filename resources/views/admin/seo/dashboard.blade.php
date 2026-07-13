{{-- resources/views/admin/seo/dashboard.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'SEO Dashboard')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">SEO</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- ===== HEADER ===== -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">SEO Dashboard</h1>
            <p class="text-sm text-gray-500 mt-1">Monitor and manage your SEO performance</p>
        </div>
        <div class="flex items-center space-x-3">
            <button onclick="refreshData()" class="p-2 bg-white rounded-lg shadow-sm border border-gray-200 hover:bg-gray-50 transition-colors">
                <i class="fas fa-sync text-gray-500"></i>
            </button>
            <a href="{{ route('admin.seo.sitemap') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-sitemap mr-2"></i> Manage Sitemap
            </a>
        </div>
    </div>

    <!-- ===== STATS CARDS ===== -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Pages Indexed</p>
                    <p class="text-xl font-bold text-gray-900">{{ $stats['indexed'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center">
                    <i class="fas fa-file-alt"></i>
                </div>
            </div>
            <div class="mt-2 text-xs text-green-600">
                <i class="fas fa-arrow-up mr-1"></i> +12% this month
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Search Queries</p>
                    <p class="text-xl font-bold text-blue-600">{{ $stats['queries'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-search"></i>
                </div>
            </div>
            <div class="mt-2 text-xs text-blue-600">
                <i class="fas fa-arrow-up mr-1"></i> +8% this month
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Organic Traffic</p>
                    <p class="text-xl font-bold text-green-600">{{ $stats['traffic'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="mt-2 text-xs text-green-600">
                <i class="fas fa-arrow-up mr-1"></i> +15% this month
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Avg. Position</p>
                    <p class="text-xl font-bold text-purple-600">#{{ $stats['avg_position'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
            <div class="mt-2 text-xs text-purple-600">
                <i class="fas fa-arrow-up mr-1"></i> +3 positions
            </div>
        </div>
    </div>

    <!-- ===== SEO HEALTH ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- SEO Score -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-sm font-medium text-gray-900 mb-4">SEO Health Score</h3>
            <div class="flex items-center justify-center">
                <div class="relative">
                    <svg class="w-32 h-32">
                        <circle class="text-gray-200" stroke-width="8" stroke="currentColor" fill="transparent" r="58" cx="64" cy="64"/>
                        <circle class="text-green-500" stroke-width="8" stroke-dasharray="{{ $stats['score'] ?? 85 }} 100" stroke-linecap="round" stroke="currentColor" fill="transparent" r="58" cx="64" cy="64"/>
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['score'] ?? 85 }}%</p>
                            <p class="text-xs text-gray-500">Good</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4 grid grid-cols-2 gap-2 text-center">
                <div class="bg-green-50 rounded-lg p-2">
                    <p class="text-sm font-medium text-green-600">Good</p>
                    <p class="text-xs text-gray-500">{{ $stats['good'] ?? 15 }} issues</p>
                </div>
                <div class="bg-yellow-50 rounded-lg p-2">
                    <p class="text-sm font-medium text-yellow-600">Warning</p>
                    <p class="text-xs text-gray-500">{{ $stats['warning'] ?? 8 }} issues</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-sm font-medium text-gray-900 mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('admin.seo.sitemap') }}" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="w-10 h-10 rounded-lg bg-primary-100 text-primary-600 flex items-center justify-center">
                        <i class="fas fa-sitemap"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Generate Sitemap</p>
                        <p class="text-xs text-gray-500">Update XML sitemap</p>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400 ml-auto"></i>
                </a>

                <a href="{{ route('admin.seo.robots') }}" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Robots.txt</p>
                        <p class="text-xs text-gray-500">Manage crawler access</p>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400 ml-auto"></i>
                </a>

                <a href="{{ route('admin.seo.redirects') }}" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Redirects</p>
                        <p class="text-xs text-gray-500">Manage URL redirects</p>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400 ml-auto"></i>
                </a>

                <a href="{{ route('admin.seo.schema') }}" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="w-10 h-10 rounded-lg bg-yellow-100 text-yellow-600 flex items-center justify-center">
                        <i class="fas fa-code"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Schema Markup</p>
                        <p class="text-xs text-gray-500">Add structured data</p>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400 ml-auto"></i>
                </a>
            </div>
        </div>

        <!-- Recent SEO Activity -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-sm font-medium text-gray-900 mb-4">Recent Activity</h3>
            <div class="space-y-3">
                @forelse($activities ?? [] as $activity)
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-circle text-gray-400 text-xs"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-900">{{ $activity->description }}</p>
                            <p class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-500 text-sm py-4">No recent activity</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- ===== SEO ISSUES ===== -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-sm font-medium text-gray-900">SEO Issues</h3>
        </div>
        <div class="divide-y divide-gray-200">
            @forelse($issues ?? [] as $issue)
                <div class="px-6 py-3 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full 
                                {{ $issue->severity == 'critical' ? 'bg-red-100 text-red-600' : 
                                   ($issue->severity == 'warning' ? 'bg-yellow-100 text-yellow-600' : 'bg-blue-100 text-blue-600') }} 
                                flex items-center justify-center">
                                <i class="fas {{ $issue->severity == 'critical' ? 'fa-exclamation-circle' : 
                                     ($issue->severity == 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle') }}"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $issue->title }}</p>
                                <p class="text-xs text-gray-500">{{ $issue->page }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $issue->severity == 'critical' ? 'bg-red-100 text-red-800' : 
                                   ($issue->severity == 'warning' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                                {{ ucfirst($issue->severity) }}
                            </span>
                            <a href="{{ $issue->link }}" class="text-gray-400 hover:text-primary-600 transition-colors">
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-6 py-8 text-center text-gray-500">
                    <i class="fas fa-check-circle text-green-500 text-3xl mb-3 block"></i>
                    <p class="text-lg font-medium">No SEO issues found</p>
                    <p class="text-sm mt-1">Your website is in great shape!</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script>
    function refreshData() {
        window.location.reload();
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