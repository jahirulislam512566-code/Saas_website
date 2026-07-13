@props([
    'size' => 'md',
    'color' => 'primary',
    'fullScreen' => false,
    'overlay' => false,
    'text' => null,
])

@php
    $sizes = [
        'xs' => 'w-4 h-4 border-2',
        'sm' => 'w-6 h-6 border-2',
        'md' => 'w-8 h-8 border-3',
        'lg' => 'w-12 h-12 border-4',
        'xl' => 'w-16 h-16 border-4',
    ];
    
    $colors = [
        'primary' => 'border-primary-600',
        'secondary' => 'border-secondary-600',
        'white' => 'border-white',
        'gray' => 'border-gray-600',
        'success' => 'border-green-600',
        'danger' => 'border-red-600',
        'warning' => 'border-yellow-600',
        'info' => 'border-blue-600',
    ];
    
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $colorClass = $colors[$color] ?? $colors['primary'];
    
    $loaderClasses = "animate-spin rounded-full {$sizeClass} border-t-transparent {$colorClass}";
    $containerClasses = $fullScreen ? 'fixed inset-0 flex items-center justify-center z-50' : 'inline-flex items-center justify-center';
    
    if ($overlay) {
        $containerClasses .= ' bg-gray-900 bg-opacity-50';
    }
@endphp

@if($fullScreen)
    <div class="{{ $containerClasses }}" {{ $attributes }}>
        <div class="flex flex-col items-center space-y-3">
            <div class="{{ $loaderClasses }}"></div>
            @if($text)
                <p class="text-sm text-gray-600">{{ $text }}</p>
            @endif
        </div>
    </div>
@elseif($overlay)
    <div class="{{ $containerClasses }}" {{ $attributes }}>
        <div class="flex flex-col items-center space-y-3">
            <div class="{{ $loaderClasses }}"></div>
            @if($text)
                <p class="text-sm text-white">{{ $text }}</p>
            @endif
        </div>
    </div>
@else
    <div class="{{ $containerClasses }}" {{ $attributes }}>
        <div class="{{ $loaderClasses }}"></div>
        @if($text)
            <span class="ml-3 text-sm text-gray-600">{{ $text }}</span>
        @endif
    </div>
@endif