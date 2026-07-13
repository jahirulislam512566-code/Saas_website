<section class="py-20 bg-indigo-600">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-5xl font-bold text-white">@yield('cta-title', 'Ready to get started?')</h2>
        <p class="mt-4 text-xl text-indigo-100">@yield('cta-subtitle', 'Join thousands of happy customers.')</p>
        <div class="mt-8 flex flex-wrap justify-center gap-4">
            @yield('cta-buttons')
        </div>
    </div>
</section>