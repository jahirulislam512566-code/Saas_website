<section class="relative overflow-hidden pt-16 pb-24 md:pt-24 md:pb-32 bg-gradient-to-br from-indigo-50 via-white to-cyan-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div>
                <div class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-100 text-indigo-800 text-sm font-medium mb-6">
                    🚀 Launch your next project
                </div>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight text-gray-900">
                    Build Stunning Websites<br>
                    <span class="text-indigo-600">Without Code</span>
                </h1>
                <p class="mt-6 text-xl text-gray-600 max-w-lg">
                    Create beautiful, responsive websites in minutes with our drag‑and‑drop builder. No coding skills required.
                </p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('register') }}" class="px-8 py-4 bg-indigo-600 text-white text-lg font-semibold rounded-xl shadow-lg hover:bg-indigo-700 transition transform hover:scale-105">
                        Start Free Trial
                    </a>
                    <a href="{{ route('landing.features') }}" class="px-8 py-4 bg-white text-gray-700 text-lg font-semibold rounded-xl shadow hover:shadow-lg transition border border-gray-200">
                        Learn More →
                    </a>
                </div>
                <div class="mt-8 flex items-center gap-6 text-sm text-gray-500">
                    <span class="flex items-center gap-1">✅ No credit card</span>
                    <span class="flex items-center gap-1">✅ 14‑day free trial</span>
                    <span class="flex items-center gap-1">✅ Cancel anytime</span>
                </div>
            </div>
            <div class="relative">
                <div class="bg-gradient-to-br from-indigo-400 to-cyan-400 rounded-3xl p-2 shadow-2xl">
                    <img src="{{ asset('images/hero-dashboard.png') }}" alt="Dashboard preview" class="rounded-2xl w-full shadow-lg" />
                </div>
                <div class="absolute -bottom-6 -left-6 bg-white rounded-xl shadow-xl px-4 py-3 hidden sm:flex items-center gap-3">
                    <span class="text-2xl">⭐</span>
                    <div>
                        <p class="font-bold text-gray-900">4.9/5</p>
                        <p class="text-xs text-gray-500">Based on 200+ reviews</p>
                    </div>
                </div>
                <div class="absolute -top-4 -right-4 bg-white rounded-xl shadow-xl px-4 py-3 hidden sm:flex items-center gap-3">
                    <span class="text-2xl">🚀</span>
                    <div>
                        <p class="font-bold text-gray-900">10k+</p>
                        <p class="text-xs text-gray-500">Websites built</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>