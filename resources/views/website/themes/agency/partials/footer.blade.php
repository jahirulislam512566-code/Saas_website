{{-- resources/views/themes/agency/partials/footer.blade.php --}}
<footer class="bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Brand -->
            <div class="col-span-1 lg:col-span-1">
                <a href="{{ route('agency.home') }}" class="flex items-center gap-2 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-secondary-500 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                        </svg>
                    </div>
                    <span class="text-xl font-extrabold tracking-tight">Agency<span class="text-primary-400">Hub</span></span>
                </a>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Empowering brands with creative solutions and digital excellence.
                </p>
                <div class="flex gap-4 mt-4">
                    <a href="#" class="text-gray-400 hover:text-primary-400 transition"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-gray-400 hover:text-primary-400 transition"><i class="fab fa-linkedin"></i></a>
                    <a href="#" class="text-gray-400 hover:text-primary-400 transition"><i class="fab fa-github"></i></a>
                    <a href="#" class="text-gray-400 hover:text-primary-400 transition"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            
            <!-- Services -->
            <div>
                <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-400">Services</h3>
                <ul class="mt-4 space-y-2">
                    <li><a href="#" class="text-gray-300 hover:text-white transition">Branding</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-white transition">Web Development</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-white transition">Digital Marketing</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-white transition">UI/UX Design</a></li>
                </ul>
            </div>
            
            <!-- Company -->
            <div>
                <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-400">Company</h3>
                <ul class="mt-4 space-y-2">
                    <li><a href="{{ route('agency.about') }}" class="text-gray-300 hover:text-white transition">About</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-white transition">Careers</a></li>
                    <li><a href="{{ route('agency.contact') }}" class="text-gray-300 hover:text-white transition">Contact</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-white transition">Blog</a></li>
                </ul>
            </div>
            
            <!-- Contact -->
            <div>
                <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-400">Contact</h3>
                <ul class="mt-4 space-y-3 text-gray-300">
                    <li class="flex items-start gap-3">
                        <i class="fas fa-map-marker-alt text-primary-400 mt-1"></i>
                        <span>123 Agency Street, NY 10001</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fas fa-phone text-primary-400 mt-1"></i>
                        <span>+1 (555) 123-4567</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fas fa-envelope text-primary-400 mt-1"></i>
                        <span>hello@agencyhub.com</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Bottom -->
        <div class="mt-12 pt-8 border-t border-gray-800 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-sm text-gray-400">
                &copy; {{ date('Y') }} Agency Hub. All rights reserved.
            </p>
            <div class="flex items-center gap-6 text-sm text-gray-400">
                <a href="#" class="hover:text-white transition">Privacy Policy</a>
                <a href="#" class="hover:text-white transition">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>