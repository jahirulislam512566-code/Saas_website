@props([
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
    'href' => null,
    'type' => 'button',
    'loading' => false,
])

@php
    $variants = [
        'primary' => 'bg-indigo-600 text-white hover:bg-indigo-700 focus:ring-indigo-500',
        'secondary' => 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 focus:ring-gray-500',
        'success' => 'bg-green-600 text-white hover:bg-green-700 focus:ring-green-500',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500',
        'warning' => 'bg-yellow-500 text-white hover:bg-yellow-600 focus:ring-yellow-400',
        'info' => 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500',
        'ghost' => 'text-gray-600 hover:bg-gray-100 focus:ring-gray-400',
    ];
    
    $sizes = [
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-6 py-3 text-base',
    ];
    
    $variantClass = $variants[$variant] ?? $variants['primary'];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $loadingClass = $loading ? 'opacity-50 cursor-not-allowed' : '';
    
    $classes = "inline-flex items-center justify-center font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-200 {$variantClass} {$sizeClass} {$loadingClass}";
@endphp

@if($href)
    <a href="{{ $href }}" class="{{ $classes }}" {{ $attributes }}>
        @if($loading)
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        @endif
        @if($icon)
            <i class="fas {{ $icon }} {{ $slot->isEmpty() ? '' : 'mr-2' }}"></i>
        @endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" class="{{ $classes }}" {{ $disabled ? 'disabled' : '' }} {{ $attributes }}>
        @if($loading)
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        @endif
        @if($icon)
            <i class="fas {{ $icon }} {{ $slot->isEmpty() ? '' : 'mr-2' }}"></i>
        @endif
        {{ $slot }}
    </button>
@endif