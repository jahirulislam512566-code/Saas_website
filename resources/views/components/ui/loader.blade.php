{{-- resources/views/components/ui/loader.blade.php --}}
@props([
    'size' => 'md',
    'variant' => 'primary',
    'text' => null,
])

@php
    $sizes = [
        'sm' => 'w-5 h-5 border-2',
        'md' => 'w-8 h-8 border-3',
        'lg' => 'w-12 h-12 border-4',
        'xl' => 'w-16 h-16 border-4',
    ];
    $variants = [
        'primary' => 'border-[#6c5ce7] border-t-transparent',
        'white' => 'border-white border-t-transparent',
        'dark' => 'border-[#0a0a0a] border-t-transparent',
    ];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $variantClass = $variants[$variant] ?? $variants['primary'];
@endphp

<div class="flex flex-col items-center justify-center gap-3" {{ $attributes }}>
    <div class="animate-spin rounded-full {{ $sizeClass }} {{ $variantClass }}"></div>
    @if($text)
        <p class="text-sm text-[#4a4a4a]">{{ $text }}</p>
    @endif
</div>

{{-- Skeleton Loader --}}
@props(['rows' => 3, 'height' => 'h-4'])
<div class="space-y-3 animate-pulse" {{ $attributes }}>
    @for($i = 0; $i < $rows; $i++)
        <div class="{{ $height }} bg-gray-200 rounded-lg w-full"></div>
    @endfor
</div>