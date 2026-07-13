{{-- resources/views/components/website/footer.blade.php --}}
<footer class="bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Brand -->
            <div class="col-span-1 lg:col-span-1">
                @if(Route::has('home'))
                    <a href="{{ route('home') }}" class="flex items-center gap-2 mb-4">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                            </svg>
                        </div>
                        <span class="text-xl font-extrabold tracking-tight">SaaS<span class="text-indigo-400">Hub</span></span>
                    </a>
                @else
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                            </svg>
                        </div>
                        <span class="text-xl font-extrabold tracking-tight">SaaS<span class="text-indigo-400">Hub</span></span>
                    </div>
                @endif
                
                <p class="text-gray-400 text-sm leading-relaxed">
                    Empowering businesses with cutting-edge SaaS solutions. Built with passion and precision.
                </p>
                <div class="flex gap-4 mt-4">
                    <a href="#" class="text-gray-400 hover:text-indigo-400 transition"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-gray-400 hover:text-indigo-400 transition"><i class="fab fa-linkedin"></i></a>
                    <a href="#" class="text-gray-400 hover:text-indigo-400 transition"><i class="fab fa-github"></i></a>
                    <a href="#" class="text-gray-400 hover:text-indigo-400 transition"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            
            <!-- Product -->
            <div>
                <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-400">Product</h3>
                <ul class="mt-4 space-y-2">
                    @if(Route::has('website.features'))
                        <li><a href="{{ route('website.features') }}" class="text-gray-300 hover:text-white transition">Features</a></li>
                    @endif
                    
                    @if(Route::has('website.pricing'))
                        <li><a href="{{ route('website.pricing') }}" class="text-gray-300 hover:text-white transition">Pricing</a></li>
                    @endif
                    
                    @if(Route::has('website.services'))
                        <li><a href="{{ route('website.services') }}" class="text-gray-300 hover:text-white transition">Services</a></li>
                    @endif
                    
                    <li><a href="#" class="text-gray-300 hover:text-white transition">Integrations</a></li>
                </ul>
            </div>
            
            <!-- Company -->
            <div>
                <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-400">Company</h3>
                <ul class="mt-4 space-y-2">
                    @if(Route::has('website.about'))
                        <li><a href="{{ route('website.about') }}" class="text-gray-300 hover:text-white transition">About</a></li>
                    @endif
                    
                    @if(Route::has('website.blog'))
                        <li><a href="{{ route('website.blog') }}" class="text-gray-300 hover:text-white transition">Blog</a></li>
                    @endif
                    
                    @if(Route::has('website.contact'))
                        <li><a href="{{ route('website.contact') }}" class="text-gray-300 hover:text-white transition">Contact</a></li>
                    @endif
                    
                    @if(Route::has('website.portfolio'))
                        <li><a href="{{ route('website.portfolio') }}" class="text-gray-300 hover:text-white transition">Portfolio</a></li>
                    @endif
                </ul>
            </div>
            
            <!-- Support -->
            <div>
                <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-400">Support</h3>
                <ul class="mt-4 space-y-2">
                    <li><a href="#" class="text-gray-300 hover:text-white transition">Help Center</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-white transition">Documentation</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-white transition">API Status</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-white transition">Security</a></li>
                </ul>
            </div>
        </div>
        
        <!-- Bottom -->
        <div class="mt-12 pt-8 border-t border-gray-800 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-sm text-gray-400">
                &copy; {{ date('Y') }} SaaS Hub. All rights reserved.
            </p>
            <div class="flex items-center gap-6 text-sm text-gray-400">
                <a href="#" class="hover:text-white transition">Privacy Policy</a>
                <a href="#" class="hover:text-white transition">Terms of Service</a>
                <a href="#" class="hover:text-white transition">Cookie Policy</a>
            </div>
        </div>
    </div>
</footer>