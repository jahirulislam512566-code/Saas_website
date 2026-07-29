{{-- resources/views/frontend/portfolio/index.blade.php --}}
@php
    $pageTitle = 'Our Portfolio - AgencyPro';
    $pageDescription = 'Explore our portfolio of successful projects. See how we\'ve helped businesses achieve their digital goals through innovative solutions.';
@endphp

@extends('layouts.app')

@section('title', $pageTitle)
@section('description', $pageDescription)

@section('schema')
    <x-seo.schema type="WebSite" :data="[
        'name' => 'AgencyPro Portfolio',
        'url' => url('/portfolio'),
        'description' => $pageDescription,
    ]" />
@endsection

@section('content')
    {{-- Page Header --}}
    <x-layout.page-header 
        title="Our Portfolio"
        subtitle="Featured Work"
        :breadcrumb="[['label' => 'Portfolio']]"
    >
        <x-slot:actions>
            <a href="#contact" class="inline-flex items-center px-6 py-3 bg-[#6c5ce7] text-white rounded-xl hover:bg-[#5a4bd1] transition-colors shadow-lg shadow-[#6c5ce7]/30">
                Start Your Project
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </x-slot:actions>
    </x-layout.page-header>

    {{-- Portfolio Grid --}}
    <x-sections.portfolio 
        title="Selected Projects"
        subtitle="Our Best Work"
        description="Each project represents our commitment to quality, innovation, and client satisfaction."
        columns="3"
        filter="true"
        :projects="[
            [
                'image' => asset('images/portfolio/project1.jpg'),
                'title' => 'E-Commerce Platform for Retail Brand',
                'category' => 'Web Development',
                'tags' => ['Laravel', 'Vue.js', 'Stripe', 'AWS'],
                'link' => route('portfolio.show', 1),
                'lightbox' => true,
            ],
            [
                'image' => asset('images/portfolio/project2.jpg'),
                'title' => 'Mobile Banking App',
                'category' => 'Mobile Development',
                'tags' => ['React Native', 'Node.js', 'MongoDB', 'Firebase'],
                'link' => route('portfolio.show', 2),
                'lightbox' => true,
            ],
            [
                'image' => asset('images/portfolio/project3.jpg'),
                'title' => 'Brand Identity & Website Redesign',
                'category' => 'UI/UX Design',
                'tags' => ['Branding', 'Figma', 'Design System', 'Tailwind'],
                'link' => route('portfolio.show', 3),
                'lightbox' => true,
            ],
            [
                'image' => asset('images/portfolio/project4.jpg'),
                'title' => 'Healthcare Management Dashboard',
                'category' => 'Web Development',
                'tags' => ['React', 'Django', 'PostgreSQL', 'Docker'],
                'link' => route('portfolio.show', 4),
                'lightbox' => true,
            ],
            [
                'image' => asset('images/portfolio/project5.jpg'),
                'title' => 'Social Media Campaign Strategy',
                'category' => 'Digital Strategy',
                'tags' => ['Social Media', 'Content Strategy', 'Analytics', 'Growth'],
                'link' => route('portfolio.show', 5),
                'lightbox' => true,
            ],
            [
                'image' => asset('images/portfolio/project6.jpg'),
                'title' => 'E-Learning Platform',
                'category' => 'Mobile Development',
                'tags' => ['Flutter', 'Firebase', 'Video Streaming', 'Payment'],
                'link' => route('portfolio.show', 6),
                'lightbox' => true,
            ],
            [
                'image' => asset('images/portfolio/project7.jpg'),
                'title' => 'SaaS Product Dashboard',
                'category' => 'Web Development',
                'tags' => ['Next.js', 'GraphQL', 'Prisma', 'Vercel'],
                'link' => route('portfolio.show', 7),
                'lightbox' => true,
            ],
            [
                'image' => asset('images/portfolio/project8.jpg'),
                'title' => 'Restaurant POS System',
                'category' => 'UI/UX Design',
                'tags' => ['UX Research', 'Prototyping', 'Usability Testing'],
                'link' => route('portfolio.show', 8),
                'lightbox' => true,
            ],
            [
                'image' => asset('images/portfolio/project9.jpg'),
                'title' => 'Real Estate Platform',
                'category' => 'Web Development',
                'tags' => ['Ruby on Rails', 'React', 'Mapbox', 'Redis'],
                'link' => route('portfolio.show', 9),
                'lightbox' => true,
            ],
        ]"
    />

    {{-- Testimonials --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-sm font-semibold text-[#6c5ce7] uppercase tracking-wider">Client Success Stories</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-2 text-[#0a0a0a]">What Our Clients Say</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <x-cards.testimonial-card 
                    quote="AgencyPro transformed our digital presence completely. Our conversion rates increased by 40% within three months."
                    author="Sarah Johnson"
                    role="CEO, TechCorp"
                    rating="5"
                />
                <x-cards.testimonial-card 
                    quote="The team's attention to detail and commitment to quality is outstanding. They delivered exactly what we needed."
                    author="Michael Chen"
                    role="Director, InnovateTech"
                    rating="5"
                    featured="true"
                />
                <x-cards.testimonial-card 
                    quote="Working with AgencyPro has been a game-changer for our business. Their strategic approach and execution are unmatched."
                    author="Emily Rodriguez"
                    role="VP Marketing, GrowthLabs"
                    rating="5"
                />
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <x-sections.cta 
        title="Ready to Be Featured in Our Portfolio?"
        subtitle="Let's Create Something Amazing"
        description="Contact us today and let's start building your success story."
        primary-text="Start Your Project"
        primary-link="#contact"
        secondary-text="Explore Our Services"
        secondary-link="{{ route('services.index') }}"
        variant="default"
    />
@endsection