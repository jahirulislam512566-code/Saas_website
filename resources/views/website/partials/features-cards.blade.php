<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-block px-4 py-1.5 bg-indigo-100 text-indigo-600 text-sm font-semibold rounded-full mb-4">
                Features
            </span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Powerful Features at Your Fingertips
            </h2>
            <p class="text-lg text-gray-600">
                Everything you need to create a stunning online presence
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach([
                [
                    'icon' => 'drag-drop',
                    'title' => 'Drag & Drop Builder',
                    'description' => 'Build pages visually with our intuitive drag and drop interface',
                    'color' => 'indigo'
                ],
                [
                    'icon' => 'responsive',
                    'title' => 'Responsive Design',
                    'description' => 'Looks perfect on all devices - desktop, tablet, and mobile',
                    'color' => 'green'
                ],
                [
                    'icon' => 'blog',
                    'title' => 'Blog CMS',
                    'description' => 'Powerful blogging platform with categories and tags',
                    'color' => 'blue'
                ],
                [
                    'icon' => 'ai',
                    'title' => 'AI Writer',
                    'description' => 'Generate high-quality content instantly with AI',
                    'color' => 'purple'
                ],
                [
                    'icon' => 'analytics',
                    'title' => 'Analytics Dashboard',
                    'description' => 'Real-time analytics to track your performance',
                    'color' => 'orange'
                ],
                [
                    'icon' => 'seo',
                    'title' => 'SEO Tools',
                    'description' => 'Built-in SEO tools to rank higher in search results',
                    'color' => 'teal'
                ],
                [
                    'icon' => 'security',
                    'title' => 'Security & SSL',
                    'description' => 'Free SSL certificates and advanced security features',
                    'color' => 'red'
                ],
                [
                    'icon' => 'performance',
                    'title' => 'Performance Optimized',
                    'description' => 'Lightning-fast websites with CDN integration',
                    'color' => 'yellow'
                ],
            ] as $feature)
                <div class="group bg-white rounded-2xl p-6 shadow-sm hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 border border-gray-100 hover:border-transparent cursor-pointer">
                    <div class="w-14 h-14 rounded-2xl bg-{{ $feature['color'] }}-100 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                        @include("icons.{$feature['icon']}", ['class' => "w-7 h-7 text-{$feature['color']}-600"])
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-{{ $feature['color'] }}-600 transition-colors">
                        {{ $feature['title'] }}
                    </h3>
                    <p class="text-gray-600 text-sm leading-relaxed">{{ $feature['description'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>