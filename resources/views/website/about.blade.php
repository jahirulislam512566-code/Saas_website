{{-- resources/views/website/about.blade.php --}}
@extends('layouts.website')

@section('title', 'About Us - SaaS Platform')

@section('content')
    <!-- About Hero -->
    <x-website.hero-section 
        title="About Our Company"
        subtitle="We're on a mission to empower businesses with cutting-edge SaaS solutions that drive growth and innovation."
        cta_text="Join Our Team"
        cta_link="#"
        secondary_text="Contact Us"
        secondary_link="{{ route('website.contact') }}"
    />
    
    <!-- Mission & Vision -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Our Mission</h2>
                    <p class="mt-4 text-lg text-gray-600 leading-relaxed">
                        To democratize access to enterprise-grade SaaS tools and empower businesses of all sizes to compete in the digital economy.
                    </p>
                    <div class="mt-6 space-y-4">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-indigo-600 mt-1"></i>
                            <p class="text-gray-600">Empower 1 million businesses by 2028</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-indigo-600 mt-1"></i>
                            <p class="text-gray-600">Maintain 99.99% uptime for all customers</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-indigo-600 mt-1"></i>
                            <p class="text-gray-600">Achieve 95% customer satisfaction rate</p>
                        </div>
                    </div>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Our Vision</h2>
                    <p class="mt-4 text-lg text-gray-600 leading-relaxed">
                        To become the world's most trusted SaaS platform, enabling businesses to build, scale, and innovate without limits.
                    </p>
                    <div class="mt-6 bg-indigo-50 rounded-xl p-6">
                        <p class="text-indigo-700 font-medium">
                            "Technology is best when it brings people together and enables them to achieve more."
                        </p>
                        <p class="mt-2 text-sm text-indigo-600">— Our Founding Team</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Team Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-website.section-header 
                title="Meet Our Team"
                subtitle="Passionate experts dedicated to your success."
            />
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <x-website.team-card 
                    name="John Doe"
                    role="CEO & Co-founder"
                    avatar="JD"
                    bio="Former VP of Engineering at TechCorp with 15+ years of experience."
                />
                <x-website.team-card 
                    name="Jane Smith"
                    role="CTO & Co-founder"
                    avatar="JS"
                    bio="PhD in Computer Science and expert in cloud architecture and AI."
                />
                <x-website.team-card 
                    name="Mike Johnson"
                    role="Head of Product"
                    avatar="MJ"
                    bio="Product leader with a passion for user-centered design and innovation."
                />
                <x-website.team-card 
                    name="Sarah Williams"
                    role="Head of Marketing"
                    avatar="SW"
                    bio="Marketing strategist with experience at leading SaaS companies."
                />
            </div>
        </div>
    </section>
    
    <!-- Values Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-website.section-header 
                title="Our Core Values"
                subtitle="These principles guide everything we do."
            />
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <x-website.value-card 
                    icon="fa-star"
                    title="Excellence"
                    description="We strive for excellence in everything we do, from code to customer service."
                />
                <x-website.value-card 
                    icon="fa-handshake"
                    title="Integrity"
                    description="We believe in transparency, honesty, and building trust with our customers."
                />
                <x-website.value-card 
                    icon="fa-lightbulb"
                    title="Innovation"
                    description="We push the boundaries of what's possible to solve real-world problems."
                />
            </div>
        </div>
    </section>
@endsection