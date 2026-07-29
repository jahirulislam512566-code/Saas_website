{{-- resources/views/themes/agency/about.blade.php --}}
@extends('themes.agency.layouts.master')

@section('title', 'About Us - Agency Hub')

@section('content')
    <!-- Hero Section -->
    <section class="pt-32 pb-20 bg-gradient-to-br from-primary-50 to-secondary-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-4xl mx-auto" data-aos="fade-up">
                <span class="inline-block px-4 py-1.5 text-sm font-semibold text-primary-600 bg-primary-100 rounded-full mb-4">
                    About Us
                </span>
                <h1 class="text-5xl md:text-6xl font-extrabold text-gray-900">
                    We're a Team of
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-secondary-500">
                        Creative Thinkers
                    </span>
                </h1>
                <p class="mt-6 text-xl text-gray-600 max-w-3xl mx-auto">
                    We combine creativity, technology, and strategy to deliver exceptional digital experiences for brands worldwide.
                </p>
            </div>
        </div>
    </section>
    
    <!-- Mission & Vision -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div data-aos="fade-right">
                    <div class="w-16 h-16 bg-primary-100 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-bullseye text-3xl text-primary-600"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Our Mission</h2>
                    <p class="text-lg text-gray-600 leading-relaxed">
                        To empower brands with innovative digital solutions that drive growth, engagement, and lasting impact in an ever-evolving digital landscape.
                    </p>
                    <ul class="mt-6 space-y-3">
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-primary-500 mt-1"></i>
                            <span class="text-gray-600">Deliver exceptional quality in every project</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-primary-500 mt-1"></i>
                            <span class="text-gray-600">Foster innovation and creative thinking</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-primary-500 mt-1"></i>
                            <span class="text-gray-600">Build lasting relationships with our clients</span>
                        </li>
                    </ul>
                </div>
                
                <div data-aos="fade-left" data-aos-delay="100">
                    <div class="w-16 h-16 bg-secondary-100 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-eye text-3xl text-secondary-500"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Our Vision</h2>
                    <p class="text-lg text-gray-600 leading-relaxed">
                        To become the world's most trusted creative agency, recognized for transforming brands through innovative design and technology.
                    </p>
                    <div class="mt-6 bg-gradient-to-br from-primary-50 to-secondary-50 rounded-2xl p-6">
                        <p class="text-lg font-medium text-gray-800 italic">
                            "Technology is best when it brings people together and enables them to achieve more."
                        </p>
                        <p class="mt-2 text-sm text-gray-600">— Our Founding Team</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Team Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
                <span class="inline-block px-4 py-1.5 text-sm font-semibold text-primary-600 bg-primary-100 rounded-full mb-4">
                    Our Team
                </span>
                <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900">
                    Meet the Experts
                </h2>
                <p class="mt-4 text-lg text-gray-600">
                    Passionate professionals dedicated to bringing your vision to life.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Team Member 1 -->
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 group" data-aos="fade-up" data-aos-delay="100">
                    <div class="h-64 bg-gradient-to-br from-primary-500 to-secondary-500 flex items-center justify-center">
                        <span class="text-6xl font-bold text-white opacity-50">JD</span>
                    </div>
                    <div class="p-6 text-center">
                        <h3 class="text-xl font-bold text-gray-900">John Doe</h3>
                        <p class="text-sm text-primary-600 font-medium">CEO & Founder</p>
                        <p class="mt-2 text-sm text-gray-600">15+ years of experience in digital strategy and brand development.</p>
                        <div class="mt-4 flex justify-center gap-3">
                            <a href="#" class="text-gray-400 hover:text-primary-500 transition"><i class="fab fa-linkedin"></i></a>
                            <a href="#" class="text-gray-400 hover:text-primary-500 transition"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                </div>
                
                <!-- Team Member 2 -->
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 group" data-aos="fade-up" data-aos-delay="200">
                    <div class="h-64 bg-gradient-to-br from-secondary-500 to-pink-500 flex items-center justify-center">
                        <span class="text-6xl font-bold text-white opacity-50">JS</span>
                    </div>
                    <div class="p-6 text-center">
                        <h3 class="text-xl font-bold text-gray-900">Jane Smith</h3>
                        <p class="text-sm text-primary-600 font-medium">Creative Director</p>
                        <p class="mt-2 text-sm text-gray-600">Award-winning designer with a passion for user-centered design.</p>
                        <div class="mt-4 flex justify-center gap-3">
                            <a href="#" class="text-gray-400 hover:text-primary-500 transition"><i class="fab fa-linkedin"></i></a>
                            <a href="#" class="text-gray-400 hover:text-primary-500 transition"><i class="fab fa-dribbble"></i></a>
                        </div>
                    </div>
                </div>
                
                <!-- Team Member 3 -->
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 group" data-aos="fade-up" data-aos-delay="300">
                    <div class="h-64 bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                        <span class="text-6xl font-bold text-white opacity-50">MJ</span>
                    </div>
                    <div class="p-6 text-center">
                        <h3 class="text-xl font-bold text-gray-900">Mike Johnson</h3>
                        <p class="text-sm text-primary-600 font-medium">Lead Developer</p>
                        <p class="mt-2 text-sm text-gray-600">Full-stack developer specializing in modern web technologies.</p>
                        <div class="mt-4 flex justify-center gap-3">
                            <a href="#" class="text-gray-400 hover:text-primary-500 transition"><i class="fab fa-linkedin"></i></a>
                            <a href="#" class="text-gray-400 hover:text-primary-500 transition"><i class="fab fa-github"></i></a>
                        </div>
                    </div>
                </div>
                
                <!-- Team Member 4 -->
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 group" data-aos="fade-up" data-aos-delay="400">
                    <div class="h-64 bg-gradient-to-br from-green-500 to-teal-500 flex items-center justify-center">
                        <span class="text-6xl font-bold text-white opacity-50">SW</span>
                    </div>
                    <div class="p-6 text-center">
                        <h3 class="text-xl font-bold text-gray-900">Sarah Williams</h3>
                        <p class="text-sm text-primary-600 font-medium">Marketing Strategist</p>
                        <p class="mt-2 text-sm text-gray-600">Data-driven marketer with expertise in growth and content strategy.</p>
                        <div class="mt-4 flex justify-center gap-3">
                            <a href="#" class="text-gray-400 hover:text-primary-500 transition"><i class="fab fa-linkedin"></i></a>
                            <a href="#" class="text-gray-400 hover:text-primary-500 transition"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Values Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
                <span class="inline-block px-4 py-1.5 text-sm font-semibold text-primary-600 bg-primary-100 rounded-full mb-4">
                    Core Values
                </span>
                <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900">
                    What Drives Us
                </h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-8 rounded-2xl hover:bg-primary-50 transition-all duration-300 group" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-20 h-20 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-primary-200 transition-colors">
                        <i class="fas fa-lightbulb text-3xl text-primary-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Innovation</h3>
                    <p class="mt-2 text-gray-600">We push boundaries and embrace new technologies to deliver cutting-edge solutions.</p>
                </div>
                
                <div class="text-center p-8 rounded-2xl hover:bg-secondary-50 transition-all duration-300 group" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-20 h-20 bg-secondary-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-secondary-200 transition-colors">
                        <i class="fas fa-handshake text-3xl text-secondary-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Integrity</h3>
                    <p class="mt-2 text-gray-600">We believe in transparency, honesty, and building trust with our clients.</p>
                </div>
                
                <div class="text-center p-8 rounded-2xl hover:bg-purple-50 transition-all duration-300 group" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-purple-200 transition-colors">
                        <i class="fas fa-star text-3xl text-purple-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Excellence</h3>
                    <p class="mt-2 text-gray-600">We strive for excellence in everything we do, from design to delivery.</p>
                </div>
            </div>
        </div>
    </section>
@endsection