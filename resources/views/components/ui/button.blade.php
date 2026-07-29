{{-- resources/views/components/ui/button.blade.php --}}
@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'type' => 'button',
    'disabled' => false,
    'loading' => false,
    'fullWidth' => false,
])

@php
    $variants = [
        'primary' => 'bg-[#6c5ce7] text-white hover:bg-[#5a4bd1] shadow-lg shadow-[#6c5ce7]/30',
        'secondary' => 'bg-white text-[#0a0a0a] border-2 border-gray-200 hover:border-[#6c5ce7] hover:text-[#6c5ce7]',
        'outline' => 'border-2 border-[#6c5ce7] text-[#6c5ce7] hover:bg-[#6c5ce7] hover:text-white',
        'ghost' => 'text-[#4a4a4a] hover:text-[#6c5ce7] hover:bg-[#6c5ce7]/10',
        'danger' => 'bg-red-500 text-white hover:bg-red-600 shadow-lg shadow-red-500/30',
        'success' => 'bg-emerald-500 text-white hover:bg-emerald-600 shadow-lg shadow-emerald-500/30',
        'dark' => 'bg-[#0a0a0a] text-white hover:bg-[#1a1a1a]',
    ];

    $sizes = [
        'sm' => 'px-4 py-2 text-sm',
        'md' => 'px-6 py-3 text-base',
        'lg' => 'px-8 py-4 text-lg',
        'xl' => 'px-10 py-5 text-xl',
        'icon' => 'p-2.5',
    ];

    $baseClasses = 'inline-flex items-center justify-center font-medium rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#6c5ce7]/50 disabled:opacity-50 disabled:cursor-not-allowed gap-2';
    $widthClass = $fullWidth ? 'w-full' : '';
    $variantClasses = $variants[$variant] ?? $variants['primary'];
    $sizeClasses = $sizes[$size] ?? $sizes['md'];
@endphp

@if($href)
    <a href="{{ $href }}" class="{{ $baseClasses }} {{ $variantClasses }} {{ $sizeClasses }} {{ $widthClass }}" {{ $attributes }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" class="{{ $baseClasses }} {{ $variantClasses }} {{ $sizeClasses }} {{ $widthClass }}" {{ $disabled ? 'disabled' : '' }} {{ $attributes }}>
        @if($loading)
            <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        @endif
        {{ $slot }}
    </button>
@endif