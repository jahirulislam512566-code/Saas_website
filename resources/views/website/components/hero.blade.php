<section class="relative overflow-hidden pt-16 pb-24 md:pt-24 md:pb-32 bg-gradient-to-br from-indigo-50 via-white to-cyan-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight text-gray-900">
                    @yield('hero-title', 'Build Something Amazing')
                </h1>
                <p class="mt-6 text-xl text-gray-600 max-w-lg">
                    @yield('hero-subtitle', 'Create beautiful, responsive websites in minutes.')
                </p>
                <div class="mt-8 flex flex-wrap gap-4">
                    @yield('hero-actions')
                </div>
            </div>
            <div class="relative">
                <div class="bg-gradient-to-br from-indigo-400 to-cyan-400 rounded-3xl p-2 shadow-2xl">
                    <img src="{{ asset('images/hero-dashboard.png') }}" alt="Hero image" class="rounded-2xl w-full shadow-lg" />
                </div>
            </div>
        </div>
    </div>
</section>