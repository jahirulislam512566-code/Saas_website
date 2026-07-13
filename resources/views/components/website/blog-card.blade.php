{{-- resources/views/components/website/blog-card.blade.php --}}
@props(['post'])

<article {{ $attributes->merge(['class' => 'bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100']) }}>
    <div class="relative h-48 overflow-hidden">
        @if($post->featured_image)
            <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center">
                <i class="fas fa-image text-4xl text-indigo-300"></i>
            </div>
        @endif
        
        @if($post->category)
            <span class="absolute top-3 left-3 px-3 py-1 text-xs font-medium text-white bg-indigo-600 rounded-full">
                {{ $post->category->name }}
            </span>
        @endif
    </div>
    
    <div class="p-5">
        <div class="flex items-center gap-2 text-xs text-gray-500 mb-3">
            <i class="far fa-calendar-alt"></i>
            <span>{{ $post->created_at->format('M d, Y') }}</span>
            <span>•</span>
            <i class="far fa-clock"></i>
            <span>{{ $post->reading_time ?? '5' }} min read</span>
        </div>
        
        <a href="{{ route('blog.show', $post->slug) }}" class="block group">
            <h3 class="text-lg font-bold text-gray-900 group-hover:text-indigo-600 transition">
                {{ $post->title }}
            </h3>
            <p class="mt-2 text-gray-600 text-sm line-clamp-2">
                {{ $post->excerpt ?? Str::limit(strip_tags($post->content), 120) }}
            </p>
        </a>
        
        <div class="mt-4 flex items-center gap-3">
            @if($post->author)
                <span class="w-8 h-8 rounded-full bg-gradient-to-r from-indigo-400 to-purple-400 flex items-center justify-center text-white text-xs font-semibold">
                    {{ $post->author->initials ?? 'U' }}
                </span>
                <span class="text-sm font-medium text-gray-700">{{ $post->author->name }}</span>
            @endif
        </div>
    </div>
</article>