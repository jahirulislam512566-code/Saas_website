<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900">@yield('features-title', 'Key Features')</h2>
            <p class="mt-4 text-xl text-gray-600">@yield('features-subtitle', 'Everything you need to succeed.')</p>
        </div>
        <div class="mt-16 grid md:grid-cols-3 gap-8">
            <!-- Feature items can be yielded or passed via slots -->
            @yield('features-items')
        </div>
    </div>
</section>