{{-- resources/views/components/website/service-card.blade.php --}}
@props([
    'icon' => 'fa-code',
    'title' => 'Service Title',
    'description' => 'Service description goes here.',
    'link' => '#',
    'compact' => false
])

@php
    $classes = $compact 
        ? 'bg-white rounded-xl p-4 shadow-sm hover:shadow-md transition border border-gray-100 group' 
        : 'bg-white rounded-2xl p-6 shadow-sm hover:shadow-xl transition border border-gray-100 group hover:-translate-y-1';
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
        <i class="fas {{ $icon }} text-xl"></i>
    </div>
    <h3 class="mt-4 text-lg font-semibold text-gray-900">{{ $title }}</h3>
    <p class="mt-2 text-gray-600 text-sm leading-relaxed">{{ $description }}</p>
    @if($link && !$compact)
        <a href="{{ $link }}" class="mt-4 inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-800 transition">
            Learn More
            <i class="fas fa-arrow-right ml-2"></i>
        </a>
    @endif
</div>