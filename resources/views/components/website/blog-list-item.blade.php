{{-- resources/views/components/website/blog-list-item.blade.php --}}
@props(['post'])

<div {{ $attributes->merge(['class' => 'bg-white rounded-xl shadow-sm hover:shadow-md transition border border-gray-100 p-5']) }}>
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex-1">
            <div class="flex items-center gap-3 text-xs text-gray-500 mb-2">
                @if($post->category)
                    <span class="category-badge">{{ $post->category->name }}</span>
                    <span>•</span>
                @endif
                <span>{{ $post->created_at->format('M d, Y') }}</span>
                <span>•</span>
                <span>{{ $post->reading_time ?? '5' }} min read</span>
            </div>
            <a href="{{ route('website.blog.show', $post->slug) }}" class="block group">
                <h4 class="text-lg font-bold text-gray-900 group-hover:text-indigo-600 transition">{{ $post->title }}</h4>
                <p class="mt-1 text-sm text-gray-600 line-clamp-2">{{ $post->excerpt ?? Str::limit(strip_tags($post->content), 120) }}</p>
            </a>
            <div class="mt-2 flex items-center gap-3 text-xs text-gray-500">
                <span>by {{ $post->author->name ?? 'Anonymous' }}</span>
            </div>
        </div>
        <div class="flex flex-wrap gap-1 text-xs">
            @foreach($post->tags as $tag)
                <a href="{{ route('website.blog.tag', $tag->slug) }}" class="badge-pill bg-slate-100 text-slate-700 hover:bg-indigo-100 hover:text-indigo-700 transition">
                    #{{ $tag->name }}
                </a>
            @endforeach
        </div>
    </div>
</div>