{{-- resources/views/components/ui/accordion-item.blade.php --}}
@props([
    'index' => 0,
    'title' => 'Accordion Item',
    'open' => false,
])

<div class="border-b border-gray-200 last:border-0" 
     x-data="{ isOpen: {{ $open ? 'true' : 'false' }} }"
     x-init="isOpen = openItems.includes({{ $index }})">
    <button class="flex items-center justify-between w-full py-4 text-left bg-transparent border-none cursor-pointer hover:text-[#6c5ce7] transition-colors group"
            x-on:click="isOpen = !isOpen; if (isOpen) { openItems.push({{ $index }}) } else { openItems = openItems.filter(i => i !== {{ $index }}) }">
        <span class="font-medium text-[#0a0a0a] group-hover:text-[#6c5ce7] transition-colors">{{ $title }}</span>
        <span class="text-xl transition-transform duration-300" 
              x-bind:class="isOpen ? 'rotate-180' : ''">▼</span>
    </button>
    <div x-show="isOpen" x-collapse>
        <div class="pb-4 text-[#4a4a4a] leading-relaxed">
            {{ $slot }}
        </div>
    </div>
</div>