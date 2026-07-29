{{-- resources/views/components/website/api-card.blade.php --}}
@props(['method', 'endpoint', 'description', 'link'])

@php
    $methodColors = [
        'GET' => 'bg-green-100 text-green-700',
        'POST' => 'bg-blue-100 text-blue-700',
        'PUT' => 'bg-yellow-100 text-yellow-700',
        'DELETE' => 'bg-red-100 text-red-700',
        'PATCH' => 'bg-purple-100 text-purple-700',
    ];
    $colorClass = $methodColors[$method] ?? 'bg-gray-100 text-gray-700';
@endphp

<a href="{{ $link }}" class="block p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors group">
    <div class="flex items-center gap-3">
        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-mono font-semibold {{ $colorClass }}">
            {{ $method }}
        </span>
        <code class="flex-1 text-sm text-gray-900 font-mono group-hover:text-primary-600 transition-colors">
            {{ $endpoint }}
        </code>
        <svg class="w-4 h-4 text-gray-400 group-hover:text-primary-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </div>
    @if($description)
        <p class="mt-1 text-sm text-gray-600 ml-[76px]">{{ $description }}</p>
    @endif
</a>