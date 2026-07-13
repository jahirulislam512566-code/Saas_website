{{-- resources/views/admin/dashboard/widgets/stats-card.blade.php --}}
@props([
    'title' => 'Stat',
    'value' => 0,
    'icon' => 'fa-chart-line',
    'color' => 'primary',
    'growth' => null,
    'growthLabel' => 'vs last month',
])

@php
    $colors = [
        'primary' => 'bg-primary-100 text-primary-600',
        'green' => 'bg-green-100 text-green-600',
        'blue' => 'bg-blue-100 text-blue-600',
        'purple' => 'bg-purple-100 text-purple-600',
        'red' => 'bg-red-100 text-red-600',
        'yellow' => 'bg-yellow-100 text-yellow-600',
        'indigo' => 'bg-indigo-100 text-indigo-600',
    ];
    $colorClass = $colors[$color] ?? $colors['primary'];
    
    $growthColor = isset($growth) && $growth > 0 ? 'text-green-600' : 'text-red-600';
    $growthIcon = isset($growth) && $growth > 0 ? 'fa-arrow-up' : 'fa-arrow-down';
@endphp

<div class="bg-white rounded-xl shadow-sm p-4 card-hover">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500">{{ $title }}</p>
            <p class="text-2xl font-bold text-gray-900">{{ $value }}</p>
            @if(isset($growth))
                <span class="text-xs {{ $growthColor }}">
                    <i class="fas {{ $growthIcon }} mr-1"></i> {{ abs($growth) }}% {{ $growthLabel }}
                </span>
            @endif
        </div>
        <div class="w-12 h-12 rounded-full {{ $colorClass }} flex items-center justify-center">
            <i class="fas {{ $icon }} text-xl"></i>
        </div>
    </div>
</div>