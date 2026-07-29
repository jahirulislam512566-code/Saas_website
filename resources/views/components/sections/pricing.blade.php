{{-- resources/views/components/sections/pricing.blade.php --}}
@props([
    'title' => 'Choose Your Plan',
    'subtitle' => 'Pricing that scales with you',
    'description' => 'Select the perfect plan for your business needs.',
    'plans' => [],
    'billing' => true,
    'defaultBilling' => 'monthly',
])

<section class="py-20 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="text-sm font-semibold text-[#6c5ce7] uppercase tracking-wider">{{ $subtitle }}</span>
            <h2 class="text-3xl md:text-4xl font-bold mt-2 text-[#0a0a0a]">{{ $title }}</h2>
            <p class="mt-4 text-lg text-[#4a4a4a]">{{ $description }}</p>
        </div>

        {{-- Billing Toggle --}}
        @if($billing)
            <div class="flex items-center justify-center gap-4 mb-12" x-data="{ billing: '{{ $defaultBilling }}' }">
                <span class="text-sm font-medium" x-bind:class="billing === 'monthly' ? 'text-[#0a0a0a]' : 'text-[#4a4a4a]'">Monthly</span>
                <button class="w-14 h-8 bg-gray-200 rounded-full relative transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-[#6c5ce7]/20"
                        x-on:click="billing = billing === 'monthly' ? 'yearly' : 'monthly'">
                    <span class="absolute top-1 left-1 w-6 h-6 bg-white rounded-full shadow-md transition-transform duration-300"
                          x-bind:class="billing === 'yearly' ? 'translate-x-6' : ''"></span>
                </button>
                <span class="text-sm font-medium" x-bind:class="billing === 'yearly' ? 'text-[#0a0a0a]' : 'text-[#4a4a4a]'">
                    Yearly
                    <span class="ml-1.5 px-2 py-0.5 bg-emerald-100 text-emerald-700 text-xs rounded-full">Save 20%</span>
                </span>
            </div>
        @endif

        {{-- Pricing Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl mx-auto">
            @foreach($plans as $plan)
                <x-cards.pricing-card 
                    :name="$plan['name']"
                    :price="$plan['price']"
                    :currency="$plan['currency'] ?? '$'"
                    :period="$plan['period'] ?? 'month'"
                    :description="$plan['description'] ?? ''"
                    :features="$plan['features'] ?? []"
                    :cta-text="$plan['ctaText'] ?? 'Get Started'"
                    :cta-link="$plan['ctaLink'] ?? '#'"
                    :highlighted="$plan['highlighted'] ?? false"
                    :badge="$plan['badge'] ?? null"
                />
            @endforeach
        </div>
    </div>
</section>