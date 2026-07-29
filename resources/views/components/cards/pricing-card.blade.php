{{-- resources/views/components/cards/pricing-card.blade.php --}}
@props([
    'name' => 'Pro Plan',
    'price' => '49',
    'currency' => '$',
    'period' => 'month',
    'description' => 'Perfect for growing teams',
    'features' => [],
    'ctaText' => 'Get Started',
    'ctaLink' => '#',
    'highlighted' => false,
    'badge' => null,
])

@php
    $highlightedClass = $highlighted ? 'ring-2 ring-[#6c5ce7] shadow-2xl shadow-[#6c5ce7]/20 scale-105' : '';
    $ctaClass = $highlighted 
        ? 'bg-[#6c5ce7] text-white hover:bg-[#5a4bd1] shadow-lg shadow-[#6c5ce7]/30' 
        : 'bg-gray-100 text-[#0a0a0a] hover:bg-[#6c5ce7] hover:text-white';
@endphp

<div class="relative bg-white rounded-2xl p-8 transition-all duration-300 hover:shadow-xl {{ $highlightedClass }}">
    {{-- Badge --}}
    @if($badge)
        <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-[#6c5ce7] text-white text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-wider">
            {{ $badge }}
        </div>
    @endif

    {{-- Header --}}
    <div class="text-center mb-8">
        <h3 class="text-lg font-semibold text-[#4a4a4a] uppercase tracking-wide">{{ $name }}</h3>
        <div class="mt-4">
            <span class="text-4xl font-bold text-[#0a0a0a]">{{ $currency }}{{ $price }}</span>
            <span class="text-[#4a4a4a]">/{{ $period }}</span>
        </div>
        <p class="mt-2 text-[#4a4a4a]">{{ $description }}</p>
    </div>

    {{-- Features --}}
    <ul class="space-y-3 mb-8">
        @foreach($features as $feature)
            <li class="flex items-start gap-3 text-[#4a4a4a]">
                <svg class="w-5 h-5 text-[#6c5ce7] flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ $feature }}
            </li>
        @endforeach
    </ul>

    {{-- CTA --}}
    <a href="{{ $ctaLink }}" class="block w-full text-center px-6 py-3.5 rounded-xl font-medium transition-all duration-300 {{ $ctaClass }}">
        {{ $ctaText }}
    </a>
</div>