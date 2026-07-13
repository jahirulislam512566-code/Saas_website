<footer class="bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid md:grid-cols-4 gap-8">
            <div>
                <span class="text-2xl font-bold text-indigo-400">SaaS</span>
                <p class="mt-2 text-gray-400 text-sm">Build beautiful websites in minutes.</p>
            </div>
            <div>
                <h4 class="font-semibold">Product</h4>
                <ul class="mt-2 space-y-2 text-gray-400 text-sm">
                    <li><a href="{{ route('landing.features') }}" class="hover:text-white transition">Features</a></li>
                    <li><a href="{{ route('landing.templates') }}" class="hover:text-white transition">Templates</a></li>
                    <li><a href="{{ route('landing.pricing') }}" class="hover:text-white transition">Pricing</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold">Company</h4>
                <ul class="mt-2 space-y-2 text-gray-400 text-sm">
                    <li><a href="{{ route('landing.about') }}" class="hover:text-white transition">About</a></li>
                    <li><a href="#" class="hover:text-white transition">Blog</a></li>
                    <li><a href="#" class="hover:text-white transition">Careers</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold">Support</h4>
                <ul class="mt-2 space-y-2 text-gray-400 text-sm">
                    <li><a href="#" class="hover:text-white transition">Help Centre</a></li>
                    <li><a href="{{ route('landing.contact') }}" class="hover:text-white transition">Contact</a></li>
                    <li><a href="#" class="hover:text-white transition">Privacy Policy</a></li>
                </ul>
            </div>
        </div>
        <div class="mt-12 pt-8 border-t border-gray-800 text-sm text-gray-500 flex flex-col md:flex-row justify-between items-center">
            <p>&copy; {{ date('Y') }} SaaS Builder. All rights reserved.</p>
            <div class="flex gap-4 mt-4 md:mt-0">
                <a href="#" class="hover:text-white">Twitter</a>
                <a href="#" class="hover:text-white">LinkedIn</a>
                <a href="#" class="hover:text-white">GitHub</a>
            </div>
        </div>
    </div>
</footer>