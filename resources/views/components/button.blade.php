@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
    'iconPosition' => 'left',
    'loading' => false,
    'disabled' => false,
    'fullWidth' => false,
    'href' => null,
    'target' => '_self',
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2';
    
    $variants = [
        'primary' => 'bg-primary-600 text-white hover:bg-primary-700 focus:ring-primary-500 active:bg-primary-800',
        'secondary' => 'bg-secondary-600 text-white hover:bg-secondary-700 focus:ring-secondary-500 active:bg-secondary-800',
        'success' => 'bg-green-600 text-white hover:bg-green-700 focus:ring-green-500 active:bg-green-800',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500 active:bg-red-800',
        'warning' => 'bg-yellow-500 text-white hover:bg-yellow-600 focus:ring-yellow-400 active:bg-yellow-700',
        'info' => 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500 active:bg-blue-800',
        'dark' => 'bg-gray-800 text-white hover:bg-gray-900 focus:ring-gray-700 active:bg-gray-950',
        'light' => 'bg-gray-200 text-gray-800 hover:bg-gray-300 focus:ring-gray-400 active:bg-gray-400',
        'outline-primary' => 'border-2 border-primary-600 text-primary-600 hover:bg-primary-50 focus:ring-primary-500 active:bg-primary-100',
        'outline-secondary' => 'border-2 border-secondary-600 text-secondary-600 hover:bg-secondary-50 focus:ring-secondary-500',
        'outline-danger' => 'border-2 border-red-600 text-red-600 hover:bg-red-50 focus:ring-red-500',
        'ghost' => 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 focus:ring-gray-400',
        'link' => 'text-primary-600 hover:text-primary-700 underline-offset-2 hover:underline focus:ring-primary-500',
    ];
    
    $sizes = [
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-6 py-3 text-base',
        'xl' => 'px-8 py-4 text-lg',
    ];
    
    $sizeClasses = $sizes[$size] ?? $sizes['md'];
    $variantClasses = $variants[$variant] ?? $variants['primary'];
    $disabledClasses = ($disabled || $loading) ? 'opacity-50 cursor-not-allowed pointer-events-none' : '';
    $widthClasses = $fullWidth ? 'w-full' : '';
    
    $classes = trim("{$baseClasses} {$sizeClasses} {$variantClasses} {$disabledClasses} {$widthClasses}");
@endphp

@if($href)
    <a href="{{ $href }}" target="{{ $target }}" class="{{ $classes }}" {{ $attributes }}>
        @if($loading)
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        @endif
        
        @if($icon && $iconPosition === 'left')
            <i class="fas fa-{{ $icon }} mr-2"></i>
        @endif
        
        {{ $slot }}
        
        @if($icon && $iconPosition === 'right')
            <i class="fas fa-{{ $icon }} ml-2"></i>
        @endif
    </a>
@else
    <button type="{{ $type }}" class="{{ $classes }}" {{ $disabled ? 'disabled' : '' }} {{ $attributes }}>
        @if($loading)
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        @endif
        
        @if($icon && $iconPosition === 'left')
            <i class="fas fa-{{ $icon }} mr-2"></i>
        @endif
        
        {{ $slot }}
        
        @if($icon && $iconPosition === 'right')
            <i class="fas fa-{{ $icon }} ml-2"></i>
        @endif
    </button>
@endif