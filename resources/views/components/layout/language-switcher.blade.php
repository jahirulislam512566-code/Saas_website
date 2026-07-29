{{-- resources/views/components/layout/language-switcher.blade.php --}}
@props(['currentLocale' => app()->getLocale()])

@php
    $languages = [
        'en' => ['name' => 'English', 'flag' => '🇬🇧', 'dir' => 'ltr'],
        'bn' => ['name' => 'বাংলা', 'flag' => '🇧🇩', 'dir' => 'ltr'],
        'es' => ['name' => 'Español', 'flag' => '🇪🇸', 'dir' => 'ltr'],
        'fr' => ['name' => 'Français', 'flag' => '🇫🇷', 'dir' => 'ltr'],
        'de' => ['name' => 'Deutsch', 'flag' => '🇩🇪', 'dir' => 'ltr'],
    ];
@endphp

<div class="relative" x-data="{ open: false }">
    <button 
        @click="open = !open"
        class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-[#4a4a4a] hover:text-[#6c5ce7] transition-colors rounded-lg hover:bg-[#6c5ce7]/5"
        aria-label="{{ __('messages.language') }}"
    >
        <span>{{ $languages[$currentLocale]['flag'] ?? '🌐' }}</span>
        <span class="hidden sm:inline">{{ $languages[$currentLocale]['name'] ?? 'Language' }}</span>
        <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <div 
        x-show="open" 
        @click.away="open = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="absolute right-0 top-full mt-1 w-48 bg-white rounded-xl shadow-xl border border-gray-100 py-1.5 z-50"
        x-cloak
    >
        @foreach($languages as $code => $language)
            @if($code !== $currentLocale)
                <a 
                    href="{{ route('language.switch', $code) }}"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm text-[#4a4a4a] hover:bg-[#6c5ce7]/5 hover:text-[#6c5ce7] transition-colors no-underline"
                >
                    <span>{{ $language['flag'] }}</span>
                    <span>{{ $language['name'] }}</span>
                </a>
            @else
                <span class="flex items-center gap-3 px-4 py-2.5 text-sm text-[#6c5ce7] bg-[#6c5ce7]/10 cursor-default">
                    <span>{{ $language['flag'] }}</span>
                    <span>{{ $language['name'] }}</span>
                    <span class="ml-auto text-xs">✓</span>
                </span>
            @endif
        @endforeach
    </div>
</div>

@push('styles')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush