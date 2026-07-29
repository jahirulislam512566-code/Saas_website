{{-- resources/views/components/sections/newsletter.blade.php --}}
@props([
    'title' => 'Subscribe to Our Newsletter',
    'subtitle' => 'Stay Updated',
    'description' => 'Get the latest insights, tips, and updates delivered to your inbox.',
    'formAction' => '#',
    'formMethod' => 'POST',
    'buttonText' => 'Subscribe',
    'placeholder' => 'Enter your email',
    'privacyText' => 'We respect your privacy. Unsubscribe at any time.',
])

<section class="py-20 bg-gradient-to-r from-[#6c5ce7] to-[#a78bfa]">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto text-center">
            <span class="text-sm font-semibold text-white/80 uppercase tracking-wider">{{ $subtitle }}</span>
            <h2 class="text-3xl md:text-4xl font-bold mt-2 text-white">{{ $title }}</h2>
            <p class="mt-4 text-lg text-white/80">{{ $description }}</p>

            <form action="{{ $formAction }}" method="{{ $formMethod }}" class="mt-8 flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
                <input type="email" 
                       name="email" 
                       placeholder="{{ $placeholder }}" 
                       required
                       class="flex-1 px-5 py-3.5 bg-white/10 border border-white/20 rounded-xl text-white placeholder:text-white/70 focus:outline-none focus:ring-2 focus:ring-white/30 transition-all">
                <button type="submit" class="px-8 py-3.5 bg-white text-[#6c5ce7] font-semibold rounded-xl hover:bg-gray-100 transition-all duration-300 shadow-lg shadow-[#0a0a0a]/20">
                    {{ $buttonText }}
                </button>
            </form>

            @if($privacyText)
                <p class="mt-4 text-sm text-white/60">{{ $privacyText }}</p>
            @endif
        </div>
    </div>
</section>