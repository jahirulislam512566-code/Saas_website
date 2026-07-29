<section id="templates" class="py-20 bg-white">
    <div class="container mx-auto px-4">
        {{-- Section Header --}}
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-block px-4 py-1 bg-purple-100 text-purple-600 text-sm font-semibold rounded-full mb-4">
                Templates
            </span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Beautiful Templates
            </h2>
            <p class="text-lg text-gray-600">
                Choose from our collection of professionally designed templates
            </p>
        </div>

        {{-- Template Categories --}}
        <div class="flex flex-wrap gap-2 justify-center mb-12">
            <button class="px-6 py-2 bg-indigo-600 text-white rounded-full text-sm font-medium hover:bg-indigo-700 transition-colors">
                All
            </button>
            <button class="px-6 py-2 bg-gray-100 text-gray-700 rounded-full text-sm font-medium hover:bg-gray-200 transition-colors">
                Business
            </button>
            <button class="px-6 py-2 bg-gray-100 text-gray-700 rounded-full text-sm font-medium hover:bg-gray-200 transition-colors">
                Portfolio
            </button>
            <button class="px-6 py-2 bg-gray-100 text-gray-700 rounded-full text-sm font-medium hover:bg-gray-200 transition-colors">
                E-commerce
            </button>
            <button class="px-6 py-2 bg-gray-100 text-gray-700 rounded-full text-sm font-medium hover:bg-gray-200 transition-colors">
                Blog
            </button>
            <button class="px-6 py-2 bg-gray-100 text-gray-700 rounded-full text-sm font-medium hover:bg-gray-200 transition-colors">
                Landing
            </button>
        </div>

        {{-- Templates Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
                $templates = [
                    [
                        'name' => 'Modern Business',
                        'category' => 'Business',
                        'image' => asset('images/templates/business.jpg'),
                        'pages' => 8,
                        'popular' => true
                    ],
                    [
                        'name' => 'Creative Portfolio',
                        'category' => 'Portfolio',
                        'image' => asset('images/templates/portfolio.jpg'),
                        'pages' => 6,
                        'popular' => false
                    ],
                    [
                        'name' => 'E-commerce Store',
                        'category' => 'E-commerce',
                        'image' => asset('images/templates/ecommerce.jpg'),
                        'pages' => 12,
                        'popular' => true
                    ],
                    [
                        'name' => 'Minimal Blog',
                        'category' => 'Blog',
                        'image' => asset('images/templates/blog.jpg'),
                        'pages' => 4,
                        'popular' => false
                    ],
                    [
                        'name' => 'Landing Page',
                        'category' => 'Landing',
                        'image' => asset('images/templates/landing.jpg'),
                        'pages' => 3,
                        'popular' => false
                    ],
                    [
                        'name' => 'Agency Pro',
                        'category' => 'Business',
                        'image' => asset('images/templates/agency.jpg'),
                        'pages' => 10,
                        'popular' => true
                    ],
                ]
            @endphp

            @foreach($templates as $template)
                <div class="group rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                    <div class="relative">
                        <img src="{{ $template['image'] }}" alt="{{ $template['name'] }}" class="w-full h-56 object-cover">
                        @if($template['popular'])
                            <span class="absolute top-4 right-4 px-3 py-1 bg-yellow-400 text-yellow-900 text-xs font-bold rounded-full">
                                Popular
                            </span>
                        @endif
                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-4">
                            <button class="px-6 py-3 bg-white text-gray-900 font-bold rounded-xl hover:bg-gray-100 transition-colors">
                                Preview
                            </button>
                            <button class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-colors">
                                Use Template
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-lg font-bold text-gray-900">{{ $template['name'] }}</h3>
                            <span class="text-sm text-gray-500">{{ $template['pages'] }} pages</span>
                        </div>
                        <p class="text-sm text-gray-500">{{ $template['category'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- View All Templates --}}
        <div class="text-center mt-12">
            <a href="{{ route('templates') }}" class="inline-flex items-center gap-2 px-8 py-4 border-2 border-gray-300 text-gray-700 font-bold rounded-xl hover:bg-gray-50 transition-colors">
                View All Templates
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
        </div>
    </div>
</section>