@props([
    'src' => null,
    'name' => null,
    'size' => 'md',
    'rounded' => true,
    'status' => null,
    'statusPosition' => 'bottom-right',
])

@php
    $sizes = [
        'xs' => 'w-6 h-6 text-xs',
        'sm' => 'w-8 h-8 text-sm',
        'md' => 'w-10 h-10 text-base',
        'lg' => 'w-12 h-12 text-lg',
        'xl' => 'w-16 h-16 text-xl',
        '2xl' => 'w-24 h-24 text-3xl',
    ];
    
    $sizeClasses = $sizes[$size] ?? $sizes['md'];
    $roundedClass = $rounded ? 'rounded-full' : 'rounded-lg';
    
    $statusSizes = [
        'xs' => 'w-1.5 h-1.5',
        'sm' => 'w-2 h-2',
        'md' => 'w-3 h-3',
        'lg' => 'w-3.5 h-3.5',
        'xl' => 'w-4 h-4',
        '2xl' => 'w-5 h-5',
    ];
    
    $statusSize = $statusSizes[$size] ?? $statusSizes['md'];
    
    $statusColors = [
        'online' => 'bg-green-500',
        'offline' => 'bg-gray-500',
        'away' => 'bg-yellow-500',
        'busy' => 'bg-red-500',
    ];
    
    $statusColor = $statusColors[$status] ?? 'bg-gray-500';
    $statusPositions = [
        'bottom-right' => 'bottom-0 right-0',
        'bottom-left' => 'bottom-0 left-0',
        'top-right' => 'top-0 right-0',
        'top-left' => 'top-0 left-0',
    ];
    $statusPositionClass = $statusPositions[$statusPosition] ?? $statusPositions['bottom-right'];
    
    // Generate initials from name
    $initials = '';
    if ($name) {
        $words = explode(' ', $name);
        foreach ($words as $word) {
            if (strlen($word) > 0 && strlen($initials) < 2) {
                $initials .= strtoupper($word[0]);
            }
        }
    }
    
    $colors = [
        'bg-red-500', 'bg-blue-500', 'bg-green-500', 'bg-yellow-500', 
        'bg-purple-500', 'bg-pink-500', 'bg-indigo-500', 'bg-orange-500',
        'bg-teal-500', 'bg-cyan-500', 'bg-rose-500', 'bg-violet-500',
    ];
    
    $colorIndex = $name ? abs(crc32($name)) % count($colors) : 0;
    $bgColor = $colors[$colorIndex];
@endphp

<div class="relative inline-flex">
    @if($src)
        <img src="{{ $src }}" 
             alt="{{ $name ?? 'Avatar' }}" 
             class="{{ $sizeClasses }} {{ $roundedClass }} object-cover border-2 border-white shadow-sm"
             {{ $attributes }}
        >
    @else
        <div class="{{ $sizeClasses }} {{ $roundedClass }} {{ $bgColor }} flex items-center justify-center text-white font-medium border-2 border-white shadow-sm">
            {{ $initials }}
        </div>
    @endif
    
    @if($status)
        <span class="absolute {{ $statusPositionClass }} block {{ $statusSize }} {{ $statusColor }} rounded-full ring-2 ring-white"></span>
    @endif
</div>