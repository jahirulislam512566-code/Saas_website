{{-- resources/views/admin/dashboard/widgets/activity-item.blade.php --}}
@props([
    'user' => null,
    'action' => 'action',
    'description' => 'Description',
    'icon' => 'fa-circle',
    'color' => 'gray',
    'time' => null,
])

@php
    $colors = [
        'green' => 'bg-green-100 text-green-600',
        'blue' => 'bg-blue-100 text-blue-600',
        'purple' => 'bg-purple-100 text-purple-600',
        'red' => 'bg-red-100 text-red-600',
        'yellow' => 'bg-yellow-100 text-yellow-600',
        'indigo' => 'bg-indigo-100 text-indigo-600',
        'gray' => 'bg-gray-100 text-gray-600',
    ];
    $colorClass = $colors[$color] ?? $colors['gray'];
    $userName = $user ? ($user->name ?? 'System') : 'System';
@endphp

<div class="flex items-center space-x-3 p-2 hover:bg-gray-50 rounded-lg transition-colors">
    <div class="w-8 h-8 rounded-full {{ $colorClass }} flex items-center justify-center flex-shrink-0">
        <i class="fas {{ $icon }} text-sm"></i>
    </div>
    <div class="flex-1 min-w-0">
        <p class="text-sm text-gray-900 truncate">
            <span class="font-medium">{{ $userName }}</span>
            {{ $description }}
        </p>
        <p class="text-xs text-gray-500">{{ $time ?? now()->diffForHumans() }}</p>
    </div>
    <span class="text-xs text-gray-400">{{ $action }}</span>
</div>