{{-- resources/views/website/home.blade.php --}}
@extends('layouts.website')

@section('title', 'Home - SaaS Platform')

@section('content')
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-indigo-50 via-white to-purple-50 py-20 lg:py-28">
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-4xl mx-auto">
                <div class="mb-4">
                    <span class="px-3 py-1 text-sm font-medium text-indigo-600 bg-indigo-100 rounded-full">
                        🚀 New: AI-Powered Analytics
                    </span>
                </div>
                
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight text-gray-900 leading-tight">
                    Build Your SaaS Product Faster
                </h1>
                
                <p class="mt-6 text-xl text-gray-600 max-w-3xl mx-auto">
                    Everything you need to launch, scale, and grow your SaaS business. From billing to analytics, we've got you covered.
                </p>
                
                <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center">
                    {{-- FIXED: Use direct route names without website. prefix --}}
                    @if(Route::has('register'))
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-3 text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm hover:shadow transition-all">
                            Start Free Trial
                        </a>
                    @elseif(Route::has('website.register'))
                        <a href="{{ route('website.register') }}" class="inline-flex items-center justify-center px-8 py-3 text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm hover:shadow transition-all">
                            Start Free Trial
                        </a>
                    @else
                        <a href="#" class="inline-flex items-center justify-center px-8 py-3 text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm hover:shadow transition-all">
                            Start Free Trial
                        </a>
                    @endif
                    
                    @if(Route::has('features'))
                        <a href="{{ route('features') }}" class="inline-flex items-center justify-center px-8 py-3 text-base font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-lg shadow-sm hover:shadow transition-all">
                            View Features
                        </a>
                    @elseif(Route::has('website.features'))
                        <a href="{{ route('website.features') }}" class="inline-flex items-center justify-center px-8 py-3 text-base font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-lg shadow-sm hover:shadow transition-all">
                            View Features
                        </a>
                    @endif
                </div>
                
                <div class="mt-12 grid grid-cols-3 gap-8">
                    <div>
                        <p class="text-2xl font-bold text-gray-900">10K+</p>
                        <p class="text-sm text-gray-600">Active Users</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">99.9%</p>
                        <p class="text-sm text-gray-600">Uptime</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">4.9⭐</p>
                        <p class="text-sm text-gray-600">User Rating</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Features Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900">Everything You Need to Succeed</h2>
                <p class="mt-4 text-lg text-gray-600">From powerful features to seamless integrations, we provide the tools to build your dream product.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Feature cards -->
                <div class="group p-6 bg-white rounded-2xl shadow-sm hover:shadow-xl border border-gray-100 transition-all duration-300 hover:-translate-y-1">
                    <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                        <i class="fas fa-bolt text-xl"></i>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900">Lightning Fast</h3>
                    <p class="mt-2 text-gray-600 text-sm leading-relaxed">Optimized for speed with edge caching and CDN distribution worldwide.</p>
                </div>
                
                <div class="group p-6 bg-white rounded-2xl shadow-sm hover:shadow-xl border border-gray-100 transition-all duration-300 hover:-translate-y-1">
                    <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                        <i class="fas fa-shield-alt text-xl"></i>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900">Secure by Default</h3>
                    <p class="mt-2 text-gray-600 text-sm leading-relaxed">Enterprise-grade security with SOC2 compliance and end-to-end encryption.</p>
                </div>
                
                <div class="group p-6 bg-white rounded-2xl shadow-sm hover:shadow-xl border border-gray-100 transition-all duration-300 hover:-translate-y-1">
                    <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                        <i class="fas fa-chart-line text-xl"></i>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900">Real-time Analytics</h3>
                    <p class="mt-2 text-gray-600 text-sm leading-relaxed">Track user behavior, revenue, and growth metrics in real-time.</p>
                </div>
                
                <div class="group p-6 bg-white rounded-2xl shadow-sm hover:shadow-xl border border-gray-100 transition-all duration-300 hover:-translate-y-1">
                    <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                        <i class="fas fa-cogs text-xl"></i>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900">Automated Workflows</h3>
                    <p class="mt-2 text-gray-600 text-sm leading-relaxed">Save time with powerful automation and integration capabilities.</p>
                </div>
                
                <div class="group p-6 bg-white rounded-2xl shadow-sm hover:shadow-xl border border-gray-100 transition-all duration-300 hover:-translate-y-1">
                    <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900">Team Collaboration</h3>
                    <p class="mt-2 text-gray-600 text-sm leading-relaxed">Built-in collaboration tools for teams of any size.</p>
                </div>
                
                <div class="group p-6 bg-white rounded-2xl shadow-sm hover:shadow-xl border border-gray-100 transition-all duration-300 hover:-translate-y-1">
                    <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                        <i class="fas fa-mobile-alt text-xl"></i>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900">Mobile Optimized</h3>
                    <p class="mt-2 text-gray-600 text-sm leading-relaxed">Fully responsive design that works on any device.</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Testimonials Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900">What Our Customers Say</h2>
                <p class="mt-4 text-lg text-gray-600">Join thousands of satisfied customers who trust our platform.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold">SJ</div>
                        <div>
                            <p class="font-semibold text-gray-900">Sarah Johnson</p>
                            <p class="text-sm text-gray-500">CEO, TechStart</p>
                        </div>
                    </div>
                    <p class="text-gray-600">"This platform has revolutionized our workflow. We've seen a 300% increase in productivity since switching."</p>
                    <div class="mt-3 text-yellow-400">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold">MC</div>
                        <div>
                            <p class="font-semibold text-gray-900">Michael Chen</p>
                            <p class="text-sm text-gray-500">CTO, CloudNine</p>
                        </div>
                    </div>
                    <p class="text-gray-600">"The best SaaS platform we've ever used. The features are incredible and the support team is outstanding."</p>
                    <div class="mt-3 text-yellow-400">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold">ER</div>
                        <div>
                            <p class="font-semibold text-gray-900">Emily Rodriguez</p>
                            <p class="text-sm text-gray-500">Product Manager, InnovateCo</p>
                        </div>
                    </div>
                    <p class="text-gray-600">"I can't imagine running our business without this tool. It's simply essential for our daily operations."</p>
                    <div class="mt-3 text-yellow-400">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-indigo-600 to-purple-600">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white">Ready to Transform Your Business?</h2>
            <p class="mt-4 text-lg text-indigo-100">Join thousands of satisfied customers and start building your SaaS product today.</p>
            <div class="mt-8">
                @if(Route::has('register'))
                    <a href="{{ route('register') }}" class="inline-flex items-center px-8 py-3 text-base font-medium text-indigo-600 bg-white hover:bg-gray-50 rounded-lg shadow-lg transition">
                        Get Started for Free
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                @elseif(Route::has('website.register'))
                    <a href="{{ route('website.register') }}" class="inline-flex items-center px-8 py-3 text-base font-medium text-indigo-600 bg-white hover:bg-gray-50 rounded-lg shadow-lg transition">
                        Get Started for Free
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                @else
                    <a href="#" class="inline-flex items-center px-8 py-3 text-base font-medium text-indigo-600 bg-white hover:bg-gray-50 rounded-lg shadow-lg transition">
                        Get Started for Free
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                @endif
            </div>
        </div>
    </section>
@endsection