<section class="py-20 bg-gradient-to-br from-gray-50 to-white relative overflow-hidden">
    {{-- Background Pattern --}}
    <div class="absolute inset-0 opacity-5">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 20% 50%, #667eea 1px, transparent 1px); background-size: 30px 30px;"></div>
    </div>

    <div class="container mx-auto px-4 relative">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-block px-4 py-1.5 bg-indigo-100 text-indigo-600 text-sm font-semibold rounded-full mb-4">
                🚀 Features
            </span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Powerful Tools for 
                <span class="bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Every Need</span>
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach([
                [
                    'icon' => '🎨',
                    'title' => 'Drag & Drop Builder',
                    'description' => 'Build stunning pages visually without touching a single line of code.',
                    'stats' => '2,500+ elements'
                ],
                [
                    'icon' => '📱',
                    'title' => 'Responsive Design',
                    'description' => 'Every website adapts perfectly to all screen sizes and devices.',
                    'stats' => '100% responsive'
                ],
                [
                    'icon' => '📝',
                    'title' => 'Blog CMS',
                    'description' => 'Manage your content with a powerful and intuitive blogging platform.',
                    'stats' => '10,000+ posts'
                ],
                [
                    'icon' => '🤖',
                    'title' => 'AI Writer',
                    'description' => 'Generate blog posts, product descriptions, and more with AI assistance.',
                    'stats' => '100+ languages'
                ],
                [
                    'icon' => '📊',
                    'title' => 'Analytics Dashboard',
                    'description' => 'Make data-driven decisions with real-time analytics and insights.',
                    'stats' => 'Real-time data'
                ],
                [
                    'icon' => '🔍',
                    'title' => 'SEO Tools',
                    'description' => 'Optimize your content and rank higher in search engine results.',
                    'stats' => '97% improvement'
                ],
            ] as $index => $feature)
                <div class="group relative bg-white rounded-2xl shadow-sm hover:shadow-2xl p-8 transition-all duration-500 hover:-translate-y-2 border border-gray-100 hover:border-indigo-100"
                     x-data="{ visible: false }"
                     x-init="setTimeout(() => visible = true, {{ $index * 100 + 200 }})"
                     :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'"
                     style="transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);">
                    
                    {{-- Icon --}}
                    <div class="relative">
                        <div class="w-14 h-14 bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-2xl flex items-center justify-center text-3xl mb-4 group-hover:scale-110 group-hover:shadow-lg transition-all duration-300">
                            {{ $feature['icon'] }}
                        </div>
                        <div class="absolute -top-2 -right-2 w-6 h-6 bg-indigo-600 rounded-full flex items-center justify-center text-white text-xs font-bold opacity-0 group-hover:opacity-100 transition-all duration-300">
                            ✓
                        </div>
                    </div>

                    {{-- Content --}}
                    <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-indigo-600 transition-colors">
                        {{ $feature['title'] }}
                    </h3>
                    <p class="text-gray-600 leading-relaxed mb-4">{{ $feature['description'] }}</p>
                    
                    {{-- Stats --}}
                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <span class="text-xs font-medium text-gray-400">{{ $feature['stats'] }}</span>
                        <a href="#" class="text-xs font-medium text-indigo-600 hover:text-indigo-800 transition-colors flex items-center gap-1">
                            Learn More
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>

                    {{-- Hover Gradient --}}
                    <div class="absolute inset-0 rounded-2xl bg-gradient-to-br from-indigo-500/5 to-purple-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                </div>
            @endforeach
        </div>

        {{-- Footer CTA --}}
        <div class="text-center mt-12">
            <p class="text-gray-600 mb-4">And many more features to explore...</p>
            <a href="{{ route('features') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold rounded-xl hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                View All Features
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
        </div>
    </div>
</section>