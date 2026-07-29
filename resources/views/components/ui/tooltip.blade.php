{{-- resources/views/components/ui/tooltip.blade.php --}}
@props([
    'text' => 'Tooltip text',
    'position' => 'top',
    'delay' => 0,
])

@php
    $positions = [
        'top' => 'bottom-full left-1/2 -translate-x-1/2 mb-2',
        'bottom' => 'top-full left-1/2 -translate-x-1/2 mt-2',
        'left' => 'right-full top-1/2 -translate-y-1/2 mr-2',
        'right' => 'left-full top-1/2 -translate-y-1/2 ml-2',
    ];
    $positionClass = $positions[$position] ?? $positions['top'];
@endphp

<div class="relative inline-block group">
    {{ $slot }}
    <div class="absolute z-50 {{ $positionClass }} opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition-opacity duration-200" style="transition-delay: {{ $delay }}ms;">
        <div class="bg-[#0a0a0a] text-white text-sm px-3 py-1.5 rounded-lg whitespace-nowrap shadow-lg relative">
            {{ $text }}
            <div class="absolute {{ $position === 'top' ? 'bottom-[-6px] left-1/2 -translate-x-1/2 border-t-[#0a0a0a] border-t-8 border-x-transparent border-x-8' : ($position === 'bottom' ? 'top-[-6px] left-1/2 -translate-x-1/2 border-b-[#0a0a0a] border-b-8 border-x-transparent border-x-8' : ($position === 'left' ? 'right-[-6px] top-1/2 -translate-y-1/2 border-l-[#0a0a0a] border-l-8 border-y-transparent border-y-8' : 'left-[-6px] top-1/2 -translate-y-1/2 border-r-[#0a0a0a] border-r-8 border-y-transparent border-y-8')) }}"></div>
        </div>
    </div>
</div>