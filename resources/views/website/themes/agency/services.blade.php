{{-- resources/views/themes/agency/services.blade.php --}}
@extends('themes.agency.layouts.master')

@section('title', 'Services - Agency Hub')

@section('content')
    <!-- Hero Section -->
    <section class="pt-32 pb-20 bg-gradient-to-br from-primary-50 to-secondary-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-4xl mx-auto" data-aos="fade-up">
                <span class="inline-block px-4 py-1.5 text-sm font-semibold text-primary-600 bg-primary-100 rounded-full mb-4">
                    Our Services
                </span>
                <h1 class="text-5xl md:text-6xl font-extrabold text-gray-900">
                    What We Can
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-secondary-500">
                        Do For You
                    </span>
                </h1>
                <p class="mt-6 text-xl text-gray-600 max-w-3xl mx-auto">
                    Comprehensive digital solutions tailored to help your brand thrive in the modern marketplace.
                </p>
            </div>
        </div>
    </section>
    
    <!-- Services List -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Service 1 -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-20" data-aos="fade-up">
                <div>
                    <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-paint-brush text-white text-3xl"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Branding & Design</h2>
                    <p class="text-lg text-gray-600 leading-relaxed">
                        We create powerful brand identities that connect with your audience and tell your story. From logos to complete brand guidelines, we ensure consistency across all touchpoints.
                    </p>
                    <ul class="mt-6 space-y-3">
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-primary-500 mt-1"></i>
                            <span class="text-gray-600">Logo & Visual Identity Design</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-primary-500 mt-1"></i>
                            <span class="text-gray-600">Brand Strategy & Positioning</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-primary-500 mt-1"></i>
                            <span class="text-gray-600">UI/UX Design & Prototyping</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-primary-500 mt-1"></i>
                            <span class="text-gray-600">Print & Packaging Design</span>
                        </li>
                    </ul>
                </div>
                <div class="relative" data-aos="fade-left">
                    <img src="https://images.unsplash.com/photo-1561070791-2526d30994b5?w=600&h=400&fit=crop" 
                         alt="Branding Design" 
                         class="rounded-2xl shadow-xl">
                </div>
            </div>
            
            <!-- Service 2 -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-20" data-aos="fade-up" data-aos-delay="100">
                <div class="order-2 lg:order-1" data-aos="fade-right">
                    <img src="https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=600&h=400&fit=crop" 
                         alt="Web Development" 
                         class="rounded-2xl shadow-xl">
                </div>
                <div class="order-1 lg:order-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-secondary-500 to-pink-500 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-code text-white text-3xl"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Web Development</h2>
                    <p class="text-lg text-gray-600 leading-relaxed">
                        We build fast, responsive, and scalable websites and web applications using the latest technologies and best practices.
                    </p>
                    <ul class="mt-6 space-y-3">
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-primary-500 mt-1"></i>
                            <span class="text-gray-600">Custom Web Development</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-primary-500 mt-1"></i>
                            <span class="text-gray-600">E-commerce Solutions</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-primary-500 mt-1"></i>
                            <span class="text-gray-600">CMS Development (WordPress, Laravel)</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-primary-500 mt-1"></i>
                            <span class="text-gray-600">Web Application Development</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Service 3 -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center" data-aos="fade-up" data-aos-delay="200">
                <div>
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-chart-line text-white text-3xl"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Digital Marketing</h2>
                    <p class="text-lg text-gray-600 leading-relaxed">
                        Drive measurable results with data-driven marketing strategies that increase visibility, engagement, and conversions.
                    </p>
                    <ul class="mt-6 space-y-3">
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-primary-500 mt-1"></i>
                            <span class="text-gray-600">Search Engine Optimization (SEO)</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-primary-500 mt-1"></i>
                            <span class="text-gray-600">Content Marketing & Strategy</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-primary-500 mt-1"></i>
                            <span class="text-gray-600">Social Media Marketing</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-primary-500 mt-1"></i>
                            <span class="text-gray-600">Paid Advertising (PPC, Display)</span>
                        </li>
                    </ul>
                </div>
                <div data-aos="fade-left">
                    <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600&h=400&fit=crop" 
                         alt="Digital Marketing" 
                         class="rounded-2xl shadow-xl">
                </div>
            </div>
        </div>
    </section>
    
    <!-- Process Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
                <span class="inline-block px-4 py-1.5 text-sm font-semibold text-primary-600 bg-primary-100 rounded-full mb-4">
                    Our Process
                </span>
                <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900">
                    How We Work
                </h2>
                <p class="mt-4 text-lg text-gray-600">
                    A transparent and collaborative approach to deliver exceptional results.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-20 h-20 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-primary-600">01</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Discovery</h3>
                    <p class="mt-2 text-sm text-gray-600">Understanding your goals, audience, and challenges.</p>
                </div>
                
                <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-20 h-20 bg-secondary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-secondary-600">02</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Strategy</h3>
                    <p class="mt-2 text-sm text-gray-600">Developing a comprehensive plan and approach.</p>
                </div>
                
                <div class="text-center" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-purple-600">03</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Execution</h3>
                    <p class="mt-2 text-sm text-gray-600">Implementing solutions with precision and care.</p>
                </div>
                
                <div class="text-center" data-aos="fade-up" data-aos-delay="400">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-green-600">04</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Delivery</h3>
                    <p class="mt-2 text-sm text-gray-600">Launching and optimizing for long-term success.</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA -->
    <section class="py-20 bg-gradient-to-br from-primary-600 to-secondary-600">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl md:text-5xl font-extrabold text-white" data-aos="fade-up">
                Ready to Work Together?
            </h2>
            <p class="mt-4 text-xl text-white/90" data-aos="fade-up" data-aos-delay="100">
                Let's discuss how we can help your brand grow.
            </p>
            <a href="{{ route('agency.contact') }}" 
               class="mt-8 inline-flex items-center px-8 py-4 text-lg font-semibold text-primary-600 bg-white rounded-full hover:shadow-2xl hover:scale-105 transition-all duration-300"
               data-aos="fade-up" data-aos-delay="200">
                Start Your Project
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </section>
@endsection