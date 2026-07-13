{{-- resources/views/components/website/btn-secondary.blade.php --}}
@props(['href' => null, 'type' => 'button'])

@if($href)
    <a href="{{ $href }}" 
       {{ $attributes->merge(['class' => 'inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-lg shadow-sm hover:shadow transition-all']) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" 
            {{ $attributes->merge(['class' => 'inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-lg shadow-sm hover:shadow transition-all']) }}>
        {{ $slot }}
    </button>
@endif