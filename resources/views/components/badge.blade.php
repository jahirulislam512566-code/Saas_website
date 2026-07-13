@props([
    'variant' => 'primary',
    'size' => 'md',
    'rounded' => 'full',
    'dot' => false,
    'dotColor' => null,
])

@php
    $variants = [
        'primary' => 'bg-primary-100 text-primary-800',
        'secondary' => 'bg-secondary-100 text-secondary-800',
        'success' => 'bg-green-100 text-green-800',
        'danger' => 'bg-red-100 text-red-800',
        'warning' => 'bg-yellow-100 text-yellow-800',
        'info' => 'bg-blue-100 text-blue-800',
        'dark' => 'bg-gray-800 text-white',
        'light' => 'bg-gray-100 text-gray-800',
        'purple' => 'bg-purple-100 text-purple-800',
        'pink' => 'bg-pink-100 text-pink-800',
        'indigo' => 'bg-indigo-100 text-indigo-800',
    ];
    
    $sizes = [
        'sm' => 'px-2 py-0.5 text-xs',
        'md' => 'px-2.5 py-0.5 text-sm',
        'lg' => 'px-3 py-1 text-base',
    ];
    
    $roundedStyles = [
        'none' => 'rounded-none',
        'sm' => 'rounded-sm',
        'md' => 'rounded',
        'lg' => 'rounded-lg',
        'full' => 'rounded-full',
    ];
    
    $variantClasses = $variants[$variant] ?? $variants['primary'];
    $sizeClasses = $sizes[$size] ?? $sizes['md'];
    $roundedClass = $roundedStyles[$rounded] ?? $roundedStyles['full'];
    $dotClasses = $dot ? 'pl-2.5 pr-3.5' : '';
    
    $dotColors = [
        'primary' => 'bg-primary-500',
        'secondary' => 'bg-secondary-500',
        'success' => 'bg-green-500',
        'danger' => 'bg-red-500',
        'warning' => 'bg-yellow-500',
        'info' => 'bg-blue-500',
        'dark' => 'bg-gray-800',
        'light' => 'bg-gray-500',
        'purple' => 'bg-purple-500',
        'pink' => 'bg-pink-500',
        'indigo' => 'bg-indigo-500',
    ];
    
    $dotColorClass = $dotColor ? $dotColors[$dotColor] ?? $dotColors['primary'] : $dotColors[$variant] ?? $dotColors['primary'];
    
    $classes = trim("inline-flex items-center font-medium {$variantClasses} {$sizeClasses} {$roundedClass} {$dotClasses}");
@endphp

<span class="{{ $classes }}" {{ $attributes }}>
    @if($dot)
        <span class="relative flex items-center mr-1.5">
            <span class="animate-ping absolute inline-flex h-2 w-2 rounded-full {{ $dotColorClass }} opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2 w-2 {{ $dotColorClass }}"></span>
        </span>
    @endif
    {{ $slot }}
</span>