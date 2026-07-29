<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        {{-- Section Header --}}
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-block px-4 py-1 bg-orange-100 text-orange-600 text-sm font-semibold rounded-full mb-4">
                Integrations
            </span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Connect with Your Favorite Tools
            </h2>
            <p class="text-lg text-gray-600">
                Integrate with 100+ tools to power up your website
            </p>
        </div>

        {{-- Integrations Grid --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @php
                $integrations = [
                    ['name' => 'Google Analytics', 'icon' => '📊', 'color' => 'blue'],
                    ['name' => 'Mailchimp', 'icon' => '📧', 'color' => 'yellow'],
                    ['name' => 'Stripe', 'icon' => '💳', 'color' => 'purple'],
                    ['name' => 'Salesforce', 'icon' => '☁️', 'color' => 'blue'],
                    ['name' => 'Slack', 'icon' => '💬', 'color' => 'green'],
                    ['name' => 'Zapier', 'icon' => '⚡', 'color' => 'orange'],
                    ['name' => 'HubSpot', 'icon' => '🔄', 'color' => 'orange'],
                    ['name' => 'PayPal', 'icon' => '💙', 'color' => 'blue'],
                    ['name' => 'Google Ads', 'icon' => '📈', 'color' => 'blue'],
                    ['name' => 'Facebook', 'icon' => '👍', 'color' => 'blue'],
                    ['name' => 'Instagram', 'icon' => '📸', 'color' => 'pink'],
                    ['name' => 'Twitter', 'icon' => '🐦', 'color' => 'blue'],
                ]
            @endphp

            @foreach($integrations as $integration)
                <div class="bg-white rounded-xl shadow-md p-4 text-center hover:shadow-lg transition-all duration-300 hover:-translate-y-1 cursor-pointer">
                    <div class="text-3xl mb-2">{{ $integration['icon'] }}</div>
                    <p class="text-sm font-medium text-gray-700">{{ $integration['name'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- See All Integrations --}}
        <div class="text-center mt-12">
            <a href="#" class="inline-flex items-center gap-2 text-indigo-600 font-semibold hover:text-indigo-800 transition-colors">
                See All Integrations
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
        </div>
    </div>
</section>