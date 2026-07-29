{{-- resources/views/themes/agency/partials/header.blade.php --}}
<header x-data="{ mobileMenuOpen: false, scrolled: false }" 
        x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 50 })"
        class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
        :class="scrolled ? 'bg-white/95 backdrop-blur-md shadow-lg' : 'bg-transparent'">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="{{ route('agency.home') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-600 to-secondary-500 flex items-center justify-center shadow-lg shadow-primary-200">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                        </svg>
                    </div>
                    <span class="text-2xl font-extrabold text-gray-900 tracking-tight" :class="scrolled ? 'text-gray-900' : 'text-white'">
                        Agency<span class="text-primary-600">Hub</span>
                    </span>
                </a>
            </div>
            
            <!-- Desktop Navigation -->
            <div class="hidden lg:flex items-center space-x-1">
                <x-theme-nav-link href="{{ route('agency.home') }}" :active="request()->routeIs('agency.home')">
                    Home
                </x-theme-nav-link>
                <x-theme-nav-link href="{{ route('agency.about') }}" :active="request()->routeIs('agency.about')">
                    About
                </x-theme-nav-link>
                <x-theme-nav-link href="{{ route('agency.services') }}" :active="request()->routeIs('agency.services')">
                    Services
                </x-theme-nav-link>
                <x-theme-nav-link href="{{ route('agency.portfolio') }}" :active="request()->routeIs('agency.portfolio')">
                    Portfolio
                </x-theme-nav-link>
                <x-theme-nav-link href="{{ route('agency.contact') }}" :active="request()->routeIs('agency.contact')">
                    Contact
                </x-theme-nav-link>
            </div>
            
            <!-- CTA Button -->
            <div class="hidden lg:flex items-center gap-4">
                <a href="{{ route('agency.contact') }}" class="px-6 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-primary-600 to-secondary-500 rounded-full hover:shadow-lg hover:scale-105 transition-all duration-300">
                    Get Started
                </a>
            </div>
            
            <!-- Mobile Menu Button -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition">
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
             class="lg:hidden py-4 border-t border-gray-100 bg-white rounded-b-2xl shadow-xl">
            <div class="space-y-1">
                <a href="{{ route('agency.home') }}" class="block px-4 py-2 text-base font-medium rounded-lg transition-colors {{ request()->routeIs('agency.home') ? 'text-primary-600 bg-primary-50' : 'text-gray-700 hover:text-primary-600 hover:bg-primary-50' }}">
                    Home
                </a>
                <a href="{{ route('agency.about') }}" class="block px-4 py-2 text-base font-medium rounded-lg transition-colors {{ request()->routeIs('agency.about') ? 'text-primary-600 bg-primary-50' : 'text-gray-700 hover:text-primary-600 hover:bg-primary-50' }}">
                    About
                </a>
                <a href="{{ route('agency.services') }}" class="block px-4 py-2 text-base font-medium rounded-lg transition-colors {{ request()->routeIs('agency.services') ? 'text-primary-600 bg-primary-50' : 'text-gray-700 hover:text-primary-600 hover:bg-primary-50' }}">
                    Services
                </a>
                <a href="{{ route('agency.portfolio') }}" class="block px-4 py-2 text-base font-medium rounded-lg transition-colors {{ request()->routeIs('agency.portfolio') ? 'text-primary-600 bg-primary-50' : 'text-gray-700 hover:text-primary-600 hover:bg-primary-50' }}">
                    Portfolio
                </a>
                <a href="{{ route('agency.contact') }}" class="block px-4 py-2 text-base font-medium rounded-lg transition-colors {{ request()->routeIs('agency.contact') ? 'text-primary-600 bg-primary-50' : 'text-gray-700 hover:text-primary-600 hover:bg-primary-50' }}">
                    Contact
                </a>
            </div>
        </div>
    </nav>
</header>