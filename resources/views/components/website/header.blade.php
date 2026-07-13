{{-- resources/views/components/website/header.blade.php --}}
<header x-data="{ mobileMenuOpen: false }" class="sticky top-0 z-50 bg-white/95 backdrop-blur-sm border-b border-gray-100">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                        </svg>
                    </div>
                    <span class="text-xl font-extrabold text-gray-900 tracking-tight">
                        SaaS<span class="text-indigo-600">Hub</span>
                    </span>
                </a>
            </div>
            
            <!-- Desktop Navigation -->
            <div class="hidden lg:flex items-center space-x-1">
                @if(Route::has('home'))
                    <a href="{{ route('home') }}" class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('home') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:text-indigo-600 hover:bg-indigo-50' }}">
                        Home
                    </a>
                @endif
                
                @if(Route::has('website.features'))
                    <a href="{{ route('website.features') }}" class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('website.features') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:text-indigo-600 hover:bg-indigo-50' }}">
                        Features
                    </a>
                @endif
                
                @if(Route::has('website.pricing'))
                    <a href="{{ route('website.pricing') }}" class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('website.pricing') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:text-indigo-600 hover:bg-indigo-50' }}">
                        Pricing
                    </a>
                @endif
                
                @if(Route::has('website.services'))
                    <a href="{{ route('website.services') }}" class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('website.services') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:text-indigo-600 hover:bg-indigo-50' }}">
                        Services
                    </a>
                @endif
                
                @if(Route::has('website.portfolio'))
                    <a href="{{ route('website.portfolio') }}" class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('website.portfolio') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:text-indigo-600 hover:bg-indigo-50' }}">
                        Portfolio
                    </a>
                @endif
                
                @if(Route::has('website.blog'))
                    <a href="{{ route('website.blog') }}" class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('website.blog') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:text-indigo-600 hover:bg-indigo-50' }}">
                        Blog
                    </a>
                @endif
                
                @if(Route::has('website.about'))
                    <a href="{{ route('website.about') }}" class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('website.about') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:text-indigo-600 hover:bg-indigo-50' }}">
                        About
                    </a>
                @endif
                
                @if(Route::has('website.contact'))
                    <a href="{{ route('website.contact') }}" class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('website.contact') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:text-indigo-600 hover:bg-indigo-50' }}">
                        Contact
                    </a>
                @endif
            </div>
            
            <!-- Right Side -->
            <div class="flex items-center gap-4">
                @auth
                    <div x-data="{ open: false }" @click.away="open = false" class="relative">
                        <button @click="open = !open" class="flex items-center gap-2 text-sm font-medium text-gray-700 hover:text-indigo-600">
                            <span class="w-8 h-8 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 flex items-center justify-center text-white text-sm font-semibold">
                                {{ auth()->user()->initials ?? 'U' }}
                            </span>
                            <span class="hidden md:inline">{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        
                        <div x-show="open" x-transition class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-1 overflow-hidden">
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            
                            @if(Route::has('website.account.dashboard'))
                                <a href="{{ route('website.account.dashboard') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600">
                                    <i class="fas fa-chart-pie w-5 text-center text-gray-400"></i> Dashboard
                                </a>
                            @endif
                            
                            @if(Route::has('website.account.profile'))
                                <a href="{{ route('website.account.profile') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600">
                                    <i class="fas fa-user w-5 text-center text-gray-400"></i> Profile
                                </a>
                            @endif
                            
                            @if(Route::has('website.account.subscriptions'))
                                <a href="{{ route('website.account.subscriptions') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600">
                                    <i class="fas fa-crown w-5 text-center text-gray-400"></i> Subscriptions
                                </a>
                            @endif
                            
                            @if(Route::has('website.account.invoices'))
                                <a href="{{ route('website.account.invoices') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600">
                                    <i class="fas fa-file-invoice w-5 text-center text-gray-400"></i> Invoices
                                </a>
                            @endif
                            
                            @if(Route::has('website.account.settings'))
                                <a href="{{ route('website.account.settings') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600">
                                    <i class="fas fa-cog w-5 text-center text-gray-400"></i> Settings
                                </a>
                            @endif
                            
                            <div class="border-t border-gray-100 mt-1 pt-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a href="#" onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                        <i class="fas fa-sign-out-alt w-5 text-center text-red-400"></i> Logout
                                    </a>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="hidden md:flex items-center gap-3">
                        @if(Route::has('login'))
                            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600 transition">
                                Log in
                            </a>
                        @endif
                        @if(Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm hover:shadow transition">
                                Get Started Free
                            </a>
                        @endif
                    </div>
                @endauth
                
                <!-- Mobile Menu Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Mobile Navigation -->
        <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="lg:hidden py-4 border-t border-gray-100">
            <div class="space-y-1">
                @if(Route::has('home'))
                    <a href="{{ route('home') }}" class="block px-4 py-2 text-base font-medium rounded-lg transition-colors {{ request()->routeIs('home') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:text-indigo-600 hover:bg-indigo-50' }}">
                        Home
                    </a>
                @endif
                
                @if(Route::has('website.features'))
                    <a href="{{ route('website.features') }}" class="block px-4 py-2 text-base font-medium rounded-lg transition-colors {{ request()->routeIs('website.features') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:text-indigo-600 hover:bg-indigo-50' }}">
                        Features
                    </a>
                @endif
                
                @if(Route::has('website.pricing'))
                    <a href="{{ route('website.pricing') }}" class="block px-4 py-2 text-base font-medium rounded-lg transition-colors {{ request()->routeIs('website.pricing') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:text-indigo-600 hover:bg-indigo-50' }}">
                        Pricing
                    </a>
                @endif
                
                @if(Route::has('website.services'))
                    <a href="{{ route('website.services') }}" class="block px-4 py-2 text-base font-medium rounded-lg transition-colors {{ request()->routeIs('website.services') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:text-indigo-600 hover:bg-indigo-50' }}">
                        Services
                    </a>
                @endif
                
                @if(Route::has('website.portfolio'))
                    <a href="{{ route('website.portfolio') }}" class="block px-4 py-2 text-base font-medium rounded-lg transition-colors {{ request()->routeIs('website.portfolio') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:text-indigo-600 hover:bg-indigo-50' }}">
                        Portfolio
                    </a>
                @endif
                
                @if(Route::has('website.blog'))
                    <a href="{{ route('website.blog') }}" class="block px-4 py-2 text-base font-medium rounded-lg transition-colors {{ request()->routeIs('website.blog') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:text-indigo-600 hover:bg-indigo-50' }}">
                        Blog
                    </a>
                @endif
                
                @if(Route::has('website.about'))
                    <a href="{{ route('website.about') }}" class="block px-4 py-2 text-base font-medium rounded-lg transition-colors {{ request()->routeIs('website.about') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:text-indigo-600 hover:bg-indigo-50' }}">
                        About
                    </a>
                @endif
                
                @if(Route::has('website.contact'))
                    <a href="{{ route('website.contact') }}" class="block px-4 py-2 text-base font-medium rounded-lg transition-colors {{ request()->routeIs('website.contact') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:text-indigo-600 hover:bg-indigo-50' }}">
                        Contact
                    </a>
                @endif
                
                @guest
                    <div class="pt-4 space-y-2 border-t border-gray-100">
                        @if(Route::has('login'))
                            <a href="{{ route('login') }}" class="block w-full text-center px-4 py-2 text-sm font-medium text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                                Log in
                            </a>
                        @endif
                        @if(Route::has('register'))
                            <a href="{{ route('register') }}" class="block w-full text-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition">
                                Get Started Free
                            </a>
                        @endif
                    </div>
                @endguest
            </div>
        </div>
    </nav>
</header>