@props([
    'active' => false,
    'icon' => null,
    'external' => false,
    'ariaCurrent' => 'page'
])

@php
$isActive = $active || request()->routeIs($active ?? '');
$baseClasses = 'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out';

$activeClasses = $isActive
    ? 'border-indigo-400 text-gray-900 focus:border-indigo-700'
    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:text-gray-700 focus:border-gray-300';

$classes = $baseClasses . ' ' . $activeClasses;

// Additional styling when icon is present
if ($icon) {
    $classes .= ' gap-2';
}
@endphp

<a 
    {{ $attributes->merge(['class' => $classes]) }}
    @if($isActive) aria-current="{{ $ariaCurrent }}" @endif
    @if($external) target="_blank" rel="noopener noreferrer" @endif
>
    @if($icon)
        <span class="inline-flex items-center">
            {{ $icon }}
        </span>
    @endif
    
    <span>{{ $slot }}</span>

    @if($external)
        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
        </svg>
    @endif
</a>