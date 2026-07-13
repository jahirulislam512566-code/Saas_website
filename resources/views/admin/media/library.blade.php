{{-- resources/views/admin/media/library.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Media Library')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">Media</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- ===== HEADER ===== -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Media Library</h1>
            <p class="text-sm text-gray-500 mt-1">Manage all your media files</p>
        </div>
        <div class="flex items-center space-x-3">
            <div class="flex items-center space-x-2 bg-white rounded-lg shadow-sm px-3 py-2 border border-gray-200">
                <i class="fas fa-th-large text-gray-400 text-sm"></i>
                <select id="viewMode" class="border-0 bg-transparent text-sm focus:ring-0">
                    <option value="grid">Grid View</option>
                    <option value="list">List View</option>
                </select>
            </div>
            <a href="{{ route('admin.media.upload') }}" 
               class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-upload mr-2"></i> Upload
            </a>
        </div>
    </div>

    <!-- ===== STATS ===== -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Files</p>
                    <p class="text-xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center">
                    <i class="fas fa-file"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Images</p>
                    <p class="text-xl font-bold text-blue-600">{{ $stats['images'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-image"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Videos</p>
                    <p class="text-xl font-bold text-purple-600">{{ $stats['videos'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                    <i class="fas fa-video"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Storage Used</p>
                    <p class="text-xl font-bold text-green-600">{{ $stats['storage'] ?? '0 MB' }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="fas fa-database"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== FILTERS ===== -->
    <div class="bg-white rounded-xl shadow-sm p-4">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" placeholder="Search media..." value="{{ request('search') }}" 
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                <select name="type" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">All Types</option>
                    <option value="image" {{ request('type') == 'image' ? 'selected' : '' }}>Images</option>
                    <option value="video" {{ request('type') == 'video' ? 'selected' : '' }}>Videos</option>
                    <option value="document" {{ request('type') == 'document' ? 'selected' : '' }}>Documents</option>
                    <option value="audio" {{ request('type') == 'audio' ? 'selected' : '' }}>Audio</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                <select name="sort" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Date Added</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                    <option value="size" {{ request('sort') == 'size' ? 'selected' : '' }}>Size</option>
                </select>
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    <i class="fas fa-search mr-2"></i> Filter
                </button>
                <a href="{{ route('admin.media.library') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-times mr-2"></i> Clear
                </a>
            </div>
        </form>
    </div>

    <!-- ===== MEDIA GRID ===== -->
    <div id="media-container" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @forelse($media as $item)
            <div class="group relative bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-all">
                <!-- Media Preview -->
                <div class="aspect-square bg-gray-100 overflow-hidden">
                    @if($item->is_image)
                        <img src="{{ $item->thumbnail_url ?? $item->url }}" 
                             alt="{{ $item->name }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @elseif($item->is_video)
                        <div class="w-full h-full flex items-center justify-center bg-gray-800">
                            <i class="fas fa-play-circle text-white text-4xl opacity-50"></i>
                            <video class="absolute inset-0 w-full h-full object-cover opacity-30">
                                <source src="{{ $item->url }}" type="{{ $item->mime_type }}">
                            </video>
                        </div>
                    @elseif($item->is_audio)
                        <div class="w-full h-full flex items-center justify-center bg-gray-800">
                            <i class="fas fa-music text-white text-4xl opacity-50"></i>
                        </div>
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-100">
                            <i class="fas fa-file text-4xl text-gray-400"></i>
                        </div>
                    @endif
                    
                    <!-- File Type Badge -->
                    <span class="absolute top-2 left-2 px-2 py-0.5 rounded text-xs font-medium bg-black/50 text-white backdrop-blur-sm">
                        {{ strtoupper($item->extension) }}
                    </span>
                    
                    <!-- Selection Checkbox -->
                    <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <input type="checkbox" class="media-checkbox h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                    </div>
                </div>
                
                <!-- Media Info -->
                <div class="p-3">
                    <p class="text-sm font-medium text-gray-900 truncate" title="{{ $item->name }}">{{ $item->name }}</p>
                    <div class="flex items-center justify-between mt-1">
                        <span class="text-xs text-gray-500">{{ $item->formatted_size }}</span>
                        <span class="text-xs text-gray-400">{{ $item->created_at->diffForHumans() }}</span>
                    </div>
                    
                    <!-- Actions -->
                    <div class="mt-2 flex items-center justify-between opacity-0 group-hover:opacity-100 transition-opacity">
                        <div class="flex items-center space-x-1">
                            <a href="{{ $item->url }}" target="_blank" 
                               class="p-1.5 text-gray-400 hover:text-blue-600 transition-colors rounded-lg hover:bg-blue-50" 
                               title="View">
                                <i class="fas fa-eye text-sm"></i>
                            </a>
                            <button onclick="copyUrl('{{ $item->url }}')" 
                                    class="p-1.5 text-gray-400 hover:text-green-600 transition-colors rounded-lg hover:bg-green-50" 
                                    title="Copy URL">
                                <i class="fas fa-copy text-sm"></i>
                            </button>
                            <button onclick="editMedia('{{ $item->id }}')" 
                                    class="p-1.5 text-gray-400 hover:text-primary-600 transition-colors rounded-lg hover:bg-primary-50" 
                                    title="Edit">
                                <i class="fas fa-pen text-sm"></i>
                            </button>
                            <button onclick="deleteMedia('{{ $item->id }}', '{{ $item->name }}')" 
                                    class="p-1.5 text-gray-400 hover:text-red-600 transition-colors rounded-lg hover:bg-red-50" 
                                    title="Delete">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>
                        <button onclick="insertMedia('{{ $item->url }}')" 
                                class="text-xs text-primary-600 hover:text-primary-700 font-medium">
                            Insert
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="text-center py-12 bg-white rounded-xl shadow-sm">
                    <i class="fas fa-photo-video text-4xl text-gray-300 mb-3 block"></i>
                    <p class="text-lg font-medium text-gray-900">No media found</p>
                    <p class="text-sm text-gray-500 mt-1">Upload your first media file</p>
                    <a href="{{ route('admin.media.upload') }}" class="inline-flex items-center mt-4 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                        <i class="fas fa-upload mr-2"></i> Upload Files
                    </a>
                </div>
            </div>
        @endforelse
    </div>
    
    <!-- ===== PAGINATION ===== -->
    <div class="flex items-center justify-between">
        @if($media instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="text-sm text-gray-500">
                Showing {{ $media->firstItem() ?? 0 }} to {{ $media->lastItem() ?? 0 }} of {{ $media->total() }} results
            </div>
            <div>
                {{ $media->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-sm text-gray-500">
                Showing {{ $media->count() }} results
            </div>
        @endif
    </div>
</div>

<!-- Edit Media Modal -->
<div x-data="{ show: false, mediaId: null }" 
     x-show="show"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto" 
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" @click="show = false">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="edit-media-form" method="POST">
                @csrf
                @method('PUT')
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Edit Media</h3>
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    File Name
                                </label>
                                <input type="text" name="name" id="edit-media-name"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            </div>
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Alt Text
                                </label>
                                <input type="text" name="alt_text" id="edit-media-alt"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            </div>
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Description
                                </label>
                                <textarea name="description" id="edit-media-description" rows="3"
                                          class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Save Changes
                    </button>
                    <button type="button" @click="show = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="delete-media-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
    // Copy URL to clipboard
    function copyUrl(url) {
        navigator.clipboard.writeText(url).then(() => {
            showToast('URL copied to clipboard!', 'success');
        });
    }

    // Edit media
    function editMedia(id) {
        fetch(`/admin/media/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const modal = document.querySelector('[x-data]').__x.$data;
                    modal.show = true;
                    modal.mediaId = id;
                    document.getElementById('edit-media-form').action = `/admin/media/${id}`;
                    document.getElementById('edit-media-name').value = data.data.name;
                    document.getElementById('edit-media-alt').value = data.data.alt_text || '';
                    document.getElementById('edit-media-description').value = data.data.description || '';
                }
            });
    }

    // Delete media
    function deleteMedia(id, name) {
        if (confirm(`Delete "${name}"?`)) {
            const form = document.getElementById('delete-media-form');
            form.action = `/admin/media/${id}`;
            form.submit();
        }
    }

    // Insert media (for editor integration)
    function insertMedia(url) {
        if (window.opener) {
            window.opener.insertMedia(url);
            window.close();
        } else {
            showToast('Media URL: ' + url, 'info');
        }
    }

    // View mode toggle
    document.getElementById('viewMode').addEventListener('change', function() {
        const container = document.getElementById('media-container');
        if (this.value === 'list') {
            container.className = 'space-y-2';
            // Transform grid to list view
            // Implementation depends on your UI framework
        } else {
            container.className = 'grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4';
        }
    });

    // Toast notification
    function showToast(message, type) {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-4 right-4 px-4 py-2 rounded-lg text-white ${
            type === 'success' ? 'bg-green-500' : 
            type === 'error' ? 'bg-red-500' : 
            'bg-blue-500'
        } shadow-lg z-50`;
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
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
    [x-cloak] { display: none !important; }
</style>
@endpush
@endsection