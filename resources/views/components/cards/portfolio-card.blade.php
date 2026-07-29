{{-- resources/views/components/cards/portfolio-card.blade.php --}}
@props([
    'image' => null,
    'title' => 'Project Title',
    'category' => 'Category',
    'link' => '#',
    'lightbox' => false,
    'tags' => [],
])

<div class="group relative overflow-hidden rounded-2xl bg-white shadow-md hover:shadow-xl transition-all duration-300">
    {{-- Image --}}
    <div class="relative overflow-hidden aspect-[4/3] bg-gray-100">
        @if($image)
            <img src="{{ $image }}" alt="{{ $title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
        @else
            <div class="w-full h-full flex items-center justify-center text-4xl text-gray-300 bg-gradient-to-br from-gray-50 to-gray-100">
                🖼️
            </div>
        @endif

        {{-- Overlay --}}
        <div class="absolute inset-0 bg-gradient-to-t from-[#0a0a0a]/80 via-[#0a0a0a]/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
            <div class="flex gap-3">
                @if($lightbox)
                    <button class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center text-white hover:bg-white hover:text-[#0a0a0a] transition-colors" aria-label="View project">
                        🔍
                    </button>
                @endif
                @if($link)
                    <a href="{{ $link }}" class="w-12 h-12 bg-[#6c5ce7] rounded-full flex items-center justify-center text-white hover:bg-[#5a4bd1] transition-colors" aria-label="View project">
                        →
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Content --}}
    <div class="p-6">
        <span class="text-xs font-semibold text-[#6c5ce7] uppercase tracking-wider">{{ $category }}</span>
        <h3 class="text-xl font-bold text-[#0a0a0a] mt-1.5">{{ $title }}</h3>
        
        @if(count($tags) > 0)
            <div class="flex flex-wrap gap-2 mt-3">
                @foreach($tags as $tag)
                    <span class="px-2.5 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">{{ $tag }}</span>
                @endforeach
            </div>
        @endif
    </div>
</div>