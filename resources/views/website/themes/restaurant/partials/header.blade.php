{{-- resources/views/themes/restaurant/partials/header.blade.php --}}
<header x-data="{ mobileMenuOpen: false, scrolled: false }" 
        x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 50 })"
        class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
        :class="scrolled ? 'bg-white/95 backdrop-blur-md shadow-lg' : 'bg-transparent'">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="{{ route('themes.restaurant.home') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-restaurant-600 flex items-center justify-center shadow-lg">
                        <i class="fas fa-utensils text-white text-xl"></i>
                    </div>
                    <span class="text-2xl font-display font-bold tracking-tight" :class="scrolled ? 'text-gray-900' : 'text-white'">
                        Le<span class="text-gold-500">Gourmet</span>
                    </span>
                </a>
            </div>
            
            <!-- Desktop Navigation -->
            <div class="hidden lg:flex items-center space-x-8">
                <x-restaurant-nav-link href="{{ route('themes.restaurant.home') }}" :active="request()->routeIs('themes.restaurant.home')">
                    Home
                </x-restaurant-nav-link>
                <x-restaurant-nav-link href="{{ route('themes.restaurant.about') }}" :active="request()->routeIs('themes.restaurant.about')">
                    About
                </x-restaurant-nav-link>
                <x-restaurant-nav-link href="{{ route('themes.restaurant.menu') }}" :active="request()->routeIs('themes.restaurant.menu')">
                    Menu
                </x-restaurant-nav-link>
                <x-restaurant-nav-link href="{{ route('themes.restaurant.contact') }}" :active="request()->routeIs('themes.restaurant.contact')">
                    Contact
                </x-restaurant-nav-link>
            </div>
            
            <!-- Reservation CTA -->
            <div class="hidden lg:flex items-center gap-4">
                <a href="{{ route('themes.restaurant.contact') }}" class="px-6 py-2.5 text-sm font-semibold text-white bg-restaurant-600 hover:bg-restaurant-700 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                    <i class="fas fa-calendar-check mr-2"></i>Reserve a Table
                </a>
            </div>
            
            <!-- Mobile Menu Button -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 rounded-lg text-white hover:bg-white/10 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
        
        <!-- Mobile Navigation -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="lg:hidden py-4 bg-white rounded-b-2xl shadow-xl">
            <div class="space-y-1">
                <a href="{{ route('themes.restaurant.home') }}" class="block px-4 py-2 text-base font-medium rounded-lg transition-colors {{ request()->routeIs('themes.restaurant.home') ? 'text-restaurant-600 bg-restaurant-50' : 'text-gray-700 hover:text-restaurant-600 hover:bg-restaurant-50' }}">
                    Home
                </a>
                <a href="{{ route('themes.restaurant.about') }}" class="block px-4 py-2 text-base font-medium rounded-lg transition-colors {{ request()->routeIs('themes.restaurant.about') ? 'text-restaurant-600 bg-restaurant-50' : 'text-gray-700 hover:text-restaurant-600 hover:bg-restaurant-50' }}">
                    About
                </a>
                <a href="{{ route('themes.restaurant.menu') }}" class="block px-4 py-2 text-base font-medium rounded-lg transition-colors {{ request()->routeIs('themes.restaurant.menu') ? 'text-restaurant-600 bg-restaurant-50' : 'text-gray-700 hover:text-restaurant-600 hover:bg-restaurant-50' }}">
                    Menu
                </a>
                <a href="{{ route('themes.restaurant.contact') }}" class="block px-4 py-2 text-base font-medium rounded-lg transition-colors {{ request()->routeIs('themes.restaurant.contact') ? 'text-restaurant-600 bg-restaurant-50' : 'text-gray-700 hover:text-restaurant-600 hover:bg-restaurant-50' }}">
                    Contact
                </a>
                <div class="pt-4 border-t border-gray-100">
                    <a href="{{ route('themes.restaurant.contact') }}" class="block w-full text-center px-4 py-2 text-sm font-medium text-white bg-restaurant-600 rounded-lg hover:bg-restaurant-700 transition">
                        Reserve a Table
                    </a>
                </div>
            </div>
        </div>
    </nav>
</header>