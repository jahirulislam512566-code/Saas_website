{{-- resources/views/components/admin/badge.blade.php --}}
@props([
    'count' => 0,
    'color' => 'blue',
    'size' => 'sm'
])

@if($count > 0)
    @php
        $colors = [
            'blue' => 'bg-blue-500',
            'green' => 'bg-green-500',
            'yellow' => 'bg-yellow-500',
            'red' => 'bg-red-500',
            'purple' => 'bg-purple-500',
            'gray' => 'bg-gray-500',
        ];
        
        $sizes = [
            'sm' => 'px-1.5 py-0.5 text-[10px] min-w-[18px]',
            'md' => 'px-2 py-1 text-xs min-w-[22px]',
            'lg' => 'px-2.5 py-1.5 text-sm min-w-[26px]',
        ];
        
        $colorClass = $colors[$color] ?? 'bg-blue-500';
        $sizeClass = $sizes[$size] ?? 'px-1.5 py-0.5 text-[10px] min-w-[18px]';
    @endphp
    
    <span class="inline-flex items-center justify-center rounded-full font-semibold text-white {{ $colorClass }} {{ $sizeClass }} {{ $attributes->get('class') }}">
        {{ $count }}
    </span>
@endif