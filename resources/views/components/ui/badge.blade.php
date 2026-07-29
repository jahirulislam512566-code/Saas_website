{{-- resources/views/components/ui/badge.blade.php --}}
@props([
    'variant' => 'default',
    'size' => 'md',
    'rounded' => 'full',
])

@php
    $variants = [
        'default' => 'bg-gray-100 text-gray-800',
        'primary' => 'bg-[#6c5ce7]/10 text-[#6c5ce7]',
        'success' => 'bg-emerald-100 text-emerald-700',
        'danger' => 'bg-red-100 text-red-700',
        'warning' => 'bg-amber-100 text-amber-700',
        'info' => 'bg-blue-100 text-blue-700',
        'dark' => 'bg-[#0a0a0a] text-white',
    ];

    $sizes = [
        'sm' => 'px-2.5 py-0.5 text-xs',
        'md' => 'px-3 py-1 text-sm',
        'lg' => 'px-4 py-1.5 text-base',
    ];

    $roundedClass = $rounded === 'full' ? 'rounded-full' : 'rounded-lg';
    $variantClasses = $variants[$variant] ?? $variants['default'];
    $sizeClasses = $sizes[$size] ?? $sizes['md'];
@endphp

<span class="inline-flex items-center font-medium {{ $roundedClass }} {{ $variantClasses }} {{ $sizeClasses }}" {{ $attributes }}>
    {{ $slot }}
</span>