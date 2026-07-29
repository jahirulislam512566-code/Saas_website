<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        {{-- Section Header --}}
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-block px-4 py-1 bg-blue-100 text-blue-600 text-sm font-semibold rounded-full mb-4">
                How It Works
            </span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Build Your Website in 3 Simple Steps
            </h2>
            <p class="text-lg text-gray-600">
                Get your website live in minutes. No coding required.
            </p>
        </div>

        {{-- Steps --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
            {{-- Connecting Line --}}
            <div class="hidden md:block absolute top-1/3 left-0 right-0 h-0.5 bg-gray-200"></div>
            <div class="hidden md:block absolute top-1/3 left-0 right-0 h-0.5 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600" style="width: 66%;"></div>

            @php
                $steps = [
                    [
                        'number' => '01',
                        'title' => 'Choose a Template',
                        'description' => 'Select from our collection of beautiful templates. Choose one that fits your style.',
                        'icon' => '🎯'
                    ],
                    [
                        'number' => '02',
                        'title' => 'Customize Your Site',
                        'description' => 'Drag and drop elements, add your content, and customize every detail.',
                        'icon' => '✏️'
                    ],
                    [
                        'number' => '03',
                        'title' => 'Publish & Grow',
                        'description' => 'Hit publish and your website goes live. Start growing your online presence.',
                        'icon' => '🚀'
                    ],
                ]
            @endphp

            @foreach($steps as $index => $step)
                <div class="relative group" x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false">
                    <div class="bg-white rounded-2xl shadow-lg p-8 text-center relative z-10 group-hover:shadow-2xl transition-all duration-300 group-hover:-translate-y-2">
                        {{-- Step Number --}}
                        <div class="text-5xl font-bold text-gray-100 mb-4">
                            {{ $step['number'] }}
                        </div>
                        
                        {{-- Icon --}}
                        <div class="w-20 h-20 mx-auto bg-indigo-100 rounded-2xl flex items-center justify-center text-4xl mb-6 group-hover:scale-110 group-hover:bg-indigo-200 transition-all duration-300">
                            {{ $step['icon'] }}
                        </div>

                        {{-- Content --}}
                        <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $step['title'] }}</h3>
                        <p class="text-gray-600 leading-relaxed">{{ $step['description'] }}</p>

                        {{-- Step Indicator --}}
                        <div class="mt-6 flex items-center justify-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-indigo-600"></div>
                            <div class="w-2 h-2 rounded-full bg-gray-300"></div>
                            <div class="w-2 h-2 rounded-full bg-gray-300"></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>