<section id="templates" class="py-20 bg-gray-50 relative overflow-hidden">
    <div class="container mx-auto px-4 relative z-10">
        {{-- Section Header --}}
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-block px-4 py-1.5 bg-indigo-100 text-indigo-600 text-sm font-semibold rounded-full mb-4 tracking-wide">
                Templates
            </span>
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4 leading-tight">
                Professional Templates for 
                <span class="bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                    Every Industry
                </span>
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Choose from our collection of professionally designed templates and launch your website in minutes
            </p>
        </div>

        {{-- Category Filters and Templates --}}
        <div x-data="{ activeCategory: 'all' }">
            {{-- Category Filters --}}
            <div class="flex flex-wrap gap-2 justify-center mb-12">
                <button @click="activeCategory = 'all'" 
                        class="px-6 py-2.5 rounded-full text-sm font-medium transition-all duration-300"
                        :class="activeCategory === 'all' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'bg-white text-gray-600 hover:bg-gray-100'">
                    All Templates
                </button>
                <button @click="activeCategory = 'business'" 
                        class="px-6 py-2.5 rounded-full text-sm font-medium transition-all duration-300"
                        :class="activeCategory === 'business' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'bg-white text-gray-600 hover:bg-gray-100'">
                    Business
                </button>
                <button @click="activeCategory = 'restaurant'" 
                        class="px-6 py-2.5 rounded-full text-sm font-medium transition-all duration-300"
                        :class="activeCategory === 'restaurant' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'bg-white text-gray-600 hover:bg-gray-100'">
                    Restaurant
                </button>
                <button @click="activeCategory = 'portfolio'" 
                        class="px-6 py-2.5 rounded-full text-sm font-medium transition-all duration-300"
                        :class="activeCategory === 'portfolio' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'bg-white text-gray-600 hover:bg-gray-100'">
                    Portfolio
                </button>
                <button @click="activeCategory = 'school'" 
                        class="px-6 py-2.5 rounded-full text-sm font-medium transition-all duration-300"
                        :class="activeCategory === 'school' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'bg-white text-gray-600 hover:bg-gray-100'">
                    School
                </button>
                <button @click="activeCategory = 'doctor'" 
                        class="px-6 py-2.5 rounded-full text-sm font-medium transition-all duration-300"
                        :class="activeCategory === 'doctor' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'bg-white text-gray-600 hover:bg-gray-100'">
                    Doctor
                </button>
                <button @click="activeCategory = 'agency'" 
                        class="px-6 py-2.5 rounded-full text-sm font-medium transition-all duration-300"
                        :class="activeCategory === 'agency' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'bg-white text-gray-600 hover:bg-gray-100'">
                    Agency
                </button>
            </div>

            {{-- Templates Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @php
                    $templates = [
                        [
                            'id' => 1,
                            'name' => 'Business Pro',
                            'category' => 'business',
                            'description' => 'Modern business template with professional design and advanced features.',
                            'image' => 'https://placehold.co/600x400/4F46E5/white?text=Business+Pro',
                            'pages' => 12,
                            'price' => 'Free',
                            'popular' => true,
                            'features' => ['Responsive', 'SEO Optimized', 'Blog Ready']
                        ],
                        [
                            'id' => 2,
                            'name' => 'Restaurant Deluxe',
                            'category' => 'restaurant',
                            'description' => 'Beautiful restaurant template with menu showcase and reservation system.',
                            'image' => 'https://placehold.co/600x400/DC2626/white?text=Restaurant+Deluxe',
                            'pages' => 8,
                            'price' => '$49',
                            'popular' => true,
                            'features' => ['Menu Builder', 'Reservation', 'Gallery']
                        ],
                        [
                            'id' => 3,
                            'name' => 'Portfolio Creative',
                            'category' => 'portfolio',
                            'description' => 'Stunning portfolio template for creative professionals and artists.',
                            'image' => 'https://placehold.co/600x400/EC4899/white?text=Portfolio+Creative',
                            'pages' => 6,
                            'price' => 'Free',
                            'popular' => false,
                            'features' => ['Gallery', 'Filter', 'Lightbox']
                        ],
                        [
                            'id' => 4,
                            'name' => 'School Academy',
                            'category' => 'school',
                            'description' => 'Complete school website template with course management and events.',
                            'image' => 'https://placehold.co/600x400/3B82F6/white?text=School+Academy',
                            'pages' => 10,
                            'price' => '$79',
                            'popular' => false,
                            'features' => ['Courses', 'Events', 'Staff Directory']
                        ],
                        [
                            'id' => 5,
                            'name' => 'Doctor Health',
                            'category' => 'doctor',
                            'description' => 'Professional medical template with appointment booking and patient management.',
                            'image' => 'https://placehold.co/600x400/0891B2/white?text=Doctor+Health',
                            'pages' => 9,
                            'price' => '$59',
                            'popular' => true,
                            'features' => ['Appointments', 'Services', 'Patient Portal']
                        ],
                        [
                            'id' => 6,
                            'name' => 'Agency Modern',
                            'category' => 'agency',
                            'description' => 'Modern agency template with project showcase and team management.',
                            'image' => 'https://placehold.co/600x400/7C3AED/white?text=Agency+Modern',
                            'pages' => 14,
                            'price' => 'Free',
                            'popular' => true,
                            'features' => ['Projects', 'Team', 'Client Portal']
                        ],
                    ]
                @endphp

                @foreach($templates as $index => $template)
                    <div class="group template-card" 
                         x-data="{ showPreview: false, isHovered: false }"
                         @mouseenter="isHovered = true"
                         @mouseleave="isHovered = false"
                         x-init="setTimeout(() => $el.classList.add('opacity-100', 'translate-y-0'), {{ $index * 100 + 200 }})"
                         class="opacity-0 translate-y-8 transition-all duration-700"
                         :data-category="'{{ $template['category'] }}'"
                         x-show="activeCategory === 'all' || activeCategory === '{{ $template['category'] }}'"
                         x-transition:enter.duration.300ms>
                        
                        {{-- Template Card --}}
                        <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden group-hover:-translate-y-2">
                            
                            {{-- Thumbnail --}}
                            <div class="relative overflow-hidden">
                                <img src="{{ $template['image'] }}" 
                                     alt="{{ $template['name'] }}" 
                                     class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-700">
                                
                                {{-- Overlay --}}
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <div class="absolute bottom-0 left-0 right-0 p-6">
                                        <div class="flex gap-3">
                                            <button @click="showPreview = true" 
                                                    class="flex-1 px-4 py-2.5 bg-white text-gray-900 font-medium rounded-lg hover:bg-gray-50 transition-colors text-sm">
                                                <span class="flex items-center justify-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                    Preview
                                                </span>
                                            </button>
                                            <a href="{{ route('templates.show', $template['id']) }}" 
                                               class="flex-1 px-4 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors text-sm">
                                                <span class="flex items-center justify-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                    </svg>
                                                    View Template
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                {{-- Badges --}}
                                <div class="absolute top-4 left-4 flex gap-2">
                                    @if($template['popular'])
                                        <span class="px-3 py-1 bg-gradient-to-r from-yellow-400 to-orange-500 text-white text-xs font-bold rounded-full shadow-lg flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                            Popular
                                        </span>
                                    @endif
                                    @if($template['price'] === 'Free')
                                        <span class="px-3 py-1 bg-green-500 text-white text-xs font-bold rounded-full shadow-lg">
                                            Free
                                        </span>
                                    @endif
                                </div>

                                {{-- Price --}}
                                <div class="absolute top-4 right-4 px-3 py-1 bg-white/90 backdrop-blur-sm text-gray-900 text-sm font-bold rounded-lg shadow-lg">
                                    {{ $template['price'] }}
                                </div>
                            </div>

                            {{-- Card Content --}}
                            <div class="p-6">
                                <div class="flex items-start justify-between mb-2">
                                    <h3 class="text-xl font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">
                                        {{ $template['name'] }}
                                    </h3>
                                    <span class="text-xs font-medium text-gray-400 bg-gray-100 px-2 py-1 rounded-full">
                                        {{ $template['pages'] }} pages
                                    </span>
                                </div>
                                
                                <p class="text-gray-600 text-sm leading-relaxed mb-4">
                                    {{ $template['description'] }}
                                </p>

                                {{-- Features --}}
                                <div class="flex flex-wrap gap-2">
                                    @foreach($template['features'] as $feature)
                                        <span class="px-2 py-1 bg-indigo-50 text-indigo-600 text-xs font-medium rounded-full">
                                            {{ $feature }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- View All Templates --}}
            <div class="text-center mt-12">
                <a href="{{ route('templates.index') }}" class="inline-flex items-center gap-3 px-8 py-4 bg-white text-gray-900 font-bold rounded-xl hover:shadow-xl transition-all duration-300 border-2 border-gray-200 hover:border-indigo-600 hover:text-indigo-600 group">
                    <span>View All Templates</span>
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>