@props(['variant' => 'primary', 'href' => '#'])

@php
    $classes = match($variant) {
        'primary' => 'bg-indigo-600 text-white hover:bg-indigo-700',
        'secondary' => 'bg-white text-gray-700 border border-gray-200 hover:shadow-lg',
        default => 'bg-gray-100 text-gray-700 hover:bg-gray-200',
    };
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => "px-6 py-3 rounded-xl font-semibold transition transform hover:scale-105 $classes"]) }}>
    {{ $slot }}
</a>