@props([
    'brand' => null,
    'logo' => null,
    'fixed' => false,
    'sticky' => false,
    'transparent' => false,
    'dark' => false,
])

@php
    $bgClass = $transparent ? 'bg-transparent' : ($dark ? 'bg-gray-900' : 'bg-white');
    $textClass = $dark ? 'text-white' : 'text-gray-700';
    $borderClass = $dark ? 'border-gray-700' : 'border-gray-200';
    $shadowClass = $fixed || $sticky ? 'shadow-lg' : '';
    $positionClass = $fixed ? 'fixed top-0 left-0 right-0 z-50' : ($sticky ? 'sticky top-0 z-50' : '');
    
    $classes = trim("{$bgClass} {$borderClass} border-b {$shadowClass} {$positionClass}");
@endphp

<nav class="{{ $classes }}" {{ $attributes }}>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Brand -->
            <div class="flex items-center">
                @if($logo)
                    <img src="{{ $logo }}" alt="Logo" class="h-8 w-auto mr-2">
                @endif
                
                @if($brand)
                    <span class="font-bold text-xl {{ $textClass }}">{{ $brand }}</span>
                @else
                    {{ $slot }}
                @endif
            </div>
            
            <!-- Right side -->
            <div class="flex items-center space-x-4">
                {{ $actions ?? '' }}
            </div>
        </div>
    </div>
</nav>