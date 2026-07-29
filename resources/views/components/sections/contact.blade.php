{{-- resources/views/components/sections/contact.blade.php --}}
@props([
    'title' => 'Get In Touch',
    'subtitle' => 'Contact Us',
    'description' => 'Have a project in mind? Let\'s create something amazing together.',
    'formAction' => '#',
    'formMethod' => 'POST',
    'buttonText' => 'Send Message',
    'info' => [],
])

<section class="py-20 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 max-w-6xl mx-auto">
            {{-- Form --}}
            <div>
                <span class="text-sm font-semibold text-[#6c5ce7] uppercase tracking-wider">{{ $subtitle }}</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-2 text-[#0a0a0a]">{{ $title }}</h2>
                <p class="mt-4 text-lg text-[#4a4a4a]">{{ $description }}</p>

                <form action="{{ $formAction }}" method="{{ $formMethod }}" class="mt-8 space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <x-forms.input name="name" label="Full Name" placeholder="John Doe" required />
                        <x-forms.input name="email" type="email" label="Email Address" placeholder="john@example.com" required />
                    </div>
                    <x-forms.input name="subject" label="Subject" placeholder="Project Discussion" />
                    <x-forms.textarea name="message" label="Message" placeholder="Tell us about your project..." rows="5" required />
                    <x-ui.button variant="primary" size="lg" full-width>
                        {{ $buttonText }}
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </x-ui.button>
                </form>
            </div>

            {{-- Contact Info --}}
            <div class="space-y-8">
                @foreach($info as $item)
                    <div class="flex gap-4 p-6 bg-gray-50 rounded-2xl">
                        <div class="flex-shrink-0 w-12 h-12 bg-[#6c5ce7]/10 rounded-xl flex items-center justify-center text-2xl">
                            {{ $item['icon'] ?? '📞' }}
                        </div>
                        <div>
                            <h4 class="font-semibold text-[#0a0a0a]">{{ $item['title'] }}</h4>
                            <p class="text-[#4a4a4a]">{{ $item['value'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>