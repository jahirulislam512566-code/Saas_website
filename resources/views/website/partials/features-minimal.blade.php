<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Built for Modern Businesses
            </h2>
            <p class="text-lg text-gray-600">
                Everything you need to succeed online
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-5xl mx-auto">
            @foreach([
                [
                    'icon' => '🎨',
                    'title' => 'Drag & Drop Builder',
                    'description' => 'Build pages visually with our intuitive drag and drop interface. No coding needed.'
                ],
                [
                    'icon' => '📱',
                    'title' => 'Responsive Design',
                    'description' => 'All websites are mobile-friendly and look great on any device.'
                ],
                [
                    'icon' => '📝',
                    'title' => 'Blog CMS',
                    'description' => 'Powerful blogging platform with categories, tags, and SEO optimization.'
                ],
                [
                    'icon' => '🤖',
                    'title' => 'AI Writer',
                    'description' => 'Generate high-quality content instantly with our AI-powered writing assistant.'
                ],
                [
                    'icon' => '📊',
                    'title' => 'Analytics Dashboard',
                    'description' => 'Track visitors, page views, and conversions with real-time analytics.'
                ],
                [
                    'icon' => '🔍',
                    'title' => 'SEO Tools',
                    'description' => 'Built-in SEO tools to help your website rank higher in search results.'
                ],
            ] as $feature)
                <div class="text-center group">
                    <div class="w-16 h-16 bg-indigo-100 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-4 group-hover:scale-110 group-hover:bg-indigo-200 transition-all duration-300">
                        {{ $feature['icon'] }}
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $feature['title'] }}</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">{{ $feature['description'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>