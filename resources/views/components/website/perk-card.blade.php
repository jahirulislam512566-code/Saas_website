{{-- resources/views/components/website/perk-card.blade.php --}}
@props(['icon', 'title', 'description', 'icon_color' => 'blue'])

@php
    $colors = [
        'green' => 'bg-green-50 text-green-600',
        'blue' => 'bg-blue-50 text-blue-600',
        'purple' => 'bg-purple-50 text-purple-600',
        'orange' => 'bg-orange-50 text-orange-600',
        'red' => 'bg-red-50 text-red-600',
        'teal' => 'bg-teal-50 text-teal-600',
        'indigo' => 'bg-indigo-50 text-indigo-600',
        'yellow' => 'bg-yellow-50 text-yellow-600',
    ];
    $colorClass = $colors[$icon_color] ?? $colors['blue'];
@endphp

<div class="p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-200 text-center">
    <div class="flex items-center justify-center w-14 h-14 {{ $colorClass }} rounded-2xl mx-auto mb-4">
        <i class="fas {{ $icon }} text-2xl"></i>
    </div>
    <h4 class="font-semibold text-gray-900">{{ $title }}</h4>
    <p class="mt-1 text-sm text-gray-600">{{ $description }}</p>
</div>