{{-- resources/views/components/ui/card.blade.php --}}
@props([
    'padding' => 'md',
    'shadow' => 'md',
    'hover' => false,
    'border' => true,
])

@php
    $paddings = [
        'none' => 'p-0',
        'sm' => 'p-4',
        'md' => 'p-6',
        'lg' => 'p-8',
        'xl' => 'p-10',
    ];

    $shadows = [
        'none' => '',
        'sm' => 'shadow-sm',
        'md' => 'shadow-md',
        'lg' => 'shadow-lg',
        'xl' => 'shadow-xl',
    ];

    $paddingClass = $paddings[$padding] ?? $paddings['md'];
    $shadowClass = $shadows[$shadow] ?? $shadows['md'];
    $hoverClass = $hover ? 'hover:shadow-xl transition-shadow duration-300 hover:-translate-y-1' : '';
    $borderClass = $border ? 'border border-gray-200' : '';
@endphp

<div class="bg-white rounded-2xl {{ $shadowClass }} {{ $hoverClass }} {{ $borderClass }} {{ $paddingClass }}" {{ $attributes }}>
    {{ $slot }}
</div>