{{-- resources/views/components/website/dropdown-link.blade.php --}}
@props(['href', 'icon' => null])

<a href="{{ $href }}" 
   {{ $attributes->merge(['class' => 'flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 transition']) }}>
    @if($icon)
        <i class="fas {{ $icon }} w-5 text-center text-gray-400"></i>
    @endif
    {{ $slot }}
</a>