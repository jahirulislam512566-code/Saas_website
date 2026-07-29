 <section class="relative min-h-screen flex items-center overflow-hidden bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 py-20">
        {{-- Animated Background --}}
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-white/10 rounded-full blur-3xl animate-pulse-slow"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-white/10 rounded-full blur-3xl animate-pulse-slow animation-delay-1000"></div>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                {{-- Content --}}
                <div x-data="{ visible: false }" 
                     x-init="setTimeout(() => visible = true, 100)"
                     :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'"
                     class="transition-all duration-1000 text-center lg:text-left">
                    
                    <div class="inline-flex items-center mt-5 gap-2 px-4 py-2 bg-white/10 backdrop-blur-md rounded-full text-white text-sm font-medium mb-6">
                        <span class="inline-block w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                        🚀 New: AI-Powered Website Builder
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6">
                        Build Beautiful Websites
                        <span class="block bg-gradient-to-r from-yellow-200 to-pink-200 bg-clip-text text-transparent">
                            Without Coding
                        </span>
                    </h1>

                    <p class="text-lg sm:text-xl text-white/90 leading-relaxed mb-8 max-w-2xl mx-auto lg:mx-0">
                        Create professional business websites in minutes. 
                        <span class="text-white font-medium">Hosting included.</span> 
                        <span class="text-white font-medium">SEO ready.</span> 
                        <span class="text-white font-medium">Fast.</span> 
                        <span class="text-white font-medium">Secure.</span>
                    </p>

                    <div class="flex flex-wrap gap-4 justify-center lg:justify-start">
                        <a href="{{ route('register') }}" 
                           class="inline-flex items-center px-8 py-4 bg-white text-indigo-600 font-bold rounded-xl hover:bg-indigo-50 transform hover:scale-105 transition-all duration-300 shadow-2xl hover:shadow-3xl group">
                            Start Free Trial
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </a>
                        <a href="#demo" 
                           class="inline-flex items-center px-8 py-4 border-2 border-white/30 text-white font-bold rounded-xl hover:bg-white/10 transform hover:scale-105 transition-all duration-300">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Live Demo
                        </a>
                    </div>

                    <div class="mt-8 flex flex-wrap items-center gap-6 justify-center lg:justify-start">
                        <div class="flex -space-x-2">
                            <img class="w-10 h-10 rounded-full border-2 border-white" src="https://ui-avatars.com/api/?name=John&background=4F46E5&color=fff&size=100" alt="">
                            <img class="w-10 h-10 rounded-full border-2 border-white" src="https://ui-avatars.com/api/?name=Sarah&background=7C3AED&color=fff&size=100" alt="">
                            <img class="w-10 h-10 rounded-full border-2 border-white" src="https://ui-avatars.com/api/?name=Mike&background=059669&color=fff&size=100" alt="">
                            <div class="w-10 h-10 rounded-full border-2 border-white bg-white/20 flex items-center justify-center text-white text-sm font-bold">+5K</div>
                        </div>
                        <div class="text-white/80 text-sm">
                            <span class="font-bold text-white">15,000+</span> websites built
                        </div>
                    </div>
                </div>

                {{-- Dashboard Preview --}}
                <div x-data="{ visible: false }" 
                     x-init="setTimeout(() => visible = true, 300)"
                     :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'"
                     class="transition-all duration-1000 delay-300 relative">
                    
                    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden transform hover:scale-[1.02] transition-transform duration-500">
                        <div class="bg-gray-50 px-4 py-3 border-b flex items-center gap-2">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 bg-red-400 rounded-full"></div>
                                <div class="w-3 h-3 bg-yellow-400 rounded-full"></div>
                                <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                            </div>
                            <div class="flex-1 text-center text-sm text-gray-500">Dashboard</div>
                            <div class="w-16"></div>
                        </div>

                        <div class="p-6">
                            <div class="grid grid-cols-3 gap-4 mb-6">
                                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl p-4 text-white">
                                    <p class="text-sm opacity-80">Websites</p>
                                    <p class="text-2xl font-bold">12</p>
                                    <span class="text-xs opacity-80">+2 this month</span>
                                </div>
                                <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl p-4 text-white">
                                    <p class="text-sm opacity-80">Visitors</p>
                                    <p class="text-2xl font-bold">24.8K</p>
                                    <span class="text-xs opacity-80">+12.5%</span>
                                </div>
                                <div class="bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl p-4 text-white">
                                    <p class="text-sm opacity-80">Conversion</p>
                                    <p class="text-2xl font-bold">3.2%</p>
                                    <span class="text-xs opacity-80">+0.8%</span>
                                </div>
                            </div>

                            <div class="border rounded-xl p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="font-semibold text-gray-900">Recent Activity</h4>
                                    <button class="text-sm text-indigo-600 hover:text-indigo-800">View All</button>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex items-center gap-3 text-sm">
                                        <span class="text-lg">🚀</span>
                                        <span class="flex-1 text-gray-700">Launched new website: "My Business"</span>
                                        <span class="text-gray-400">2 min ago</span>
                                    </div>
                                    <div class="flex items-center gap-3 text-sm">
                                        <span class="text-lg">✏️</span>
                                        <span class="flex-1 text-gray-700">Updated homepage design</span>
                                        <span class="text-gray-400">15 min ago</span>
                                    </div>
                                    <div class="flex items-center gap-3 text-sm">
                                        <span class="text-lg">📱</span>
                                        <span class="flex-1 text-gray-700">Added mobile responsive layout</span>
                                        <span class="text-gray-400">1 hour ago</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Floating Elements --}}
                    <div class="absolute -top-4 -right-4 bg-white rounded-xl shadow-xl p-3 animate-bounce-slow hidden sm:block">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-gray-900">100%</div>
                                <div class="text-xs text-gray-500">Satisfaction</div>
                            </div>
                        </div>
                    </div>

                    <div class="absolute -bottom-4 -left-4 bg-white rounded-xl shadow-xl p-3 animate-bounce-slow animation-delay-500 hidden sm:block">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-gray-900">24/7</div>
                                <div class="text-xs text-gray-500">Support</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Scroll Indicator --}}
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
            <div class="w-6 h-10 border-2 border-white/30 rounded-full flex justify-center">
                <div class="w-1.5 h-3 bg-white/50 rounded-full mt-2 animate-scroll"></div>
            </div>
        </div>
    </section>