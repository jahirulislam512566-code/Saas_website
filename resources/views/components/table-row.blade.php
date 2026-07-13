@props([
    'striped' => true,
    'hoverable' => true,
    'clickable' => false,
    'href' => null,
])

@php
    $classes = 'transition-colors duration-150';
    
    if ($striped) {
        $classes .= ' even:bg-gray-50';
    }
    
    if ($hoverable) {
        $classes .= ' hover:bg-gray-100';
    }
    
    if ($clickable) {
        $classes .= ' cursor-pointer';
    }
@endphp

@if($href)
    <a href="{{ $href }}" class="block {{ $classes }}">
        <tr class="{{ $classes }}" {{ $attributes }}>
            {{ $slot }}
        </tr>
    </a>
@else
    <tr class="{{ $classes }}" {{ $attributes }}>
        {{ $slot }}
    </tr>
@endif