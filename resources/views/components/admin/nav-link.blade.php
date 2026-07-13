@props([
    'href' => '#',
    'icon' => null,
    'active' => request()->is($href) || request()->routeIs($href),
])

@php
    $classes = $active 
        ? 'bg-gray-800 text-white' 
        : 'text-gray-300 hover:bg-gray-700 hover:text-white';
    $iconClasses = $active ? 'text-primary-500' : 'text-gray-400';
@endphp

<a href="{{ $href }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-150 {{ $classes }}" {{ $attributes }}>
    @if($icon)
        <i class="fas {{ $icon }} {{ $iconClasses }} w-5 text-center"></i>
    @endif
    <span class="ml-3 flex-1">{{ $slot }}</span>
    {{ $badge ?? '' }}
</a>