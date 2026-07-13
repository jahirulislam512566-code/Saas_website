{{-- resources/views/admin/websites/show.blade.php --}}
@extends('admin.layouts.admin')

@section('title', $website->name)

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <a href="{{ route('admin.websites.index') }}" class="text-gray-500 hover:text-gray-700">Websites</a>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-700">{{ $website->name }}</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- ===== HEADER ===== -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-primary-100 text-primary-600 flex items-center justify-center">
                <i class="fas fa-globe text-2xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $website->name }}</h1>
                <div class="flex items-center space-x-3 mt-1">
                    <span class="text-sm text-gray-500">{{ $website->domain ?? 'No domain' }}</span>
                    <span class="text-gray-300">|</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        {{ $website->status == 'published' ? 'bg-green-100 text-green-800' : 
                           ($website->status == 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                        {{ ucfirst($website->status) }}
                    </span>
                    @if($website->is_featured)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-star mr-1"></i> Featured
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            @if($website->status == 'published')
                <a href="{{ route('website.preview', $website) }}" target="_blank" 
                   class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-external-link-alt mr-2"></i> Preview
                </a>
            @endif
            @if($website->status != 'published')
                <a href="{{ route('admin.websites.publish', $website) }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-rocket mr-2"></i> Publish
                </a>
            @endif
            <a href="{{ route('admin.websites.edit', $website) }}" 
               class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-pen mr-2"></i> Edit
            </a>
            <a href="{{ route('admin.websites.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
        </div>
    </div>

    <!-- ===== STATS ===== -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Total Views</p>
            <p class="text-xl font-bold text-gray-900">{{ $website->views ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Pages</p>
            <p class="text-xl font-bold text-blue-600">{{ $website->pages_count ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Domains</p>
            <p class="text-xl font-bold text-purple-600">{{ $website->domains_count ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Owner</p>
            <p class="text-xl font-bold text-green-600">{{ $website->user->name ?? 'Unknown' }}</p>
        </div>
    </div>

    <!-- ===== DETAILS ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Description -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-sm font-medium text-gray-900 mb-3">Description</h3>
                @if($website->description)
                    <p class="text-gray-600">{{ $website->description }}</p>
                @else
                    <p class="text-gray-400 italic">No description provided</p>
                @endif
            </div>

            <!-- Pages -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-sm font-medium text-gray-900">Pages</h3>
                    <a href="{{ route('admin.websites.pages', $website) }}" class="text-xs text-primary-600 hover:text-primary-700">
                        View All
                    </a>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($website->pages ?? [] as $page)
                        <div class="px-6 py-3 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $page->title }}</p>
                                    <p class="text-xs text-gray-500">{{ $page->slug }}</p>
                                </div>
                                <span class="text-xs text-gray-400">{{ $page->updated_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-4 text-center text-gray-500">No pages created yet</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-sm font-medium text-gray-900 mb-4">Website Information</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-xs text-gray-500">ID</dt>
                        <dd class="text-sm text-gray-900">#{{ $website->id }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Name</dt>
                        <dd class="text-sm text-gray-900">{{ $website->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Domain</dt>
                        <dd class="text-sm text-gray-900">{{ $website->domain ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Template</dt>
                        <dd class="text-sm text-gray-900">{{ $website->template->name ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Status</dt>
                        <dd class="text-sm text-gray-900">{{ ucfirst($website->status) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">SSL</dt>
                        <dd class="text-sm text-gray-900">{{ $website->has_ssl ? 'Enabled' : 'Disabled' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Created</dt>
                        <dd class="text-sm text-gray-900">{{ $website->created_at->format('M d, Y H:i:s') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Last Updated</dt>
                        <dd class="text-sm text-gray-900">{{ $website->updated_at->format('M d, Y H:i:s') }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-sm font-medium text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    @if($website->status != 'published')
                        <a href="{{ route('admin.websites.publish', $website) }}" 
                           class="block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-rocket mr-2"></i> Publish Website
                        </a>
                    @endif
                    <a href="{{ route('admin.websites.domains', $website) }}" 
                       class="block w-full text-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                        <i class="fas fa-globe mr-2"></i> Manage Domains
                    </a>
                    <a href="{{ route('admin.websites.analytics', $website) }}" 
                       class="block w-full text-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        <i class="fas fa-chart-line mr-2"></i> View Analytics
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection