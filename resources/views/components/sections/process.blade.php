{{-- resources/views/components/sections/process.blade.php --}}
@props([
    'title' => 'Our Process',
    'subtitle' => 'How We Work',
    'description' => 'A transparent and collaborative approach to deliver exceptional results.',
    'steps' => [],
    'layout' => 'vertical',
])

@php
    $layoutClasses = [
        'vertical' => 'space-y-12',
        'horizontal' => 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8',
        'timeline' => 'relative space-y-8',
    ];
    $layoutClass = $layoutClasses[$layout] ?? $layoutClasses['vertical'];
@endphp

<section class="py-20 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-sm font-semibold text-[#6c5ce7] uppercase tracking-wider">{{ $subtitle }}</span>
            <h2 class="text-3xl md:text-4xl font-bold mt-2 text-[#0a0a0a]">{{ $title }}</h2>
            <p class="mt-4 text-lg text-[#4a4a4a]">{{ $description }}</p>
        </div>

        {{-- Steps --}}
        <div class="max-w-5xl mx-auto {{ $layoutClass }}">
            @foreach($steps as $index => $step)
                <div class="flex {{ $layout === 'horizontal' ? 'flex-col' : 'gap-6' }} group">
                    @if($layout === 'timeline')
                        <div class="flex gap-6">
                            <div class="flex flex-col items-center">
                                <div class="w-12 h-12 rounded-full bg-[#6c5ce7] text-white flex items-center justify-center font-bold text-lg flex-shrink-0">
                                    {{ $index + 1 }}
                                </div>
                                @if(!$loop->last)
                                    <div class="w-0.5 flex-1 bg-[#6c5ce7]/20 mt-2"></div>
                                @endif
                            </div>
                            <div class="pb-8">
                                <h4 class="text-xl font-bold text-[#0a0a0a]">{{ $step['title'] }}</h4>
                                <p class="text-[#4a4a4a] mt-2">{{ $step['description'] }}</p>
                            </div>
                        </div>
                    @elseif($layout === 'horizontal')
                        <div class="bg-gray-50 p-6 rounded-2xl text-center group-hover:shadow-lg transition-all duration-300">
                            <div class="w-16 h-16 mx-auto bg-[#6c5ce7]/10 rounded-2xl flex items-center justify-center text-3xl mb-4 group-hover:bg-[#6c5ce7] group-hover:text-white transition-colors duration-300">
                                {{ $step['icon'] ?? '📋' }}
                            </div>
                            <span class="inline-block text-xs font-semibold text-[#6c5ce7] bg-[#6c5ce7]/10 px-2.5 py-1 rounded-full mb-3">Step {{ $index + 1 }}</span>
                            <h4 class="text-lg font-bold text-[#0a0a0a]">{{ $step['title'] }}</h4>
                            <p class="text-sm text-[#4a4a4a] mt-2">{{ $step['description'] }}</p>
                        </div>
                    @else
                        <div class="flex gap-6 items-start">
                            <div class="flex-shrink-0 w-14 h-14 bg-[#6c5ce7]/10 rounded-2xl flex items-center justify-center text-3xl group-hover:bg-[#6c5ce7] group-hover:text-white transition-colors duration-300">
                                {{ $step['icon'] ?? '📋' }}
                            </div>
                            <div>
                                <span class="text-sm font-semibold text-[#6c5ce7]">Step {{ $index + 1 }}</span>
                                <h4 class="text-xl font-bold text-[#0a0a0a]">{{ $step['title'] }}</h4>
                                <p class="text-[#4a4a4a] mt-2">{{ $step['description'] }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>