{{-- resources/views/themes/agency/contact.blade.php --}}
@extends('themes.agency.layouts.master')

@section('title', 'Contact Us - Agency Hub')

@section('content')
    <!-- Hero Section -->
    <section class="pt-32 pb-20 bg-gradient-to-br from-primary-50 to-secondary-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-4xl mx-auto" data-aos="fade-up">
                <span class="inline-block px-4 py-1.5 text-sm font-semibold text-primary-600 bg-primary-100 rounded-full mb-4">
                    Get In Touch
                </span>
                <h1 class="text-5xl md:text-6xl font-extrabold text-gray-900">
                    Let's Start a
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-secondary-500">
                        Project Together
                    </span>
                </h1>
                <p class="mt-6 text-xl text-gray-600 max-w-3xl mx-auto">
                    Have a project in mind? We'd love to hear about it. Reach out and let's create something amazing.
                </p>
            </div>
        </div>
    </section>
    
    <!-- Contact Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Contact Info -->
                <div class="lg:col-span-1 space-y-8" data-aos="fade-right">
                    <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-6">Contact Information</h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-map-marker-alt text-primary-600 text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Address</p>
                                    <p class="text-sm text-gray-600">123 Agency Street, New York, NY 10001</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-phone text-primary-600 text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Phone</p>
                                    <p class="text-sm text-gray-600">+1 (555) 123-4567</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-envelope text-primary-600 text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Email</p>
                                    <p class="text-sm text-gray-600">hello@agencyhub.com</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-clock text-primary-600 text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Working Hours</p>
                                    <p class="text-sm text-gray-600">Mon-Fri: 9:00 AM - 6:00 PM</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Follow Us</h3>
                        <div class="flex gap-4">
                            <a href="#" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-600 hover:text-primary-600 hover:shadow-md transition-all duration-300">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-600 hover:text-primary-600 hover:shadow-md transition-all duration-300">
                                <i class="fab fa-linkedin"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-600 hover:text-primary-600 hover:shadow-md transition-all duration-300">
                                <i class="fab fa-github"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-600 hover:text-primary-600 hover:shadow-md transition-all duration-300">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-600 hover:text-primary-600 hover:shadow-md transition-all duration-300">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Contact Form -->
                <div class="lg:col-span-2" data-aos="fade-left" data-aos-delay="100">
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Send Us a Message</h3>
                        <p class="text-gray-600 mb-8">Fill in the form below and we'll get back to you within 24 hours.</p>
                        
                        @if(session('success'))
                            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        <form method="POST" action="{{ route('agency.contact.store') }}" class="space-y-6">
                            @csrf
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                                    <input type="text" name="name" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition @error('name') border-red-500 @enderror" 
                                           value="{{ old('name') }}" 
                                           placeholder="John Doe" 
                                           required>
                                    @error('name')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                    <input type="email" name="email" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition @error('email') border-red-500 @enderror" 
                                           value="{{ old('email') }}" 
                                           placeholder="john@example.com" 
                                           required>
                                    @error('email')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Subject *</label>
                                <input type="text" name="subject" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition @error('subject') border-red-500 @enderror" 
                                       value="{{ old('subject') }}" 
                                       placeholder="Project Inquiry" 
                                       required>
                                @error('subject')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Message *</label>
                                <textarea name="message" rows="5" 
                                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition @error('message') border-red-500 @enderror" 
                                          placeholder="Tell us about your project..." 
                                          required>{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Budget Range (Optional)</label>
                                <select name="budget" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                                    <option value="">Select a budget range</option>
                                    <option value="under-5k">Under $5,000</option>
                                    <option value="5k-10k">$5,000 - $10,000</option>
                                    <option value="10k-25k">$10,000 - $25,000</option>
                                    <option value="25k-50k">$25,000 - $50,000</option>
                                    <option value="50k-100k">$50,000 - $100,000</option>
                                    <option value="over-100k">Over $100,000</option>
                                </select>
                            </div>
                            
                            <button type="submit" 
                                    class="w-full px-8 py-4 text-lg font-semibold text-white bg-gradient-to-r from-primary-600 to-secondary-500 rounded-xl hover:shadow-2xl hover:scale-[1.02] transition-all duration-300">
                                Send Message
                                <i class="fas fa-paper-plane ml-2"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Map Section -->
    <section class="h-96 bg-gray-200 relative">
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="text-center">
                <i class="fas fa-map-marked-alt text-5xl text-gray-400 mb-4"></i>
                <p class="text-gray-600">Interactive Map Here</p>
            </div>
        </div>
        <!-- You can add Google Maps embed here -->
    </section>
    
    <!-- FAQ Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
                <span class="inline-block px-4 py-1.5 text-sm font-semibold text-primary-600 bg-primary-100 rounded-full mb-4">
                    FAQ
                </span>
                <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900">
                    Frequently Asked Questions
                </h2>
            </div>
            
            <div class="space-y-4">
                <div x-data="{ open: false }" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up" data-aos-delay="100">
                    <button @click="open = !open" class="w-full px-6 py-4 flex items-center justify-between text-left hover:bg-gray-50 transition">
                        <span class="text-lg font-semibold text-gray-900">How long does a typical project take?</span>
                        <svg class="w-5 h-5 text-gray-500 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="px-6 pb-4">
                        <p class="text-gray-600 leading-relaxed">Project timelines vary depending on scope and complexity. A typical website project takes 4-8 weeks, while larger applications can take 3-6 months. We'll provide a detailed timeline during the discovery phase.</p>
                    </div>
                </div>
                
                <div x-data="{ open: false }" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up" data-aos-delay="200">
                    <button @click="open = !open" class="w-full px-6 py-4 flex items-center justify-between text-left hover:bg-gray-50 transition">
                        <span class="text-lg font-semibold text-gray-900">What is your pricing model?</span>
                        <svg class="w-5 h-5 text-gray-500 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="px-6 pb-4">
                        <p class="text-gray-600 leading-relaxed">We offer flexible pricing models including fixed-price projects, hourly rates, and retainer packages. We'll work with you to find the best option for your budget and project requirements.</p>
                    </div>
                </div>
                
                <div x-data="{ open: false }" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up" data-aos-delay="300">
                    <button @click="open = !open" class="w-full px-6 py-4 flex items-center justify-between text-left hover:bg-gray-50 transition">
                        <span class="text-lg font-semibold text-gray-900">Do you provide ongoing support?</span>
                        <svg class="w-5 h-5 text-gray-500 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="px-6 pb-4">
                        <p class="text-gray-600 leading-relaxed">Yes! We offer comprehensive support and maintenance packages to ensure your project continues to perform optimally after launch. We also provide training and documentation for your team.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection