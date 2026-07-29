@props([
    'href' => '#',
    'icon' => null,
    'active' => false,
    'badge' => null,
    'badgeColor' => 'danger',
])

<a href="{{ $href }}" 
   class="flex items-center px-3 py-2 text-sm rounded-lg transition-all duration-200 {{ 
       $active 
           ? 'bg-slate-800 text-white' 
           : 'text-slate-300 hover:bg-slate-800 hover:text-white' 
   }}"
   {{ $attributes->merge(['class' => '']) }}>
    
    @if($icon)
        <i class="fas fa-{{ $icon }} w-5"></i>
    @endif
    
    <span class="{{ $icon ? 'ml-2' : '' }}">{{ $slot }}</span>
    
    @if($badge)
        <span class="ml-auto bg-{{ $badgeColor }}-500 text-white text-xs px-2 py-0.5 rounded-full">
            {{ $badge }}
        </span>
    @endif
</a>