{{-- resources/views/components/website/benefit-card.blade.php --}}
@props(['icon', 'title', 'description', 'color' => 'blue'])

@php
    $colors = [
        'red' => 'text-red-500 bg-red-50',
        'blue' => 'text-blue-500 bg-blue-50',
        'green' => 'text-green-500 bg-green-50',
        'purple' => 'text-purple-500 bg-purple-50',
        'orange' => 'text-orange-500 bg-orange-50',
        'teal' => 'text-teal-500 bg-teal-50',
    ];
    $colorClass = $colors[$color] ?? $colors['blue'];
@endphp

<div class="p-6 bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-200">
    <div class="flex items-center justify-center w-12 h-12 {{ $colorClass }} rounded-xl mb-4">
        <i class="fas {{ $icon }} text-xl"></i>
    </div>
    <h3 class="text-xl font-semibold text-gray-900">{{ $title }}</h3>
    <p class="mt-2 text-gray-600">{{ $description }}</p>
</div>