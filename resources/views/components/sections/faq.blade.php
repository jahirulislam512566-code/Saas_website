{{-- resources/views/components/sections/faq.blade.php --}}
@props([
    'title' => 'Frequently Asked Questions',
    'subtitle' => 'FAQ',
    'description' => 'Find answers to common questions about our services and process.',
    'items' => [],
    'columns' => 1,
])

@php
    $colClasses = [
        1 => 'max-w-3xl mx-auto',
        2 => 'grid grid-cols-1 lg:grid-cols-2 gap-8',
    ];
    $colClass = $colClasses[$columns] ?? $colClasses[1];
@endphp

<section class="py-20 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-sm font-semibold text-[#6c5ce7] uppercase tracking-wider">{{ $subtitle }}</span>
            <h2 class="text-3xl md:text-4xl font-bold mt-2 text-[#0a0a0a]">{{ $title }}</h2>
            <p class="mt-4 text-lg text-[#4a4a4a]">{{ $description }}</p>
        </div>

        {{-- FAQ Items --}}
        <div class="{{ $colClass }}">
            <x-ui.accordion>
                @foreach($items as $index => $item)
                    <x-ui.accordion-item :index="$index" :title="$item['question']">
                        {{ $item['answer'] }}
                    </x-ui.accordion-item>
                @endforeach
            </x-ui.accordion>
        </div>
    </div>
</section>