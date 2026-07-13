<section id="pricing" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900">Choose your plan</h2>
            <p class="mt-4 text-xl text-gray-600">Simple, transparent pricing. No hidden fees.</p>
        </div>

        <!-- Billing toggle (optional) -->
        <div class="mt-8 flex justify-center items-center space-x-4">
            <span class="text-sm font-medium text-gray-700">Monthly</span>
            <div class="w-12 h-6 bg-gray-300 rounded-full p-1 cursor-pointer" x-data="{ toggle: false }" @click="toggle = !toggle">
                <div class="w-4 h-4 bg-white rounded-full shadow transform transition" :class="toggle ? 'translate-x-6 bg-indigo-600' : ''"></div>
            </div>
            <span class="text-sm font-medium text-gray-700">Yearly <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Save 20%</span></span>
        </div>

        <div class="mt-16 grid md:grid-cols-3 gap-8">
            <!-- Free Plan -->
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200 hover:shadow-xl transition">
                <h3 class="text-2xl font-bold text-gray-900">Free</h3>
                <p class="mt-2 text-gray-600">Perfect for getting started</p>
                <div class="mt-4">
                    <span class="text-4xl font-bold">$0</span>
                    <span class="text-gray-500">/month</span>
                </div>
                <ul class="mt-6 space-y-3">
                    <li class="flex items-center gap-2">✅ 1 website</li>
                    <li class="flex items-center gap-2">✅ 5 pages</li>
                    <li class="flex items-center gap-2">✅ Basic templates</li>
                    <li class="flex items-center gap-2 text-gray-400">❌ Custom domain</li>
                </ul>
                <a href="{{ route('register') }}" class="mt-8 block text-center bg-gray-100 text-gray-700 py-3 rounded-xl font-semibold hover:bg-gray-200 transition">Get Started</a>
            </div>

            <!-- Pro Plan (Featured) -->
            <div class="bg-indigo-600 text-white p-8 rounded-2xl shadow-lg border-2 border-indigo-600 relative hover:shadow-xl transition transform md:scale-105">
                <span class="absolute -top-3 right-6 bg-yellow-400 text-gray-900 text-xs font-bold px-3 py-1 rounded-full">Most Popular</span>
                <h3 class="text-2xl font-bold">Pro</h3>
                <p class="mt-2 text-indigo-100">For growing businesses</p>
                <div class="mt-4">
                    <span class="text-4xl font-bold">$29</span>
                    <span class="text-indigo-100">/month</span>
                </div>
                <ul class="mt-6 space-y-3">
                    <li class="flex items-center gap-2">✅ 10 websites</li>
                    <li class="flex items-center gap-2">✅ Unlimited pages</li>
                    <li class="flex items-center gap-2">✅ All templates</li>
                    <li class="flex items-center gap-2">✅ Custom domain</li>
                    <li class="flex items-center gap-2">✅ Priority support</li>
                </ul>
                <a href="{{ route('register') }}" class="mt-8 block text-center bg-white text-indigo-600 py-3 rounded-xl font-semibold hover:bg-gray-100 transition">Start Free Trial</a>
            </div>

            <!-- Business Plan -->
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200 hover:shadow-xl transition">
                <h3 class="text-2xl font-bold text-gray-900">Business</h3>
                <p class="mt-2 text-gray-600">For large teams & agencies</p>
                <div class="mt-4">
                    <span class="text-4xl font-bold">$79</span>
                    <span class="text-gray-500">/month</span>
                </div>
                <ul class="mt-6 space-y-3">
                    <li class="flex items-center gap-2">✅ Unlimited websites</li>
                    <li class="flex items-center gap-2">✅ Unlimited pages</li>
                    <li class="flex items-center gap-2">✅ All templates + premium</li>
                    <li class="flex items-center gap-2">✅ Custom domain</li>
                    <li class="flex items-center gap-2">✅ 24/7 priority support</li>
                    <li class="flex items-center gap-2">✅ API access</li>
                </ul>
                <a href="{{ route('register') }}" class="mt-8 block text-center bg-gray-100 text-gray-700 py-3 rounded-xl font-semibold hover:bg-gray-200 transition">Contact Sales</a>
            </div>
        </div>
    </div>
</section>