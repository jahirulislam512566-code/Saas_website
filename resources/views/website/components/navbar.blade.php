<nav x-data="{ 
    mobileMenuOpen: false, 
    scrolled: false, 
    dropdowns: { product: false, resources: false, company: false } 
}" 
     x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 50 })"
     @click.away="mobileMenuOpen = false; dropdowns = { product: false, resources: false, company: false }"
     class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
     :class="scrolled ? 'bg-white/95 backdrop-blur-md shadow-lg border-b border-gray-100' : 'bg-transparent'"
     role="navigation"
     aria-label="Main navigation">
    
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 md:h-20">
            
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2 flex-shrink-0 group" aria-label="Buildora Home">
                <div class="relative">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-lg transition-transform duration-300 group-hover:scale-105">
                        B
                    </div>
                    <div class="absolute -inset-1 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl blur opacity-20 group-hover:opacity-40 transition-opacity"></div>
                </div>
                <span class="text-xl font-bold transition-colors duration-300" :class="scrolled ? 'text-gray-900' : 'text-white'">
                    Buildora
                </span>
            </a>

            {{-- ==================== DESKTOP NAVIGATION ==================== --}}
            {{-- Changed from hidden xl:flex to hidden lg:flex to show on smaller desktops --}}
            <div class="hidden lg:flex items-center gap-1">
                
                {{-- Product Dropdown --}}
                <div class="relative" @mouseenter="dropdowns.product = true" @mouseleave="dropdowns.product = false">
                    <button class="flex items-center gap-1 px-3 py-2 text-sm font-medium transition-all duration-200 rounded-lg hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2" 
                            :class="scrolled ? 'text-gray-700 hover:text-indigo-600 hover:bg-gray-50' : 'text-white/90 hover:text-white hover:bg-white/10'"
                            :aria-expanded="dropdowns.product"
                            aria-haspopup="true">
                        <span>Product</span>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="dropdowns.product ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    
                    <div x-show="dropdowns.product" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         class="absolute top-full left-0 mt-2 w-64 bg-white rounded-2xl shadow-2xl py-2 border border-gray-100 overflow-hidden"
                         role="menu">
                        <div class="px-3 py-2 border-b border-gray-50">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Explore</p>
                        </div>
                        <a href="{{ route('features.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors group" role="menuitem">
                            <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 group-hover:bg-indigo-100 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900 group-hover:text-indigo-600 transition-colors">Features</div>
                                <div class="text-xs text-gray-500">Everything you need</div>
                            </div>
                        </a>
                        <a href="{{ route('templates.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors group" role="menuitem">
                            <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600 group-hover:bg-purple-100 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900 group-hover:text-purple-600 transition-colors">Templates</div>
                                <div class="text-xs text-gray-500">100+ professional designs</div>
                            </div>
                        </a>
                        <a href="{{ route('pricing') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors group" role="menuitem">
                            <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center text-green-600 group-hover:bg-green-100 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900 group-hover:text-green-600 transition-colors">Pricing</div>
                                <div class="text-xs text-gray-500">Simple, transparent plans</div>
                            </div>
                        </a>
                        <a href="{{ route('solutions.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors group" role="menuitem">
                            <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center text-orange-600 group-hover:bg-orange-100 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900 group-hover:text-orange-600 transition-colors">Solutions</div>
                                <div class="text-xs text-gray-500">For every industry</div>
                            </div>
                        </a>
                    </div>
                </div>

                {{-- Resources Dropdown --}}
                <div class="relative" @mouseenter="dropdowns.resources = true" @mouseleave="dropdowns.resources = false">
                    <button class="flex items-center gap-1 px-3 py-2 text-sm font-medium transition-all duration-200 rounded-lg hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            :class="scrolled ? 'text-gray-700 hover:text-indigo-600 hover:bg-gray-50' : 'text-white/90 hover:text-white hover:bg-white/10'"
                            :aria-expanded="dropdowns.resources"
                            aria-haspopup="true">
                        <span>Resources</span>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="dropdowns.resources ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    
                    <div x-show="dropdowns.resources" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         class="absolute top-full left-0 mt-2 w-64 bg-white rounded-2xl shadow-2xl py-2 border border-gray-100 overflow-hidden"
                         role="menu">
                        <div class="px-3 py-2 border-b border-gray-50">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Learn & Grow</p>
                        </div>
                        <a href="{{ route('blog.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors group" role="menuitem">
                            <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 group-hover:bg-blue-100 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900 group-hover:text-blue-600 transition-colors">Blog</div>
                                <div class="text-xs text-gray-500">Latest articles & updates</div>
                            </div>
                        </a>
                        <a href="{{ route('docs.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors group" role="menuitem">
                            <div class="w-10 h-10 bg-cyan-50 rounded-xl flex items-center justify-center text-cyan-600 group-hover:bg-cyan-100 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900 group-hover:text-cyan-600 transition-colors">Documentation</div>
                                <div class="text-xs text-gray-500">Learn how to use Buildora</div>
                            </div>
                        </a>
                        <a href="{{ route('resources.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors group" role="menuitem">
                            <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600 group-hover:bg-amber-100 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900 group-hover:text-amber-600 transition-colors">Resources</div>
                                <div class="text-xs text-gray-500">Guides, videos & tools</div>
                            </div>
                        </a>
                    </div>
                </div>

                {{-- Company Dropdown --}}
                <div class="relative" @mouseenter="dropdowns.company = true" @mouseleave="dropdowns.company = false">
                    <button class="flex items-center gap-1 px-3 py-2 text-sm font-medium transition-all duration-200 rounded-lg hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            :class="scrolled ? 'text-gray-700 hover:text-indigo-600 hover:bg-gray-50' : 'text-white/90 hover:text-white hover:bg-white/10'"
                            :aria-expanded="dropdowns.company"
                            aria-haspopup="true">
                        <span>Company</span>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="dropdowns.company ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    
                    <div x-show="dropdowns.company" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         class="absolute top-full left-0 mt-2 w-64 bg-white rounded-2xl shadow-2xl py-2 border border-gray-100 overflow-hidden"
                         role="menu">
                        <div class="px-3 py-2 border-b border-gray-50">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">About Us</p>
                        </div>
                        <a href="{{ route('about.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors group" role="menuitem">
                            <div class="w-10 h-10 bg-rose-50 rounded-xl flex items-center justify-center text-rose-600 group-hover:bg-rose-100 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900 group-hover:text-rose-600 transition-colors">About</div>
                                <div class="text-xs text-gray-500">Our story & mission</div>
                            </div>
                        </a>
                        <a href="{{ route('careers.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors group" role="menuitem">
                            <div class="w-10 h-10 bg-violet-50 rounded-xl flex items-center justify-center text-violet-600 group-hover:bg-violet-100 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900 group-hover:text-violet-600 transition-colors">Careers</div>
                                <div class="text-xs text-gray-500">Join our team</div>
                            </div>
                        </a>
                        <a href="{{ route('contact') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors group" role="menuitem">
                            <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 group-hover:bg-emerald-100 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900 group-hover:text-emerald-600 transition-colors">Contact</div>
                                <div class="text-xs text-gray-500">Get in touch</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Desktop Actions --}}
            <div class="hidden lg:flex items-center gap-3">
                @auth
                    <a href="{{ route('account.dashboard') }}" class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">
                        Profile
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium transition-colors duration-200 px-4 py-2 rounded-lg hover:bg-white/10" 
                       :class="scrolled ? 'text-gray-700 hover:text-indigo-600 hover:bg-gray-50' : 'text-white/90 hover:text-white'">
                        Log In
                    </a>
                    <a href="{{ route('register') }}" class="px-6 py-2.5 bg-white text-indigo-600 text-sm font-bold rounded-xl hover:bg-gray-50 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">
                        Start Free Trial
                    </a>
                @endauth
            </div>

            {{-- Mobile Menu Button - Only shows on smaller screens --}}
            <button @click="mobileMenuOpen = !mobileMenuOpen" 
                    class="lg:hidden p-2 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                    :class="scrolled ? 'hover:bg-gray-100' : 'hover:bg-white/10'"
                    aria-label="Toggle menu"
                    :aria-expanded="mobileMenuOpen">
                <svg class="w-6 h-6" :class="scrolled ? 'text-gray-900' : 'text-white'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        {{-- ==================== MOBILE MENU ==================== --}}
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 -translate-y-4 scale-95"
             class="lg:hidden bg-white rounded-2xl shadow-2xl p-4 mt-2 max-h-[80vh] overflow-y-auto border border-gray-100"
             @click.away="mobileMenuOpen = false">
            
            <div class="space-y-1">
                {{-- Product --}}
                <div x-data="{ open: false }" class="border-b border-gray-50 pb-2">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-xl transition-colors font-medium">
                        <span>Product</span>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" class="ml-4 space-y-1">
                        <a href="{{ route('features.index') }}" class="block px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 rounded-lg transition-colors">Features</a>
                        <a href="{{ route('templates.index') }}" class="block px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 rounded-lg transition-colors">Templates</a>
                        <a href="{{ route('pricing') }}" class="block px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 rounded-lg transition-colors">Pricing</a>
                        <a href="{{ route('solutions.index') }}" class="block px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 rounded-lg transition-colors">Solutions</a>
                    </div>
                </div>

                {{-- Resources --}}
                <div x-data="{ open: false }" class="border-b border-gray-50 pb-2">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-xl transition-colors font-medium">
                        <span>Resources</span>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" class="ml-4 space-y-1">
                        <a href="{{ route('blog.index') }}" class="block px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 rounded-lg transition-colors">Blog</a>
                        <a href="{{ route('docs.index') }}" class="block px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 rounded-lg transition-colors">Documentation</a>
                        <a href="{{ route('resources.index') }}" class="block px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 rounded-lg transition-colors">Resources</a>
                    </div>
                </div>

                {{-- Company --}}
                <div x-data="{ open: false }" class="border-b border-gray-50 pb-2">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-xl transition-colors font-medium">
                        <span>Company</span>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" class="ml-4 space-y-1">
                        <a href="{{ route('about.index') }}" class="block px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 rounded-lg transition-colors">About</a>
                        <a href="{{ route('careers.index') }}" class="block px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 rounded-lg transition-colors">Careers</a>
                        <a href="{{ route('contact') }}" class="block px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 rounded-lg transition-colors">Contact</a>
                    </div>
                </div>

                <div class="pt-4 mt-2">
                    @auth
                        <a href="{{ route('dashboard') }}" class="block w-full px-4 py-3 text-center text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition-colors font-medium">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="block w-full px-4 py-3 text-center text-gray-700 hover:bg-gray-50 rounded-xl transition-colors font-medium">
                            Log In
                        </a>
                        <a href="{{ route('register') }}" class="block w-full px-4 py-3 text-center text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition-colors mt-2 font-medium">
                            Start Free Trial
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</nav>