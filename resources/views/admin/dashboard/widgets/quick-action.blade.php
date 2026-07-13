{{-- resources/views/admin/dashboard/widgets/quick-action.blade.php --}}
@props([
    'title' => 'Action',
    'description' => 'Description',
    'icon' => 'fa-plus',
    'color' => 'blue',
    'route' => '#',
])

@php
    $colors = [
        'blue' => 'bg-blue-50 hover:bg-blue-100',
        'green' => 'bg-green-50 hover:bg-green-100',
        'purple' => 'bg-purple-50 hover:bg-purple-100',
        'orange' => 'bg-orange-50 hover:bg-orange-100',
        'red' => 'bg-red-50 hover:bg-red-100',
        'indigo' => 'bg-indigo-50 hover:bg-indigo-100',
    ];
    $colorClass = $colors[$color] ?? $colors['blue'];
    
    $iconColors = [
        'blue' => 'bg-blue-100 text-blue-600 group-hover:bg-blue-200',
        'green' => 'bg-green-100 text-green-600 group-hover:bg-green-200',
        'purple' => 'bg-purple-100 text-purple-600 group-hover:bg-purple-200',
        'orange' => 'bg-orange-100 text-orange-600 group-hover:bg-orange-200',
        'red' => 'bg-red-100 text-red-600 group-hover:bg-red-200',
        'indigo' => 'bg-indigo-100 text-indigo-600 group-hover:bg-indigo-200',
    ];
    $iconColor = $iconColors[$color] ?? $iconColors['blue'];
@endphp

<a href="{{ $route }}" class="flex items-center p-3 {{ $colorClass }} rounded-lg transition-colors group">
    <div class="w-8 h-8 rounded-full {{ $iconColor }} flex items-center justify-center transition-colors">
        <i class="fas {{ $icon }}"></i>
    </div>
    <div class="ml-3">
        <p class="text-sm font-medium text-gray-900">{{ $title }}</p>
        <p class="text-xs text-gray-500">{{ $description }}</p>
    </div>
</a>