@props([
    'title' => null,
    'subtitle' => null,
    'header' => null,
    'footer' => null,
    'padding' => true,
    'hoverable' => false,
    'shadow' => 'md',
    'border' => false,
])

@php
    $shadows = [
        'none' => 'shadow-none',
        'sm' => 'shadow-sm',
        'md' => 'shadow',
        'lg' => 'shadow-lg',
        'xl' => 'shadow-xl',
    ];
    
    $shadowClass = $shadows[$shadow] ?? $shadows['md'];
    $hoverClass = $hoverable ? 'hover:shadow-lg transition-shadow duration-200' : '';
    $borderClass = $border ? 'border border-gray-200' : '';
    $paddingClass = $padding ? 'p-6' : '';
    
    $classes = trim("bg-white rounded-lg {$shadowClass} {$hoverClass} {$borderClass}");
@endphp

<div class="{{ $classes }}" {{ $attributes }}>
    @if($header)
        <div class="border-b border-gray-200 px-6 py-4">
            {{ $header }}
        </div>
    @elseif($title || $subtitle)
        <div class="border-b border-gray-200 px-6 py-4">
            @if($title)
                <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
            @endif
            @if($subtitle)
                <p class="text-sm text-gray-500">{{ $subtitle }}</p>
            @endif
        </div>
    @endif
    
    <div class="{{ $paddingClass }}">
        {{ $slot }}
    </div>
    
    @if($footer)
        <div class="border-t border-gray-200 px-6 py-4 bg-gray-50 rounded-b-lg">
            {{ $footer }}
        </div>
    @endif
</div>