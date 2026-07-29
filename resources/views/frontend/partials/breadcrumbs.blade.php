{{-- resources/views/frontend/partials/breadcrumbs.blade.php --}}
@props([
    'items' => [],
    'separator' => '/',
])

<nav class="flex items-center gap-2 text-sm text-[#4a4a4a] py-3" aria-label="Breadcrumb">
    <ol class="flex flex-wrap items-center gap-2 list-none p-0 m-0">
        <li>
            <a href="{{ route('home') }}" class="text-[#6c5ce7] hover:underline no-underline flex items-center gap-1">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                </svg>
                Home
            </a>
        </li>

        @foreach($items as $item)
            <li class="flex items-center gap-2">
                <span class="text-[#4a4a4a]">{{ $separator }}</span>
                @if($loop->last || !isset($item['url']))
                    <span class="font-medium text-[#0a0a0a]" aria-current="page">{{ $item['label'] }}</span>
                @else
                    <a href="{{ $item['url'] }}" class="text-[#4a4a4a] hover:text-[#6c5ce7] transition-colors no-underline">
                        {{ $item['label'] }}
                    </a>
                @endif
            </li>
        @endforeach
    </ol>
</nav>