{{-- resources/views/components/cards/stat-card.blade.php --}}
@props([
    'label' => 'Stat Label',
    'value' => '0',
    'icon' => '📊',
    'change' => null,
    'changeType' => 'increase',
    'color' => 'primary',
    'description' => null,
    'link' => null,
])

@php
    $colors = [
        'primary' => 'bg-[#6c5ce7]/10 text-[#6c5ce7]',
        'blue' => 'bg-blue-100 text-blue-600',
        'emerald' => 'bg-emerald-100 text-emerald-600',
        'amber' => 'bg-amber-100 text-amber-600',
        'rose' => 'bg-rose-100 text-rose-600',
        'indigo' => 'bg-indigo-100 text-indigo-600',
    ];
    $colorClass = $colors[$color] ?? $colors['primary'];
    $changeColor = $changeType === 'increase' ? 'text-emerald-600' : 'text-rose-600';
    $changeIcon = $changeType === 'increase' ? '↑' : '↓';
@endphp

<div class="bg-white rounded-2xl p-6 shadow-md hover:shadow-xl transition-all duration-300 group">
    <div class="flex items-start justify-between">
        {{-- Icon --}}
        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl {{ $colorClass }}">
            {{ $icon }}
        </div>

        {{-- Change Indicator --}}
        @if($change !== null)
            <span class="text-sm font-semibold {{ $changeColor }} bg-gray-50 px-2.5 py-1 rounded-full">
                {{ $changeIcon }} {{ $change }}%
            </span>
        @endif
    </div>

    {{-- Value --}}
    <div class="mt-4">
        <p class="text-3xl font-bold text-[#0a0a0a]">{{ $value }}</p>
        <p class="text-sm font-medium text-[#4a4a4a] mt-0.5">{{ $label }}</p>
    </div>

    {{-- Description --}}
    @if($description)
        <p class="text-sm text-[#4a4a4a] mt-2">{{ $description }}</p>
    @endif

    {{-- Link --}}
    @if($link)
        <a href="{{ $link }}" class="inline-flex items-center gap-1.5 text-sm text-[#6c5ce7] font-medium mt-3 hover:gap-2.5 transition-all">
            View Details →
        </a>
    @endif
</div>