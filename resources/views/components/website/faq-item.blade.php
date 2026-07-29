{{-- resources/views/components/website/faq-item.blade.php --}}
@props(['question', 'answer'])

<div x-data="{ open: false }" class="border border-gray-200 rounded-lg overflow-hidden">
    <button 
        @click="open = !open" 
        class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors"
    >
        <span class="font-medium text-gray-900">{{ $question }}</span>
        <svg class="w-5 h-5 text-gray-400 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>
    <div x-show="open" x-collapse class="px-6 pb-4">
        <p class="text-gray-600">{{ $answer }}</p>
    </div>
</div>