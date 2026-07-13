<nav x-data="{ open: false }" class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('website.home') }}" class="flex items-center space-x-2">
                    <span class="text-2xl font-bold text-indigo-600">SaaS</span>
                    <span class="text-gray-500 font-light">Builder</span>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('website.home') }}" class="text-gray-700 hover:text-indigo-600 transition">Home</a>
                <a href="{{ route('website.about') }}" class="text-gray-700 hover:text-indigo-600 transition">About</a>
                <a href="{{ route('website.services') }}" class="text-gray-700 hover:text-indigo-600 transition">Services</a>
                <a href="{{ route('website.contact') }}" class="text-gray-700 hover:text-indigo-600 transition">Contact</a>
                <a href="{{ route('login') }}" class="text-gray-700 hover:text-indigo-600 transition">Log In</a>
                <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">Get Started</a>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button @click="open = !open" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <div x-show="open" x-transition class="md:hidden bg-white border-t border-gray-200">
        <div class="px-2 pt-2 pb-3 space-y-1">
            <a href="{{ route('website.home') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50">Home</a>
            <a href="{{ route('website.about') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50">About</a>
            <a href="{{ route('website.services') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50">Services</a>
            <a href="{{ route('website.contact') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50">Contact</a>
            <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50">Log In</a>
            <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-base font-medium bg-indigo-600 text-white hover:bg-indigo-700">Get Started</a>
        </div>
    </div>
</nav>