{{-- resources/views/components/cards/service-card.blade.php --}}
@props([
    'icon' => '🚀',
    'title' => 'Service Title',
    'description' => 'Service description goes here.',
    'link' => null,
    'linkText' => 'Learn More',
    'color' => 'primary',
    'featured' => false,
    'features' => [],
])

@php
    $colors = [
        'primary' => 'bg-[#6c5ce7]/10 text-[#6c5ce7] group-hover:bg-[#6c5ce7] group-hover:text-white',
        'blue' => 'bg-blue-100 text-blue-600 group-hover:bg-blue-600 group-hover:text-white',
        'emerald' => 'bg-emerald-100 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white',
        'amber' => 'bg-amber-100 text-amber-600 group-hover:bg-amber-600 group-hover:text-white',
        'rose' => 'bg-rose-100 text-rose-600 group-hover:bg-rose-600 group-hover:text-white',
        'indigo' => 'bg-indigo-100 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white',
    ];
    $colorClass = $colors[$color] ?? $colors['primary'];
    $featuredClass = $featured ? 'ring-2 ring-[#6c5ce7] shadow-xl shadow-[#6c5ce7]/10 -translate-y-4' : '';
@endphp

<div class="group relative bg-white rounded-2xl p-8 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 {{ $featuredClass }}">
    {{-- Featured Badge --}}
    @if($featured)
        <div class="absolute -top-3 right-6 bg-[#6c5ce7] text-white text-xs font-semibold px-3 py-1 rounded-full">
            Featured
        </div>
    @endif

    {{-- Icon --}}
    <div class="mb-5 w-16 h-16 rounded-2xl flex items-center justify-center text-2xl transition-all duration-300 {{ $colorClass }}">
        {{ $icon }}
    </div>

    {{-- Content --}}
    <h3 class="text-xl font-bold text-[#0a0a0a] mb-3">{{ $title }}</h3>
    <p class="text-[#4a4a4a] leading-relaxed mb-4">{{ $description }}</p>

    {{-- Features --}}
    @if(count($features) > 0)
        <ul class="space-y-2 mb-6">
            @foreach($features as $feature)
                <li class="flex items-center gap-2 text-sm text-[#4a4a4a]">
                    <svg class="w-4 h-4 text-[#6c5ce7] flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ $feature }}
                </li>
            @endforeach
        </ul>
    @endif

    {{-- Link --}}
    @if($link)
        <a href="{{ $link }}" class="inline-flex items-center gap-2 text-[#6c5ce7] font-medium hover:gap-3 transition-all">
            {{ $linkText }}
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
        </a>
    @endif
</div>