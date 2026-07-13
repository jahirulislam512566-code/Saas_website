@extends('admin.layouts.admin')

@section('title', 'Posts Dashboard')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">Posts Dashboard</span>
        </div>
    </li>
@endsection

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Posts Dashboard</h1>
            <p class="text-sm text-gray-500 mt-1">Manage and monitor your blog posts</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.posts.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-list mr-2"></i> All Posts
            </a>
            <a href="{{ route('admin.posts.create') }}" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-plus mr-2"></i> New Post
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Posts</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalPosts ?? 0 }}</p>
                    <p class="text-xs text-green-600">
                        <i class="fas fa-arrow-up mr-1"></i> {{ $growthPercentage ?? 0 }}% from last month
                    </p>
                </div>
                <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-file-alt text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Published</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $publishedCount ?? 0 }}</p>
                    <p class="text-xs text-green-600">
                        <i class="fas fa-arrow-up mr-1"></i> {{ $publishedGrowth ?? 0 }}% from last month
                    </p>
                </div>
                <div class="w-12 h-12 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-yellow-500 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Drafts</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $draftCount ?? 0 }}</p>
                    <p class="text-xs text-yellow-600">
                        <i class="fas fa-arrow-down mr-1"></i> {{ $draftGrowth ?? 0 }}% from last month
                    </p>
                </div>
                <div class="w-12 h-12 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center">
                    <i class="fas fa-pencil-alt text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-purple-500 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Views</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalViews ?? 0) }}</p>
                    <p class="text-xs text-purple-600">
                        <i class="fas fa-arrow-up mr-1"></i> {{ $viewsGrowth ?? 0 }}% from last month
                    </p>
                </div>
                <div class="w-12 h-12 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                    <i class="fas fa-eye text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center">
                    <i class="fas fa-plus-circle text-xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-900">Create New Post</h3>
                    <p class="text-xs text-gray-500">Start writing a new blog post</p>
                    <a href="{{ route('admin.posts.create') }}" class="text-xs text-primary-600 hover:text-primary-700 mt-1 inline-block">
                        Go to Create <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-list-ul text-xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-900">View All Posts</h3>
                    <p class="text-xs text-gray-500">Manage your blog posts</p>
                    <a href="{{ route('admin.posts.index') }}" class="text-xs text-blue-600 hover:text-blue-700 mt-1 inline-block">
                        Go to List <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="fas fa-edit text-xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-900">Recent Posts</h3>
                    <p class="text-xs text-gray-500">Edit your recent posts</p>
                    @if($recentPosts->count() > 0)
                        <a href="{{ route('admin.posts.edit', $recentPosts->first()) }}" class="text-xs text-green-600 hover:text-green-700 mt-1 inline-block">
                            Edit Latest <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    @else
                        <span class="text-xs text-gray-400">No recent posts</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Posts Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Recent Posts</h3>
                <p class="text-sm text-gray-500">Your latest blog posts</p>
            </div>
            <a href="{{ route('admin.posts.index') }}" class="text-sm text-primary-600 hover:text-primary-800">
                View All <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Views</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Published</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentPosts ?? [] as $post)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    @if($post->featuredImage)
                                        <img src="{{ $post->featuredImage->thumbnail }}" alt="" class="w-10 h-10 object-cover rounded-lg">
                                    @else
                                        <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ Str::limit($post->title, 30) }}</p>
                                        <p class="text-xs text-gray-500">{{ Str::limit($post->slug, 25) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $post->category->name ?? 'Uncategorized' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $post->status_color }}-100 text-{{ $post->status_color }}-800">
                                    {{ ucfirst($post->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ number_format($post->views ?? 0) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $post->published_at ? $post->published_at->diffForHumans() : 'Not published' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.posts.edit', $post) }}" class="text-indigo-600 hover:text-indigo-800 transition-colors" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.posts.show', $post) }}" class="text-blue-600 hover:text-blue-800 transition-colors" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($post->status != 'published')
                                        <form action="{{ route('admin.posts.publish', $post) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-800 transition-colors" title="Publish">
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-inbox text-gray-300 text-4xl mb-3 block"></i>
                                No posts found
                                <br>
                                <a href="{{ route('admin.posts.create') }}" class="text-primary-600 hover:text-primary-700">Create your first post</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Stats & Insights -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Category Distribution -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Posts by Category</h3>
            @if($categoryStats->count() > 0)
                <div class="space-y-3">
                    @foreach($categoryStats as $stat)
                        <div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-700">{{ $stat->name }}</span>
                                <span class="font-medium text-gray-900">{{ $stat->count }} posts</span>
                            </div>
                            <div class="mt-1 w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-primary-600 h-2 rounded-full transition-all duration-500" style="width: {{ ($stat->count / max(1, $totalPosts)) * 100 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">No categories with posts</p>
            @endif
        </div>

        <!-- Top Posts -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Posts by Views</h3>
            @if($topPosts->count() > 0)
                <div class="space-y-3">
                    @foreach($topPosts as $post)
                        <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded-lg transition-colors">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ Str::limit($post->title, 30) }}</p>
                                <p class="text-xs text-gray-500">{{ $post->views ?? 0 }} views</p>
                            </div>
                            <a href="{{ route('admin.posts.edit', $post) }}" class="text-indigo-600 hover:text-indigo-800">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">No posts with views</p>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-refresh stats every 5 minutes
    setInterval(function() {
        location.reload();
    }, 300000);
});
</script>
@endpush
@endsection