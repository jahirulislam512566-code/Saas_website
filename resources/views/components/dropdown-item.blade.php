@props([
    'href' => '#',
    'icon' => null,
    'active' => false,
])

@php
    $classes = 'block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out';
    
    if ($active) {
        $classes .= ' bg-gray-100';
    }
    
    if ($icon) {
        $classes .= ' flex items-center';
    }
@endphp

@if($href === '#')
    <button type="button" class="{{ $classes }}" {{ $attributes }}>
        @if($icon)
            <i class="fas fa-{{ $icon }} mr-2 w-4 text-gray-400"></i>
        @endif
        {{ $slot }}
    </button>
@else
    <a href="{{ $href }}" class="{{ $classes }}" {{ $attributes }}>
        @if($icon)
            <i class="fas fa-{{ $icon }} mr-2 w-4 text-gray-400"></i>
        @endif
        {{ $slot }}
    </a>
@endif