<section id="testimonials" class="py-20 bg-white">
    <div class="container mx-auto px-4">
        {{-- Section Header --}}
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-block px-4 py-1 bg-pink-100 text-pink-600 text-sm font-semibold rounded-full mb-4">
                Testimonials
            </span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                What Our Customers Say
            </h2>
            <p class="text-lg text-gray-600">
                Join thousands of satisfied businesses who built their website with us
            </p>
        </div>

        {{-- Testimonials Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
                $testimonials = [
                    [
                        'name' => 'Sarah Johnson',
                        'role' => 'CEO, Creative Agency',
                        'image' => 'https://ui-avatars.com/api/?name=Sarah+Johnson&background=4F46E5&color=fff&size=100',
                        'quote' => 'This platform transformed our business. We built our entire website in just 2 days without any coding knowledge.',
                        'rating' => 5
                    ],
                    [
                        'name' => 'Michael Chen',
                        'role' => 'Founder, Tech Start',
                        'image' => 'https://ui-avatars.com/api/?name=Michael+Chen&background=7C3AED&color=fff&size=100',
                        'quote' => 'The drag and drop builder is incredibly intuitive. We\'ve launched 3 websites and each one looks professional.',
                        'rating' => 5
                    ],
                    [
                        'name' => 'Emily Davis',
                        'role' => 'Marketing Director',
                        'image' => 'https://ui-avatars.com/api/?name=Emily+Davis&background=059669&color=fff&size=100',
                        'quote' => 'Customer support is amazing. They helped us customize our site exactly how we wanted it. Highly recommend!',
                        'rating' => 5
                    ],
                ]
            @endphp

            @foreach($testimonials as $testimonial)
                <div class="bg-gray-50 rounded-2xl p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
                    {{-- Rating --}}
                    <div class="flex items-center gap-1 mb-4">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 {{ $i <= $testimonial['rating'] ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>

                    {{-- Quote --}}
                    <p class="text-gray-700 leading-relaxed mb-6">"{{ $testimonial['quote'] }}"</p>

                    {{-- Author --}}
                    <div class="flex items-center gap-4">
                        <img src="{{ $testimonial['image'] }}" alt="{{ $testimonial['name'] }}" class="w-12 h-12 rounded-full">
                        <div>
                            <p class="font-bold text-gray-900">{{ $testimonial['name'] }}</p>
                            <p class="text-sm text-gray-500">{{ $testimonial['role'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
