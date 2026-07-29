<section class="py-20 bg-gray-50" x-data="{ currentSlide: 0, totalSlides: 6 }">
    <div class="container mx-auto px-4">
        {{-- Section Header --}}
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="inline-block px-4 py-1.5 bg-indigo-100 text-indigo-600 text-sm font-semibold rounded-full mb-4">
                Templates
            </span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Choose Your Perfect Template
            </h2>
            <p class="text-lg text-gray-600">
                Professionally designed templates for every industry
            </p>
        </div>

        {{-- Carousel --}}
        <div class="relative">
            {{-- Navigation Buttons --}}
            <button @click="currentSlide = (currentSlide - 1 + totalSlides) % totalSlides" 
                    class="absolute left-0 top-1/2 -translate-y-1/2 z-20 p-3 bg-white rounded-full shadow-lg hover:shadow-xl transition-all duration-300 -ml-4">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>

            <button @click="currentSlide = (currentSlide + 1) % totalSlides" 
                    class="absolute right-0 top-1/2 -translate-y-1/2 z-20 p-3 bg-white rounded-full shadow-lg hover:shadow-xl transition-all duration-300 -mr-4">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>

            {{-- Carousel Items --}}
            <div class="overflow-hidden">
                <div class="flex transition-transform duration-500 ease-out" 
                     :style="`transform: translateX(-${currentSlide * (100/3)}%)`">
                    
                    @php
                        $templates = [
                            ['name' => 'Business Pro', 'category' => 'Business', 'image' => asset('images/templates/business.jpg')],
                            ['name' => 'Restaurant', 'category' => 'Restaurant', 'image' => asset('images/templates/restaurant.jpg')],
                            ['name' => 'Portfolio', 'category' => 'Portfolio', 'image' => asset('images/templates/portfolio.jpg')],
                            ['name' => 'School', 'category' => 'School', 'image' => asset('images/templates/school.jpg')],
                            ['name' => 'Doctor', 'category' => 'Doctor', 'image' => asset('images/templates/doctor.jpg')],
                            ['name' => 'Agency', 'category' => 'Agency', 'image' => asset('images/templates/agency.jpg')],
                        ]
                    @endphp

                    @foreach($templates as $template)
                        <div class="flex-shrink-0 w-full md:w-1/2 lg:w-1/3 p-3">
                            <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300">
                                <img src="{{ $template['image'] }}" alt="{{ $template['name'] }}" class="w-full h-48 object-cover">
                                <div class="p-6">
                                    <div class="flex items-center justify-between mb-2">
                                        <h3 class="text-lg font-bold text-gray-900">{{ $template['name'] }}</h3>
                                        <span class="text-xs font-medium text-gray-400">{{ $template['category'] }}</span>
                                    </div>
                                    <div class="flex gap-2">
                                        <button class="flex-1 px-4 py-2 border-2 border-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors text-sm">
                                            Preview
                                        </button>
                                        <button class="flex-1 px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors text-sm">
                                            Use Template
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Dots --}}
            <div class="flex justify-center gap-2 mt-8">
                @for($i = 0; $i < 6; $i++)
                    <button @click="currentSlide = {{ $i }}" 
                            class="w-2.5 h-2.5 rounded-full transition-all duration-300"
                            :class="currentSlide === {{ $i }} ? 'w-8 bg-indigo-600' : 'bg-gray-300 hover:bg-gray-400'">
                    </button>
                @endfor
            </div>
        </div>
    </div>
</section>