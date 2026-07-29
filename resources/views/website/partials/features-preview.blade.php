<section id="features" class="py-20 bg-white relative overflow-hidden">
    {{-- Background Decorations --}}
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-50 rounded-full blur-3xl opacity-50"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-purple-50 rounded-full blur-3xl opacity-50"></div>
    </div>

    <div class="container mx-auto px-4 relative z-10">
        {{-- Section Header --}}
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-block px-4 py-1.5 bg-indigo-100 text-indigo-600 text-sm font-semibold rounded-full mb-4 tracking-wide">
                Features
            </span>
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4 leading-tight">
                Everything You Need to 
                <span class="bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                    Build Amazing Websites
                </span>
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Powerful tools and features designed to help you create, manage, and grow your online presence with ease
            </p>
        </div>

        {{-- Features Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @php
                $features = [
                    [
                        'icon' => '🎨',
                        'title' => 'Drag & Drop Builder',
                        'description' => 'Build stunning pages visually with our intuitive drag and drop interface. No coding needed.',
                        'color' => 'indigo',
                        'gradient' => 'from-indigo-500 to-indigo-600',
                        'bg' => 'bg-indigo-50',
                        'popular' => true
                    ],
                    [
                        'icon' => '📱',
                        'title' => 'Responsive Design',
                        'description' => 'Every website looks perfect on all devices - desktop, tablet, and mobile. Fully responsive out of the box.',
                        'color' => 'green',
                        'gradient' => 'from-green-500 to-emerald-600',
                        'bg' => 'bg-green-50',
                        'popular' => false
                    ],
                    [
                        'icon' => '📝',
                        'title' => 'Blog CMS',
                        'description' => 'Powerful blogging platform with categories, tags, and SEO optimization. Drive traffic with great content.',
                        'color' => 'blue',
                        'gradient' => 'from-blue-500 to-cyan-600',
                        'bg' => 'bg-blue-50',
                        'popular' => true
                    ],
                    [
                        'icon' => '🤖',
                        'title' => 'AI Writer',
                        'description' => 'Generate high-quality content instantly with AI. Get blog posts, product descriptions, and more.',
                        'color' => 'purple',
                        'gradient' => 'from-purple-500 to-pink-600',
                        'bg' => 'bg-purple-50',
                        'popular' => true
                    ],
                    [
                        'icon' => '📊',
                        'title' => 'Analytics Dashboard',
                        'description' => 'Track visitors, page views, and conversions with real-time analytics. Make data-driven decisions.',
                        'color' => 'orange',
                        'gradient' => 'from-orange-500 to-red-600',
                        'bg' => 'bg-orange-50',
                        'popular' => false
                    ],
                    [
                        'icon' => '🔍',
                        'title' => 'SEO Tools',
                        'description' => 'Built-in SEO tools with meta tags, sitemaps, and optimization suggestions. Rank higher in search results.',
                        'color' => 'teal',
                        'gradient' => 'from-teal-500 to-emerald-600',
                        'bg' => 'bg-teal-50',
                        'popular' => true
                    ],
                    [
                        'icon' => '🔒',
                        'title' => 'Security & SSL',
                        'description' => 'Enterprise-grade security with free SSL certificates, daily backups, and advanced threat protection.',
                        'color' => 'red',
                        'gradient' => 'from-red-500 to-pink-600',
                        'bg' => 'bg-red-50',
                        'popular' => false
                    ],
                    [
                        'icon' => '⚡',
                        'title' => 'Performance Optimized',
                        'description' => 'Lightning-fast websites with CDN integration, image optimization, and advanced caching strategies.',
                        'color' => 'yellow',
                        'gradient' => 'from-yellow-500 to-amber-600',
                        'bg' => 'bg-yellow-50',
                        'popular' => false
                    ],
                ]
            @endphp

            @foreach($features as $index => $feature)
                <div class="group relative" 
                     x-data="{ hover: false }"
                     @mouseenter="hover = true"
                     @mouseleave="hover = false"
                     x-init="setTimeout(() => $el.classList.add('opacity-100', 'translate-y-0'), {{ $index * 50 + 100 }})"
                     class="opacity-0 translate-y-8 transition-all duration-700">
                    
                    {{-- Feature Card --}}
                    <div class="relative bg-white rounded-2xl shadow-sm hover:shadow-2xl border border-gray-100 hover:border-transparent p-6 transition-all duration-300 h-full {{ $feature['popular'] ? 'ring-2 ring-indigo-500/20' : '' }}">
                        
                        {{-- Popular Badge --}}
                        @if($feature['popular'])
                            <div class="absolute -top-2 -right-2">
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-gradient-to-r from-indigo-500 to-purple-500 text-white text-xs font-bold rounded-full shadow-lg">
                                    <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span>
                                    Popular
                                </span>
                            </div>
                        @endif

                        {{-- Icon --}}
                        <div class="w-14 h-14 {{ $feature['bg'] }} rounded-2xl flex items-center justify-center text-3xl mb-4 group-hover:scale-110 group-hover:shadow-lg transition-all duration-300">
                            {{ $feature['icon'] }}
                        </div>

                        {{-- Title --}}
                        <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-{{ $feature['color'] }}-600 transition-colors">
                            {{ $feature['title'] }}
                        </h3>

                        {{-- Description --}}
                        <p class="text-gray-600 leading-relaxed text-sm">
                            {{ $feature['description'] }}
                        </p>

                        {{-- Learn More Link --}}
                        <div class="mt-4 opacity-0 group-hover:opacity-100 transition-all duration-300 transform group-hover:translate-y-0 translate-y-2">
                            <a href="{{ route('features.index') }}" class="inline-flex items-center gap-1 text-sm font-medium text-{{ $feature['color'] }}-600 hover:text-{{ $feature['color'] }}-700">
                                Learn More
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>

                        {{-- Hover Gradient Overlay --}}
                        <div class="absolute inset-0 rounded-2xl bg-gradient-to-br {{ $feature['gradient'] }} opacity-0 group-hover:opacity-5 transition-opacity duration-300 pointer-events-none"></div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- CTA Button --}}
        <div class="text-center mt-12">
            <a href="{{ route('features.index') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-gray-900 text-white font-bold rounded-xl hover:bg-gray-800 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                Explore All Features
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
        </div>
    </div>
</section>