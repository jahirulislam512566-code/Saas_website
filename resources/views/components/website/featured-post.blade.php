{{-- resources/views/components/website/featured-post.blade.php --}}
@props(['post'])

<div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition overflow-hidden border border-gray-100">
    <div class="md:flex">
        <div class="md:w-2/5 h-48 md:h-auto bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center overflow-hidden">
            @if($post->featured_image)
                <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
            @else
                <span class="bg-white/60 px-4 py-2 rounded-full text-indigo-500 text-sm font-medium">Featured</span>
            @endif
        </div>
        <div class="p-6 md:p-8 md:w-3/5">
            <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500 mb-2">
                @if($post->category)
                    <span class="category-badge">{{ $post->category->name }}</span>
                    <span>•</span>
                @endif
                <span>{{ $post->created_at->format('M d, Y') }}</span>
                <span>•</span>
                <span>{{ $post->reading_time ?? '5' }} min read</span>
            </div>
            <a href="{{ route('website.blog.show', $post->slug) }}" class="block group">
                <h3 class="text-xl md:text-2xl font-bold text-gray-900 group-hover:text-indigo-600 transition">{{ $post->title }}</h3>
                <p class="mt-2 text-gray-600 leading-relaxed">{{ $post->excerpt ?? Str::limit(strip_tags($post->content), 150) }}</p>
            </a>
            <div class="mt-4 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-indigo-200 flex items-center justify-center text-indigo-800 text-xs font-bold">
                    {{ $post->author->initials ?? 'JD' }}
                </div>
                <span class="text-sm font-medium text-gray-700">{{ $post->author->name ?? 'Jane Doe' }}</span>
                <span class="text-xs text-gray-400">•</span>
                <span class="text-xs text-gray-500">{{ $post->author->role ?? 'Team' }}</span>
            </div>
        </div>
    </div>
</div>