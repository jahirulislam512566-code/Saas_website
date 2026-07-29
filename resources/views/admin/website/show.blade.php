<!-- admin/websites/show.blade.php -->
@extends('layout.app')

@section('title', 'Website Details')
@section('page-title', 'Website Details')
@section('page-subtitle', 'View complete website information and performance')

@section('content')
<div x-data="websiteDetails()" x-init="init()">
    <!-- Website Header -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden mb-6">
        <div class="p-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-xl bg-indigo-100 dark:bg-indigo-900/20 flex items-center justify-center">
                        <i class="fas fa-globe text-2xl text-indigo-600 dark:text-indigo-400"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-3">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $website->name }}</h3>
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                         {{ $website->status === 'published' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 
                                            ($website->status === 'draft' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' :
                                            ($website->status === 'archived' ? 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400' :
                                            ($website->status === 'suspended' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' :
                                            'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400'))) }}">
                                {{ ucfirst($website->status) }}
                            </span>
                            @if($website->is_featured)
                                <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400 rounded-full">
                                    <i class="fas fa-star mr-1"></i>Featured
                                </span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $website->domain }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            <i class="fas fa-user mr-1"></i> {{ $website->customer->name ?? 'Unknown' }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    @if($website->status === 'published')
                        <a href="https://{{ $website->domain }}" target="_blank" 
                           class="px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg shadow-sm transition-all">
                            <i class="fas fa-external-link-alt mr-1.5"></i>Visit Website
                        </a>
                    @endif
                    <a href="{{ route('admin.websites.edit', $website) }}" 
                       class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition-all">
                        <i class="fas fa-edit mr-1.5"></i>Edit
                    </a>
                    <a href="{{ route('admin.websites.analytics', $website) }}" 
                       class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors">
                        <i class="fas fa-chart-line mr-1.5"></i>Analytics
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Screenshot -->
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-200 dark:border-gray-800">
                    <h4 class="font-semibold text-gray-900 dark:text-white">Preview</h4>
                </div>
                <div class="p-4">
                    @if($website->screenshot)
                        <img src="{{ $website->screenshot }}" alt="{{ $website->name }}" 
                             class="w-full rounded-lg border border-gray-200 dark:border-gray-700">
                    @else
                        <div class="w-full h-64 bg-gray-100 dark:bg-gray-800 rounded-lg flex items-center justify-center">
                            <div class="text-center">
                                <i class="fas fa-globe text-4xl text-gray-400 dark:text-gray-600 mb-2"></i>
                                <p class="text-gray-500 dark:text-gray-400">No screenshot available</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Website Details -->
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-6">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Website Details</h4>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</label>
                            <p class="mt-1 text-gray-900 dark:text-white">{{ $website->name }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Domain</label>
                            <p class="mt-1 text-gray-900 dark:text-white">{{ $website->domain }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Customer</label>
                            <p class="mt-1 text-gray-900 dark:text-white">{{ $website->customer->name ?? 'Unknown' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</label>
                            <p class="mt-1 text-gray-900 dark:text-white capitalize">{{ $website->status }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Created</label>
                            <p class="mt-1 text-gray-900 dark:text-white">{{ $website->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Last Updated</label>
                            <p class="mt-1 text-gray-900 dark:text-white">{{ $website->updated_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>

                    @if($website->description)
                        <div class="mt-4">
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Description</label>
                            <p class="mt-1 text-gray-700 dark:text-gray-300">{{ $website->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Pages & Content -->
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
                    <h4 class="font-semibold text-gray-900 dark:text-white">Pages</h4>
                    <a href="#" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($pages as $page)
                        <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $page->title }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $page->slug }}</p>
                                </div>
                                <span class="text-xs px-2 py-0.5 rounded-full
                                             {{ $page->status === 'published' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 
                                                'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' }}">
                                    {{ ucfirst($page->status) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                            <p>No pages found</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Performance Stats -->
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-6">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Performance</h4>
                    
                    <div class="space-y-4">
                        <div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Total Views</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ number_format($website->views ?? 0) }}</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5 mt-1">
                                <div class="h-1.5 rounded-full bg-indigo-500" style="width: 75%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Unique Visitors</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ number_format($website->unique_visitors ?? 0) }}</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5 mt-1">
                                <div class="h-1.5 rounded-full bg-green-500" style="width: 60%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Bounce Rate</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $website->bounce_rate ?? 0 }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5 mt-1">
                                <div class="h-1.5 rounded-full bg-yellow-500" style="width: {{ $website->bounce_rate ?? 0 }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Avg Session</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $website->avg_session ?? 0 }}s</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5 mt-1">
                                <div class="h-1.5 rounded-full bg-purple-500" style="width: 45%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-6">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h4>
                    
                    <div class="space-y-2">
                        @if($website->status === 'published')
                            <button @click="unpublishWebsite()" 
                                    class="w-full flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-yellow-600 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-800 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 rounded-lg transition-colors">
                                <i class="fas fa-eye-slash"></i> Unpublish
                            </button>
                        @elseif($website->status === 'draft')
                            <button @click="publishWebsite()" 
                                    class="w-full flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors">
                                <i class="fas fa-rocket"></i> Publish Website
                            </button>
                        @endif
                        
                        <button @click="duplicateWebsite()" 
                                class="w-full flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors">
                            <i class="fas fa-copy"></i> Duplicate Website
                        </button>
                        
                        <button @click="generateScreenshot()" 
                                class="w-full flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors">
                            <i class="fas fa-camera"></i> Generate Screenshot
                        </button>
                        
                        <button @click="deleteWebsite()" 
                                class="w-full flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-red-600 dark:text-red-400 border border-red-200 dark:border-red-800 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                            <i class="fas fa-trash-alt"></i> Delete Website
                        </button>
                    </div>
                </div>
            </div>

            <!-- SEO Information -->
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-6">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">SEO Information</h4>
                    
                    <div class="space-y-3 text-sm">
                        <div>
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Meta Title</label>
                            <p class="mt-1 text-gray-900 dark:text-white">{{ $website->meta_title ?? 'Not set' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Meta Description</label>
                            <p class="mt-1 text-gray-600 dark:text-gray-400">{{ $website->meta_description ?? 'Not set' }}</p>
                        </div>
                        @if($website->keywords)
                            <div>
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Keywords</label>
                                <div class="mt-1 flex flex-wrap gap-1">
                                    @foreach(explode(',', $website->keywords) as $keyword)
                                        <span class="px-2 py-0.5 text-xs bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 rounded-full">
                                            {{ trim($keyword) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function websiteDetails() {
    return {
        websiteId: {{ $website->id }},
        
        publishWebsite() {
            if (confirm('Publish this website?')) {
                this.updateStatus('published');
            }
        },
        
        unpublishWebsite() {
            if (confirm('Unpublish this website?')) {
                this.updateStatus('draft');
            }
        },
        
        updateStatus(status) {
            fetch(`{{ route('admin.websites.status', $website) }}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => console.error('Error updating status:', error));
        },
        
        duplicateWebsite() {
            if (confirm('Duplicate this website?')) {
                fetch(`{{ route('admin.websites.duplicate', $website) }}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = `{{ route('admin.websites.edit', '') }}/${data.website_id}`;
                    }
                })
                .catch(error => console.error('Error duplicating website:', error));
            }
        },
        
        generateScreenshot() {
            fetch(`{{ route('admin.websites.screenshot', $website) }}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Failed to generate screenshot. Please try again.');
                }
            })
            .catch(error => console.error('Error generating screenshot:', error));
        },
        
        deleteWebsite() {
            if (confirm('Are you sure you want to delete this website? This action cannot be undone.')) {
                fetch(`{{ route('admin.websites.destroy', $website) }}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = '{{ route("admin.websites.index") }}';
                    }
                })
                .catch(error => console.error('Error deleting website:', error));
            }
        }
    }
}
</script>
@endpush