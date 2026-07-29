<section id="demo" class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            {{-- Content --}}
            <div>
                <span class="inline-block px-4 py-1 bg-green-100 text-green-600 text-sm font-semibold rounded-full mb-4">
                    Live Demo
                </span>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    See the Website Builder in Action
                </h2>
                <p class="text-lg text-gray-600 mb-6">
                    Watch how easy it is to create a professional website. Drag, drop, and customize in real-time.
                </p>

                {{-- Features List --}}
                <div class="space-y-3">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-6 h-6 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-sm font-bold mt-0.5">✓</div>
                        <p class="text-gray-700">Intuitive drag and drop interface</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-6 h-6 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-sm font-bold mt-0.5">✓</div>
                        <p class="text-gray-700">Real-time preview of your changes</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-6 h-6 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-sm font-bold mt-0.5">✓</div>
                        <p class="text-gray-700">Mobile-responsive design out of the box</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-6 h-6 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-sm font-bold mt-0.5">✓</div>
                        <p class="text-gray-700">AI-powered content suggestions</p>
                    </div>
                </div>

                <div class="mt-8">
                    <a href="{{ route('demo') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-colors shadow-lg">
                        Try Live Demo
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Video/Demo Preview --}}
            <div class="relative">
                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl p-8 shadow-2xl">
                    <div class="bg-white rounded-xl overflow-hidden shadow-xl">
                        {{-- Browser Chrome --}}
                        <div class="bg-gray-50 px-4 py-2 border-b flex items-center gap-2">
                            <div class="flex items-center gap-1.5">
                                <div class="w-2.5 h-2.5 bg-red-400 rounded-full"></div>
                                <div class="w-2.5 h-2.5 bg-yellow-400 rounded-full"></div>
                                <div class="w-2.5 h-2.5 bg-green-400 rounded-full"></div>
                            </div>
                            <div class="flex-1 text-center text-xs text-gray-500">builder-demo.com</div>
                        </div>

                        {{-- Builder Interface Preview --}}
                        <div class="p-4">
                            <div class="flex gap-4">
                                {{-- Sidebar --}}
                                <div class="w-32 flex-shrink-0">
                                    <div class="space-y-2">
                                        <div class="p-2 bg-indigo-50 border border-indigo-200 rounded-lg text-xs font-medium text-indigo-700">Elements</div>
                                        <div class="p-2 bg-gray-50 rounded-lg text-xs text-gray-500">Text</div>
                                        <div class="p-2 bg-gray-50 rounded-lg text-xs text-gray-500">Image</div>
                                        <div class="p-2 bg-gray-50 rounded-lg text-xs text-gray-500">Button</div>
                                        <div class="p-2 bg-gray-50 rounded-lg text-xs text-gray-500">Video</div>
                                    </div>
                                </div>

                                {{-- Canvas --}}
                                <div class="flex-1">
                                    <div class="aspect-video bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center">
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
                        </div>
                    </div>
                </div>

                {{-- Play Button --}}
                <div class="absolute inset-0 flex items-center justify-center">
                    <button class="w-20 h-20 bg-white rounded-full shadow-2xl flex items-center justify-center hover:scale-110 transition-transform duration-300 group">
                        <div class="w-16 h-16 bg-indigo-600 rounded-full flex items-center justify-center group-hover:bg-indigo-700 transition-colors">
                            <svg class="w-8 h-8 text-white ml-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>