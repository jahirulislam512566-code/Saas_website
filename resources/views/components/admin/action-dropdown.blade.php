@props(['align' => 'right'])

<div x-data="{ open: false }" @click.outside="open = false" class="relative inline-block">
    <button @click="open = !open" type="button" class="text-gray-400 hover:text-gray-600">
        <i class="fas fa-ellipsis-v"></i>
    </button>
    
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute z-50 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 py-1 {{ $align === 'right' ? 'right-0' : 'left-0' }}"
         style="display: none;"
    >
        {{ $slot }}
    </div>
</div>