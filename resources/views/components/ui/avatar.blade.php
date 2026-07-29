{{-- resources/views/components/ui/avatar.blade.php --}}
@props([
    'src' => null,
    'alt' => 'Avatar',
    'size' => 'md',
    'fallback' => null,
    'online' => false,
])

@php
    $sizes = [
        'sm' => 'w-8 h-8 text-xs',
        'md' => 'w-10 h-10 text-sm',
        'lg' => 'w-14 h-14 text-lg',
        'xl' => 'w-20 h-20 text-2xl',
        '2xl' => 'w-28 h-28 text-4xl',
    ];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $onlineClass = $online ? 'ring-2 ring-white ring-offset-2 ring-offset-[#6c5ce7]' : '';
@endphp

<div class="relative inline-block">
    <div class="relative rounded-full overflow-hidden {{ $sizeClass }} bg-gradient-to-br from-[#6c5ce7]/20 to-[#6c5ce7]/5 {{ $onlineClass }}">
        @if($src)
            <img src="{{ $src }}" alt="{{ $alt }}" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full flex items-center justify-center text-[#6c5ce7] font-semibold">
                {{ $fallback ?? strtoupper(substr($alt, 0, 2)) }}
            </div>
        @endif
    </div>
    @if($online)
        <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-400 rounded-full ring-2 ring-white"></span>
    @endif
</div>