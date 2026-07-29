{{-- resources/views/components/website/pricing-comparison.blade.php --}}
@props([
    'plans' => [],
    'features' => [],
    'currency' => '$',
    'billingPeriod' => 'monthly'
])

@php
    $defaultPlans = [
        [
            'name' => 'Starter',
            'description' => 'Perfect for small businesses and startups',
            'price' => 29,
            'period' => 'month',
            'badge' => null,
            'cta_text' => 'Get Started',
            'cta_link' => route('register'),
            'popular' => false,
            'features' => [
                'Up to 1,000 users',
                '10 GB storage',
                'Basic analytics',
                'Email support',
                'API access',
                'Monthly reports'
            ]
        ],
        [
            'name' => 'Professional',
            'description' => 'Ideal for growing businesses and teams',
            'price' => 79,
            'period' => 'month',
            'badge' => 'Most Popular',
            'cta_text' => 'Start Free Trial',
            'cta_link' => route('register'),
            'popular' => true,
            'features' => [
                'Up to 10,000 users',
                '100 GB storage',
                'Advanced analytics',
                'Priority support',
                'Full API access',
                'Weekly reports',
                'Custom integrations',
                'Team collaboration'
            ]
        ],
        [
            'name' => 'Enterprise',
            'description' => 'For large organizations with custom needs',
            'price' => 199,
            'period' => 'month',
            'badge' => null,
            'cta_text' => 'Contact Sales',
            'cta_link' => route('contact'),
            'popular' => false,
            'features' => [
                'Unlimited users',
                'Unlimited storage',
                'Custom analytics',
                '24/7 dedicated support',
                'Full API access',
                'Real-time reports',
                'Custom integrations',
                'Advanced security',
                'SLA guarantee',
                'Dedicated account manager'
            ]
        ]
    ];

    $plans = !empty($plans) ? $plans : $defaultPlans;

    // Ensure all plans have required keys with defaults
    $plans = array_map(function($plan) {
        return array_merge([
            'name' => 'Plan',
            'description' => '',
            'price' => 0,
            'period' => 'month',
            'badge' => null,
            'cta_text' => 'Get Started',
            'cta_link' => '#',
            'popular' => false,
            'features' => []
        ], $plan);
    }, $plans);

    $defaultFeatures = [
        'Users',
        'Storage',
        'Analytics',
        'Support',
        'API Access',
        'Reports',
        'Integrations',
        'Collaboration',
        'Security',
        'SLA Guarantee'
    ];

    $features = !empty($features) ? $features : $defaultFeatures;
@endphp

<div class="w-full overflow-x-auto" x-data="pricingComparison()">
    {{-- Toggle for Billing Period --}}
    <div class="flex justify-center items-center space-x-4 mb-10">
        <span class="text-sm font-medium text-gray-700" :class="{'text-gray-900 font-semibold': billing === 'monthly'}">Monthly</span>
        <button 
            @click="toggleBilling()"
            class="relative inline-flex items-center h-6 rounded-full w-11 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
            :class="{'bg-primary-600': billing === 'annual', 'bg-gray-300': billing === 'monthly'}"
            role="switch"
            :aria-checked="billing === 'annual'"
        >
            <span class="sr-only">Toggle billing period</span>
            <span 
                class="inline-block w-4 h-4 transform bg-white rounded-full shadow transition-transform duration-200"
                :class="{'translate-x-6': billing === 'annual', 'translate-x-1': billing === 'monthly'}"
            ></span>
        </button>
        <span class="text-sm font-medium text-gray-700" :class="{'text-gray-900 font-semibold': billing === 'annual'}">
            Annual
            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                Save 20%
            </span>
        </span>
    </div>

    {{-- Comparison Table --}}
    <div class="min-w-[768px]">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="text-left py-4 px-6 text-sm font-semibold text-gray-900 bg-gray-50 rounded-l-lg">
                        Features
                    </th>
                    @foreach($plans as $plan)
                        <th class="text-center py-4 px-6 text-sm font-semibold bg-gray-50 {{ $loop->last ? 'rounded-r-lg' : '' }}">
                            <div class="flex flex-col items-center">
                                <span class="text-lg font-bold text-gray-900">{{ $plan['name'] }}</span>
                                @if(!empty($plan['badge']))
                                    <span class="mt-1 inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                        {{ $plan['badge'] }}
                                    </span>
                                @endif
                                <div class="mt-3">
                                    <span class="text-3xl font-bold text-gray-900" x-text="formatPrice({{ $plan['price'] }})"></span>
                                    <span class="text-sm text-gray-500">/{{ $plan['period'] }}</span>
                                </div>
                                <p class="mt-2 text-sm text-gray-500">{{ $plan['description'] }}</p>
                                <a 
                                    href="{{ $plan['cta_link'] }}" 
                                    class="mt-4 w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white {{ isset($plan['popular']) && $plan['popular'] ? 'bg-primary-600 hover:bg-primary-700' : 'bg-gray-600 hover:bg-gray-700' }} transition-colors duration-200"
                                >
                                    {{ $plan['cta_text'] }}
                                </a>
                            </div>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($features as $feature)
                    <tr class="{{ $loop->even ? 'bg-gray-50/50' : 'bg-white' }}">
                        <td class="py-4 px-6 text-sm text-gray-900 font-medium">
                            {{ $feature }}
                        </td>
                        @foreach($plans as $plan)
                            <td class="text-center py-4 px-6">
                                @php
                                    $hasFeature = in_array($feature, $plan['features'] ?? []);
                                @endphp
                                @if($hasFeature)
                                    <svg class="w-5 h-5 mx-auto text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 mx-auto text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td class="py-6 px-6 bg-gray-50 rounded-bl-lg"></td>
                    @foreach($plans as $plan)
                        <td class="text-center py-6 px-6 bg-gray-50 {{ $loop->last ? 'rounded-br-lg' : '' }}">
                            <a 
                                href="{{ $plan['cta_link'] }}" 
                                class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white {{ isset($plan['popular']) && $plan['popular'] ? 'bg-primary-600 hover:bg-primary-700' : 'bg-gray-600 hover:bg-gray-700' }} transition-colors duration-200 w-full"
                            >
                                {{ $plan['cta_text'] }}
                            </a>
                            @if(isset($plan['popular']) && $plan['popular'])
                                <p class="mt-2 text-xs text-gray-500">No credit card required</p>
                            @endif
                        </td>
                    @endforeach
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- Note about pricing --}}
    <div class="mt-6 text-center">
        <p class="text-sm text-gray-500">
            * All plans include a 14-day free trial. No credit card required.
            <br class="hidden sm:inline">
            Volume discounts available for enterprise plans.
        </p>
    </div>
</div>

@push('scripts')
<script>
    function pricingComparison() {
        return {
            billing: 'monthly',
            prices: @json(array_column($plans, 'price')),
            
            toggleBilling() {
                this.billing = this.billing === 'monthly' ? 'annual' : 'monthly';
            },
            
            formatPrice(price) {
                if (this.billing === 'annual') {
                    return '{{ $currency }}' + (price * 12 * 0.8).toFixed(0);
                }
                return '{{ $currency }}' + price;
            },
            
            getPrice(index) {
                if (this.billing === 'annual') {
                    return this.prices[index] * 12 * 0.8;
                }
                return this.prices[index];
            }
        };
    }
</script>
@endpush