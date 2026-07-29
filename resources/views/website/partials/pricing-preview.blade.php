<section id="pricing" class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        {{-- Section Header --}}
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-block px-4 py-1.5 bg-indigo-100 text-indigo-600 text-sm font-semibold rounded-full mb-4">
                Pricing
            </span>
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4 leading-tight">
                Simple, Transparent Pricing
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Choose the plan that fits your business needs
            </p>

            {{-- Billing Toggle --}}
            <div class="flex items-center justify-center gap-4 mt-6" x-data="{ monthly: true }">
                <span class="text-sm font-medium" :class="monthly ? 'text-gray-900' : 'text-gray-500'">Monthly</span>
                <button @click="monthly = !monthly" 
                        class="relative w-14 h-8 bg-indigo-600 rounded-full p-1 transition-colors" 
                        :class="monthly ? 'bg-indigo-600' : 'bg-gray-300'"
                        type="button"
                        aria-label="Toggle billing period">
                    <div class="w-6 h-6 bg-white rounded-full shadow transform transition-transform" 
                         :class="monthly ? 'translate-x-0' : 'translate-x-6'"></div>
                </button>
                <span class="text-sm font-medium" :class="!monthly ? 'text-gray-900' : 'text-gray-500'">Yearly</span>
                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">Save 20%</span>
            </div>
        </div>

        {{-- Pricing Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto" x-data="{ monthly: true }">
            @php
                $plans = [
                    [
                        'name' => 'Starter',
                        'price' => 19,
                        'description' => 'Perfect for getting started',
                        'features' => [
                            '1 Website',
                            '10 GB Storage',
                            '5 Pages',
                            'Free SSL Certificate',
                            'Basic SEO Tools',
                            'Email Support'
                        ],
                        'button_text' => 'Start Free Trial',
                        'button_link' => route('register'),
                        'popular' => false
                    ],
                    [
                        'name' => 'Business',
                        'price' => 49,
                        'description' => 'For growing businesses',
                        'features' => [
                            '10 Websites',
                            '50 GB Storage',
                            'Unlimited Pages',
                            'Free SSL Certificate',
                            'Advanced SEO Tools',
                            'Priority Support',
                            'Custom Domain',
                            'AI Content Assistant'
                        ],
                        'button_text' => 'Start Free Trial',
                        'button_link' => route('register'),
                        'popular' => true
                    ],
                    [
                        'name' => 'Agency',
                        'price' => 99,
                        'description' => 'For large organizations',
                        'features' => [
                            'Unlimited Websites',
                            '500 GB Storage',
                            'Unlimited Pages',
                            'Free SSL Certificate',
                            'Advanced SEO Tools',
                            '24/7 Priority Support',
                            'Multiple Custom Domains',
                            'AI Content Assistant',
                            'White Label',
                            'API Access'
                        ],
                        'button_text' => 'Contact Sales',
                        'button_link' => route('contact'),
                        'popular' => false
                    ],
                ]
            @endphp

            @foreach($plans as $index => $plan)
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl {{ $plan['popular'] ? 'ring-2 ring-indigo-600 relative' : '' }}">
                    @if($plan['popular'])
                        <div class="absolute top-0 right-0 z-10">
                            <div class="bg-indigo-600 text-white text-xs font-bold px-4 py-1 rounded-bl-lg">
                                Popular
                            </div>
                        </div>
                    @endif

                    <div class="p-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $plan['name'] }}</h3>
                        <p class="text-gray-500 text-sm mb-4">{{ $plan['description'] }}</p>
                        
                        <div class="mb-6">
                            <span class="text-4xl font-bold text-gray-900" 
                                  x-text="monthly ? '{{ $plan['price'] }}' : '{{ number_format($plan['price'] * 12 * 0.8 / 12, 2) }}'">
                                {{ $plan['price'] }}
                            </span>
                            <span class="text-gray-500 text-sm">/month</span>
                            <p x-show="!monthly" class="text-xs text-green-600 font-medium mt-1">Billed annually (Save 20%)</p>
                        </div>

                        {{-- Features --}}
                        <ul class="space-y-3 mb-8">
                            @foreach($plan['features'] as $feature)
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-gray-700 text-sm">{{ $feature }}</span>
                                </li>
                            @endforeach
                        </ul>

                        {{-- Button --}}
                        <a href="{{ $plan['button_link'] }}" 
                           class="block w-full text-center px-6 py-3 {{ $plan['popular'] ? 'bg-indigo-600 hover:bg-indigo-700 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-900' }} font-bold rounded-xl transition-colors">
                            {{ $plan['button_text'] }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- View All Pricing --}}
        <div class="text-center mt-12">
            <a href="{{ route('pricing') }}" class="inline-flex items-center gap-2 text-indigo-600 font-semibold hover:text-indigo-800 transition-colors">
                View All Pricing Plans
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
        </div>
    </div>
</section>