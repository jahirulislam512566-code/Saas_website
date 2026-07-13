{{-- resources/views/components/website/faq-item.blade.php --}}
@props([
    'question' => 'Frequently Asked Question',
    'answer' => 'Answer to the frequently asked question goes here.',
    'open' => false,
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden']) }}>
    <button 
        x-data="{ expanded: {{ $open ? 'true' : 'false' }} }"
        @click="expanded = !expanded" 
        class="w-full px-6 py-4 flex items-center justify-between text-left hover:bg-gray-50 transition"
    >
        <span class="text-lg font-semibold text-gray-900">{{ $question }}</span>
        <span class="flex-shrink-0 ml-4">
            <svg class="w-5 h-5 text-gray-500 transition-transform duration-200" :class="{ 'rotate-180': expanded }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </span>
    </button>
    <div x-show="expanded" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="px-6 pb-4">
        <p class="text-gray-600 leading-relaxed">{{ $answer }}</p>
    </div>
</div>