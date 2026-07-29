<section class="py-20 bg-white" x-data="{ activeFeature: 0 }">
    <div class="container mx-auto px-4">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-block px-4 py-1.5 bg-indigo-100 text-indigo-600 text-sm font-semibold rounded-full mb-4">
                Features
            </span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                See Our Features in Action
            </h2>
            <p class="text-lg text-gray-600">
                Click on any feature to see a live demo
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
            {{-- Feature Tabs --}}
            <div class="lg:col-span-2 space-y-3">
                @php
                    $features = [
                        [
                            'id' => 0,
                            'icon' => '🎨',
                            'title' => 'Drag & Drop Builder',
                            'description' => 'Build pages visually with our intuitive interface'
                        ],
                        [
                            'id' => 1,
                            'icon' => '📱',
                            'title' => 'Responsive Design',
                            'description' => 'Perfect on all devices'
                        ],
                        [
                            'id' => 2,
                            'icon' => '📝',
                            'title' => 'Blog CMS',
                            'description' => 'Powerful blogging platform'
                        ],
                        [
                            'id' => 3,
                            'icon' => '🤖',
                            'title' => 'AI Writer',
                            'description' => 'Generate content with AI'
                        ],
                        [
                            'id' => 4,
                            'icon' => '📊',
                            'title' => 'Analytics Dashboard',
                            'description' => 'Real-time analytics'
                        ],
                        [
                            'id' => 5,
                            'icon' => '🔍',
                            'title' => 'SEO Tools',
                            'description' => 'Rank higher in search'
                        ],
                    ]
                @endphp

                @foreach($features as $feature)
                    <button @click="activeFeature = {{ $feature['id'] }}" 
                            class="w-full text-left p-4 rounded-xl transition-all duration-300 group"
                            :class="activeFeature === {{ $feature['id'] }} ? 'bg-indigo-50 border-2 border-indigo-500 shadow-lg' : 'bg-gray-50 hover:bg-gray-100 border-2 border-transparent'">
                        <div class="flex items-center gap-4">
                            <div class="text-2xl group-hover:scale-110 transition-transform duration-300" 
                                 :class="activeFeature === {{ $feature['id'] }} ? 'scale-110' : ''">
                                {{ $feature['icon'] }}
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900" 
                                    :class="activeFeature === {{ $feature['id'] }} ? 'text-indigo-600' : ''">
                                    {{ $feature['title'] }}
                                </h4>
                                <p class="text-sm text-gray-500">{{ $feature['description'] }}</p>
                            </div>
                            <div class="ml-auto">
                                <svg class="w-5 h-5 text-gray-400 transition-transform duration-300" 
                                     :class="activeFeature === {{ $feature['id'] }} ? 'rotate-90 text-indigo-600' : ''"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </div>
                    </button>
                @endforeach
            </div>

            {{-- Feature Demo --}}
            <div class="lg:col-span-3">
                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl p-8 shadow-2xl">
                    <div class="bg-white rounded-xl overflow-hidden shadow-xl">
                        {{-- Browser Chrome --}}
                        <div class="bg-gray-50 px-4 py-2 border-b flex items-center gap-2">
                            <div class="flex items-center gap-1.5">
                                <div class="w-2.5 h-2.5 bg-red-400 rounded-full"></div>
                                <div class="w-2.5 h-2.5 bg-yellow-400 rounded-full"></div>
                                <div class="w-2.5 h-2.5 bg-green-400 rounded-full"></div>
                            </div>
                            <div class="flex-1 text-center text-xs text-gray-500">feature-demo.com</div>
                        </div>

                        {{-- Demo Content --}}
                        <div class="p-6 min-h-[300px]">
                            {{-- Drag & Drop Builder Demo --}}
                            <div x-show="activeFeature === 0">
                                <div class="flex gap-4">
                                    <div class="w-32 flex-shrink-0">
                                        <div class="space-y-2">
                                            <div class="p-2 bg-indigo-50 border border-indigo-200 rounded-lg text-xs font-medium text-indigo-700">Elements</div>
                                            <div class="p-2 bg-gray-50 rounded-lg text-xs text-gray-500 cursor-move hover:bg-gray-100 transition-colors">Text</div>
                                            <div class="p-2 bg-gray-50 rounded-lg text-xs text-gray-500 cursor-move hover:bg-gray-100 transition-colors">Image</div>
                                            <div class="p-2 bg-gray-50 rounded-lg text-xs text-gray-500 cursor-move hover:bg-gray-100 transition-colors">Button</div>
                                        </div>
                                    </div>
                                    <div class="flex-1 aspect-video bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center">
                                        <div class="text-center">
                                            <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                            </div>
                                            <p class="text-sm text-gray-500">Drop elements here</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Responsive Design Demo --}}
                            <div x-show="activeFeature === 1" class="flex items-center justify-center">
                                <div class="flex items-center gap-8">
                                    <div class="w-12 h-24 bg-gray-200 rounded-lg flex items-center justify-center text-xs text-gray-500">Mobile</div>
                                    <div class="w-24 h-16 bg-gray-200 rounded-lg flex items-center justify-center text-xs text-gray-500">Tablet</div>
                                    <div class="w-48 h-24 bg-gray-200 rounded-lg flex items-center justify-center text-xs text-gray-500">Desktop</div>
                                </div>
                            </div>

                            {{-- Blog CMS Demo --}}
                            <div x-show="activeFeature === 2">
                                <div class="space-y-4">
                                    <div class="flex items-center gap-4 border-b pb-4">
                                        <div class="w-16 h-16 bg-indigo-100 rounded-lg"></div>
                                        <div>
                                            <h4 class="font-bold text-gray-900">How to Build a Website</h4>
                                            <p class="text-sm text-gray-500">Posted 2 days ago • 3 min read</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4 border-b pb-4">
                                        <div class="w-16 h-16 bg-green-100 rounded-lg"></div>
                                        <div>
                                            <h4 class="font-bold text-gray-900">SEO Best Practices</h4>
                                            <p class="text-sm text-gray-500">Posted 5 days ago • 5 min read</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- AI Writer Demo --}}
                            <div x-show="activeFeature === 3">
                                <div class="space-y-4">
                                    <div class="flex gap-4">
                                        <input type="text" placeholder="Enter a topic..." class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <button class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">Generate</button>
                                    </div>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <p class="text-sm text-gray-600">✨ AI-generated content will appear here...</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Analytics Dashboard Demo --}}
                            <div x-show="activeFeature === 4">
                                <div class="grid grid-cols-3 gap-4 mb-4">
                                    <div class="bg-indigo-50 rounded-lg p-3 text-center">
                                        <p class="text-2xl font-bold text-indigo-600">24.8K</p>
                                        <p class="text-xs text-gray-500">Visitors</p>
                                    </div>
                                    <div class="bg-green-50 rounded-lg p-3 text-center">
                                        <p class="text-2xl font-bold text-green-600">3.2%</p>
                                        <p class="text-xs text-gray-500">Conversion</p>
                                    </div>
                                    <div class="bg-purple-50 rounded-lg p-3 text-center">
                                        <p class="text-2xl font-bold text-purple-600">12.5%</p>
                                        <p class="text-xs text-gray-500">Growth</p>
                                    </div>
                                </div>
                                <div class="h-20 bg-gray-100 rounded-lg flex items-center justify-center text-sm text-gray-500">
                                    📊 Analytics Chart
                                </div>
                            </div>

                            {{-- SEO Tools Demo --}}
                            <div x-show="activeFeature === 5">
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                        <span class="text-sm font-medium text-gray-700">Meta Title</span>
                                        <span class="text-sm text-green-600">✓ Optimized (52 chars)</span>
                                    </div>
                                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                        <span class="text-sm font-medium text-gray-700">Meta Description</span>
                                        <span class="text-sm text-green-600">✓ Optimized (145 chars)</span>
                                    </div>
                                    <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                                        <span class="text-sm font-medium text-gray-700">Keywords</span>
                                        <span class="text-sm text-yellow-600">⚠ 3 missing</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>