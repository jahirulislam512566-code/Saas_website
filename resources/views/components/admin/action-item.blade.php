@props([
    'href' => '#',
    'icon' => null,
    'method' => null,
    'confirm' => null,
])

<a href="{{ $href }}"
   @if($method) 
       onclick="event.preventDefault(); if(confirm('{{ $confirm ?? 'Are you sure?' }}')) { document.getElementById('delete-form-{{ $method }}').submit(); }"
   @endif
   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
   {{ $attributes }}
>
    @if($icon)
        <i class="fas {{ $icon }} w-4 text-gray-400 mr-2"></i>
    @endif
    {{ $slot }}
</a>

@if($method)
    <form id="delete-form-{{ $method }}" action="{{ $href }}" method="POST" class="hidden">
        @csrf
        @method($method)
    </form>
@endif