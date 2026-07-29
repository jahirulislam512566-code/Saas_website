{{-- resources/views/components/website/doc-list-item.blade.php --}}
@props(['title', 'description', 'category', 'link', 'icon' => null])

<a href="{{ $link }}" class="block p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors group">
    <div class="flex items-start justify-between">
        <div class="flex-1">
            <div class="flex items-center gap-2">
                @if($icon)
                    <i class="fas {{ $icon }} text-primary-600 text-sm"></i>
                @endif
                <h4 class="font-medium text-gray-900 group-hover:text-primary-600 transition-colors">
                    {{ $title }}
                </h4>
            </div>
            @if($description)
                <p class="mt-1 text-sm text-gray-600">{{ $description }}</p>
            @endif
            <span class="mt-2 inline-block text-xs text-gray-400">{{ $category }}</span>
        </div>
        <svg class="w-5 h-5 text-gray-400 group-hover:text-primary-600 transition-colors flex-shrink-0 ml-4 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </div>
</a>