{{-- resources/views/themes/restaurant/partials/footer.blade.php --}}
<footer class="bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Brand -->
            <div class="col-span-1 lg:col-span-1">
                <a href="{{ route('themes.restaurant.home') }}" class="flex items-center gap-2 mb-4">
                    <div class="w-10 h-10 rounded-full bg-restaurant-600 flex items-center justify-center">
                        <i class="fas fa-utensils text-white text-xl"></i>
                    </div>
                    <span class="text-2xl font-display font-bold tracking-tight">
                        Le<span class="text-gold-500">Gourmet</span>
                    </span>
                </a>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Experience the finest dining with exceptional flavors, crafted with passion and served with warmth.
                </p>
                <div class="flex gap-4 mt-4">
                    <a href="#" class="text-gray-400 hover:text-gold-400 transition"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-gray-400 hover:text-gold-400 transition"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="text-gray-400 hover:text-gold-400 transition"><i class="fab fa-tripadvisor"></i></a>
                    <a href="#" class="text-gray-400 hover:text-gold-400 transition"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div>
                <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-400">Quick Links</h3>
                <ul class="mt-4 space-y-2">
                    <li><a href="{{ route('themes.restaurant.home') }}" class="text-gray-300 hover:text-gold-400 transition">Home</a></li>
                    <li><a href="{{ route('themes.restaurant.about') }}" class="text-gray-300 hover:text-gold-400 transition">About Us</a></li>
                    <li><a href="{{ route('themes.restaurant.menu') }}" class="text-gray-300 hover:text-gold-400 transition">Menu</a></li>
                    <li><a href="{{ route('themes.restaurant.contact') }}" class="text-gray-300 hover:text-gold-400 transition">Contact</a></li>
                </ul>
            </div>
            
            <!-- Hours -->
            <div>
                <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-400">Opening Hours</h3>
                <ul class="mt-4 space-y-2 text-gray-300">
                    <li class="flex justify-between">
                        <span>Monday - Friday</span>
                        <span>11:00 AM - 11:00 PM</span>
                    </li>
                    <li class="flex justify-between">
                        <span>Saturday</span>
                        <span>10:00 AM - 12:00 AM</span>
                    </li>
                    <li class="flex justify-between">
                        <span>Sunday</span>
                        <span>10:00 AM - 10:00 PM</span>
                    </li>
                </ul>
            </div>
            
            <!-- Contact -->
            <div>
                <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-400">Contact</h3>
                <ul class="mt-4 space-y-3 text-gray-300">
                    <li class="flex items-start gap-3">
                        <i class="fas fa-map-marker-alt text-gold-400 mt-1"></i>
                        <span>123 Gourmet Street, NYC 10001</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fas fa-phone text-gold-400 mt-1"></i>
                        <span>+1 (555) 123-4567</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fas fa-envelope text-gold-400 mt-1"></i>
                        <span>info@legourmet.com</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Bottom -->
        <div class="mt-12 pt-8 border-t border-gray-800 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-sm text-gray-400">
                &copy; {{ date('Y') }} Le Gourmet Restaurant. All rights reserved.
            </p>
            <div class="flex items-center gap-6 text-sm text-gray-400">
                <a href="#" class="hover:text-white transition">Privacy Policy</a>
                <a href="#" class="hover:text-white transition">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>