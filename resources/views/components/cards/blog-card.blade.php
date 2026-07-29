{{-- resources/views/components/cards/blog-card.blade.php --}}
@props([
    'image' => null,
    'title' => 'Blog Post Title',
    'excerpt' => 'Blog post excerpt goes here...',
    'author' => 'Author Name',
    'authorAvatar' => null,
    'date' => 'Jan 1, 2024',
    'category' => 'Category',
    'readingTime' => '5 min read',
    'tags' => [],
    'link' => '#',
    'featured' => false,
])

@php
    $featuredClass = $featured ? 'ring-2 ring-[#6c5ce7] shadow-xl' : '';
    $imageHeight = $featured ? 'aspect-[16/9]' : 'aspect-[4/3]';
@endphp

<article class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group {{ $featuredClass }}">
    {{-- Image --}}
    <div class="relative overflow-hidden {{ $imageHeight }} bg-gray-100">
        @if($image)
            <img src="{{ $image }}" alt="{{ $title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
        @else
            <div class="w-full h-full flex items-center justify-center text-4xl text-gray-300 bg-gradient-to-br from-gray-50 to-gray-100">
                📝
            </div>
        @endif
        @if($category)
            <span class="absolute top-4 left-4 bg-[#6c5ce7] text-white text-xs font-semibold px-3 py-1 rounded-full">
                {{ $category }}
            </span>
        @endif
        @if($featured)
            <span class="absolute top-4 right-4 bg-amber-500 text-white text-xs font-semibold px-3 py-1 rounded-full">
                ⭐ Featured
            </span>
        @endif
    </div>

    {{-- Content --}}
    <div class="p-6">
        <div class="flex items-center gap-4 text-xs text-[#4a4a4a] mb-3">
            <span>{{ $date }}</span>
            <span>•</span>
            <span>{{ $readingTime }}</span>
        </div>

        <h3 class="text-xl font-bold text-[#0a0a0a] hover:text-[#6c5ce7] transition-colors mb-2">
            <a href="{{ $link }}">{{ $title }}</a>
        </h3>

        <p class="text-[#4a4a4a] leading-relaxed mb-4">{{ $excerpt }}</p>

        {{-- Tags --}}
        @if(count($tags) > 0)
            <div class="flex flex-wrap gap-2 mb-4">
                @foreach($tags as $tag)
                    <span class="px-2.5 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">#{{ $tag }}</span>
                @endforeach
            </div>
        @endif

        {{-- Author --}}
        <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#6c5ce7] to-[#a78bfa] flex items-center justify-center text-white font-semibold text-sm">
                {{ $authorAvatar ? '<img src="' . $authorAvatar . '" alt="' . $author . '" class="w-full h-full object-cover rounded-full">' : strtoupper(substr($author, 0, 2)) }}
            </div>
            <div>
                <p class="text-sm font-medium text-[#0a0a0a]">{{ $author }}</p>
                <a href="{{ $link }}" class="text-xs text-[#6c5ce7] hover:underline">Read full article →</a>
            </div>
        </div>
    </div>
</article>