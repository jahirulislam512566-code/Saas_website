<!-- Navbar -->
<nav x-data="{ open: false }" class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('landing.home') }}" class="flex items-center space-x-2">
                    <span class="text-2xl font-bold text-indigo-600">SaaS</span>
                    <span class="text-gray-500 font-light">Builder</span>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('landing.features') }}" class="text-gray-700 hover:text-indigo-600 transition">Features</a>
                <a href="{{ route('landing.templates') }}" class="text-gray-700 hover:text-indigo-600 transition">Templates</a>
                <a href="{{ route('landing.pricing') }}" class="text-gray-700 hover:text-indigo-600 transition">Pricing</a>
                <a href="#testimonials" class="text-gray-700 hover:text-indigo-600 transition">Testimonials</a>
                <a href="{{ route('login') }}" class="text-gray-700 hover:text-indigo-600 transition">Log In</a>
                <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">Start Free Trial</a>
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
            <a href="{{ route('landing.features') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50">Features</a>
            <a href="{{ route('landing.templates') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50">Templates</a>
            <a href="{{ route('landing.pricing') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50">Pricing</a>
            <a href="#testimonials" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50">Testimonials</a>
            <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50">Log In</a>
            <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-base font-medium bg-indigo-600 text-white hover:bg-indigo-700">Start Free Trial</a>
        </div>
    </div>
</nav>