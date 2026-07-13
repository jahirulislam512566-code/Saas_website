{{-- resources/views/admin/categories/show.blade.php --}}
@extends('admin.layouts.admin')

@section('title', $category->name)

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <a href="{{ route('admin.categories.index') }}" class="text-gray-500 hover:text-gray-700">Categories</a>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-700">{{ $category->name }}</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- ===== HEADER ===== -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background-color: {{ $category->color_hex }}20;">
                <i class="fas {{ $category->icon ?? 'fa-folder' }} text-2xl" style="color: {{ $category->color_hex }};"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $category->name }}</h1>
                <div class="flex items-center space-x-3 mt-1">
                    <span class="text-sm text-gray-500">Slug: {{ $category->slug }}</span>
                    <span class="text-gray-300">|</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $category->is_active ? 'Active' : 'Inactive' }}
                    </span>
                    @if($category->is_featured)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-star mr-1"></i> Featured
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.categories.edit', $category) }}" 
               class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-pen mr-2"></i> Edit
            </a>
            <a href="{{ route('admin.categories.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
        </div>
    </div>

    <!-- ===== STATS ===== -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Total Posts</p>
            <p class="text-xl font-bold text-gray-900">{{ $stats['total_posts'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Published</p>
            <p class="text-xl font-bold text-green-600">{{ $stats['published_posts'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Drafts</p>
            <p class="text-xl font-bold text-yellow-600">{{ $stats['draft_posts'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Subcategories</p>
            <p class="text-xl font-bold text-purple-600">{{ $stats['children_count'] ?? 0 }}</p>
        </div>
    </div>

    <!-- ===== MAIN CONTENT ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Description -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-sm font-medium text-gray-900 mb-3">Description</h3>
                @if($category->description)
                    <p class="text-gray-600">{{ $category->description }}</p>
                @else
                    <p class="text-gray-400 italic">No description provided</p>
                @endif
            </div>

            <!-- Recent Posts -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-sm font-medium text-gray-900">Recent Posts</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($recentPosts ?? [] as $post)
                        <div class="px-6 py-3 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $post->title }}</p>
                                    <p class="text-xs text-gray-500">By {{ $post->user->name ?? 'Unknown' }} · {{ $post->created_at->diffForHumans() }}</p>
                                </div>
                                <a href="{{ route('admin.posts.show', $post) }}" class="text-gray-400 hover:text-blue-600">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-4 text-center text-gray-500">
                            No posts in this category
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Column: Meta -->
        <div class="space-y-6">
            <!-- Category Info -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-sm font-medium text-gray-900 mb-4">Category Info</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-xs text-gray-500">ID</dt>
                        <dd class="text-sm text-gray-900">#{{ $category->id }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Parent</dt>
                        <dd class="text-sm text-gray-900">
                            @if($category->parent)
                                <a href="{{ route('admin.categories.show', $category->parent) }}" class="text-primary-600 hover:text-primary-700">
                                    {{ $category->parent->name }}
                                </a>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Path</dt>
                        <dd class="text-sm text-gray-900">{{ $category->full_path }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Level</dt>
                        <dd class="text-sm text-gray-900">{{ $category->level }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Sort Order</dt>
                        <dd class="text-sm text-gray-900">{{ $category->sort_order }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Created</dt>
                        <dd class="text-sm text-gray-900">{{ $category->created_at->format('M d, Y H:i:s') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Last Updated</dt>
                        <dd class="text-sm text-gray-900">{{ $category->updated_at->format('M d, Y H:i:s') }}</dd>
                    </div>
                </dl>
            </div>

            <!-- SEO Info -->
            @if($category->meta_title || $category->meta_description || $category->meta_keywords)
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-sm font-medium text-gray-900 mb-4">SEO Information</h3>
                    <dl class="space-y-3">
                        @if($category->meta_title)
                            <div>
                                <dt class="text-xs text-gray-500">Meta Title</dt>
                                <dd class="text-sm text-gray-900">{{ $category->meta_title }}</dd>
                            </div>
                        @endif
                        @if($category->meta_description)
                            <div>
                                <dt class="text-xs text-gray-500">Meta Description</dt>
                                <dd class="text-sm text-gray-900">{{ $category->meta_description }}</dd>
                            </div>
                        @endif
                        @if($category->meta_keywords)
                            <div>
                                <dt class="text-xs text-gray-500">Meta Keywords</dt>
                                <dd class="text-sm text-gray-900">{{ $category->meta_keywords }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            @endif

            <!-- Image -->
            @if($category->image)
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-sm font-medium text-gray-900 mb-3">Category Image</h3>
                    <img src="{{ Storage::disk('public')->url($category->image) }}" 
                         alt="{{ $category->name }}" 
                         class="w-full rounded-lg">
                </div>
            @endif

            <!-- Subcategories -->
            @if($category->children->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-sm font-medium text-gray-900 mb-3">Subcategories</h3>
                    <ul class="space-y-2">
                        @foreach($category->children as $child)
                            <li>
                                <a href="{{ route('admin.categories.show', $child) }}" 
                                   class="flex items-center space-x-2 text-sm text-gray-700 hover:text-primary-600 transition-colors">
                                    <i class="fas {{ $child->icon ?? 'fa-folder' }} text-xs" style="color: {{ $child->color_hex }};"></i>
                                    <span>{{ $child->name }}</span>
                                    <span class="text-xs text-gray-400">({{ $child->post_count }} posts)</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection