{{-- resources/views/components/restaurant-nav-link.blade.php --}}
@props(['href', 'active' => false])

<a href="{{ $href }}" 
   {{ $attributes->merge(['class' => 'px-3 py-2 text-sm font-medium transition-all duration-300 relative group ' . ($active ? 'text-gold-500' : 'text-white hover:text-gold-400')]) }}>
    {{ $slot }}
    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gold-500 transition-all duration-300 group-hover:w-full {{ $active ? 'w-full' : '' }}"></span>
</a>