{{-- resources/views/frontend/pages/careers.blade.php --}}
@php
    $pageTitle = 'Careers - AgencyPro';
    $pageDescription = 'Join our team at AgencyPro. We\'re hiring talented individuals passionate about digital innovation.';
@endphp

@extends('layouts.app')

@section('title', $pageTitle)
@section('description', $pageDescription)

@section('schema')
    <x-seo.schema type="Organization" :data="[
        'name' => 'AgencyPro',
        'url' => url('/careers'),
        'description' => $pageDescription,
        'social' => [
            'https://twitter.com/agencypro',
            'https://linkedin.com/company/agencypro',
        ]
    ]" />
@endsection

@section('content')
    {{-- Page Header --}}
    <x-layout.page-header 
        title="Join Our Team"
        subtitle="Careers"
        :breadcrumb="[['label' => 'Careers']]"
    >
        <x-slot:actions>
            <a href="#openings" class="inline-flex items-center px-6 py-3 bg-[#6c5ce7] text-white rounded-xl hover:bg-[#5a4bd1] transition-colors shadow-lg shadow-[#6c5ce7]/30">
                View Open Positions
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 13l-7 7-7-7m14-8l-7 7-7-7"/>
                </svg>
            </a>
        </x-slot:actions>
    </x-layout.page-header>

    {{-- Why Join Us --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-sm font-semibold text-[#6c5ce7] uppercase tracking-wider">Why Join Us</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-2 text-[#0a0a0a]">Work With Passion, Innovate With Purpose</h2>
                <p class="mt-4 text-lg text-[#4a4a4a]">We're building a team of creative thinkers, problem solvers, and technology enthusiasts who are passionate about making a difference.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 max-w-6xl mx-auto">
                <div class="text-center p-6 rounded-2xl hover:bg-gray-50 transition-colors group">
                    <div class="text-4xl mb-3">🌍</div>
                    <h4 class="font-bold text-[#0a0a0a] group-hover:text-[#6c5ce7] transition-colors">Global Impact</h4>
                    <p class="text-sm text-[#4a4a4a] mt-1">Work on projects that make a real difference</p>
                </div>
                <div class="text-center p-6 rounded-2xl hover:bg-gray-50 transition-colors group">
                    <div class="text-4xl mb-3">💡</div>
                    <h4 class="font-bold text-[#0a0a0a] group-hover:text-[#6c5ce7] transition-colors">Innovation Culture</h4>
                    <p class="text-sm text-[#4a4a4a] mt-1">Embrace creativity and cutting-edge technology</p>
                </div>
                <div class="text-center p-6 rounded-2xl hover:bg-gray-50 transition-colors group">
                    <div class="text-4xl mb-3">🤝</div>
                    <h4 class="font-bold text-[#0a0a0a] group-hover:text-[#6c5ce7] transition-colors">Collaborative Team</h4>
                    <p class="text-sm text-[#4a4a4a] mt-1">Work with talented, supportive colleagues</p>
                </div>
                <div class="text-center p-6 rounded-2xl hover:bg-gray-50 transition-colors group">
                    <div class="text-4xl mb-3">📈</div>
                    <h4 class="font-bold text-[#0a0a0a] group-hover:text-[#6c5ce7] transition-colors">Growth Opportunities</h4>
                    <p class="text-sm text-[#4a4a4a] mt-1">Continuous learning and career advancement</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Open Positions --}}
    <section class="py-20 bg-gray-50" id="openings">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-sm font-semibold text-[#6c5ce7] uppercase tracking-wider">Open Positions</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-2 text-[#0a0a0a]">Join Our Growing Team</h2>
                <p class="mt-4 text-lg text-[#4a4a4a]">We're looking for talented individuals to help us build the future of digital experiences.</p>
            </div>

            <div class="max-w-4xl mx-auto space-y-4">
                {{-- Job 1 --}}
                <div class="bg-white rounded-2xl p-6 shadow-md hover:shadow-lg transition-all duration-300">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <h3 class="text-xl font-bold text-[#0a0a0a]">Senior Full-Stack Developer</h3>
                            <div class="flex flex-wrap gap-3 mt-2">
                                <span class="text-sm text-[#6c5ce7] font-medium">📍 Remote</span>
                                <span class="text-sm text-[#4a4a4a]">•</span>
                                <span class="text-sm text-[#4a4a4a]">💼 Full-Time</span>
                                <span class="text-sm text-[#4a4a4a]">•</span>
                                <span class="text-sm text-emerald-600 font-medium">💰 $120k - $150k</span>
                            </div>
                            <p class="text-[#4a4a4a] mt-3">We're looking for an experienced Full-Stack Developer to join our engineering team. You'll be building scalable web applications using modern technologies.</p>
                            <div class="flex flex-wrap gap-2 mt-3">
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">React</span>
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">Laravel</span>
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">AWS</span>
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">TypeScript</span>
                            </div>
                        </div>
                        <a href="#apply" class="flex-shrink-0 px-6 py-2.5 bg-[#6c5ce7] text-white rounded-xl hover:bg-[#5a4bd1] transition-colors text-sm font-medium">
                            Apply Now →
                        </a>
                    </div>
                </div>

                {{-- Job 2 --}}
                <div class="bg-white rounded-2xl p-6 shadow-md hover:shadow-lg transition-all duration-300">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <h3 class="text-xl font-bold text-[#0a0a0a]">UI/UX Designer</h3>
                            <div class="flex flex-wrap gap-3 mt-2">
                                <span class="text-sm text-[#6c5ce7] font-medium">📍 Remote</span>
                                <span class="text-sm text-[#4a4a4a]">•</span>
                                <span class="text-sm text-[#4a4a4a]">💼 Full-Time</span>
                                <span class="text-sm text-[#4a4a4a]">•</span>
                                <span class="text-sm text-emerald-600 font-medium">💰 $90k - $120k</span>
                            </div>
                            <p class="text-[#4a4a4a] mt-3">We're seeking a talented UI/UX Designer to create beautiful, user-centered designs for our clients. You'll work closely with our development team to bring designs to life.</p>
                            <div class="flex flex-wrap gap-2 mt-3">
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">Figma</span>
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">Design Systems</span>
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">Prototyping</span>
                            </div>
                        </div>
                        <a href="#apply" class="flex-shrink-0 px-6 py-2.5 bg-[#6c5ce7] text-white rounded-xl hover:bg-[#5a4bd1] transition-colors text-sm font-medium">
                            Apply Now →
                        </a>
                    </div>
                </div>

                {{-- Job 3 --}}
                <div class="bg-white rounded-2xl p-6 shadow-md hover:shadow-lg transition-all duration-300">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <h3 class="text-xl font-bold text-[#0a0a0a]">Digital Marketing Specialist</h3>
                            <div class="flex flex-wrap gap-3 mt-2">
                                <span class="text-sm text-[#6c5ce7] font-medium">📍 Remote</span>
                                <span class="text-sm text-[#4a4a4a]">•</span>
                                <span class="text-sm text-[#4a4a4a]">💼 Full-Time</span>
                                <span class="text-sm text-[#4a4a4a]">•</span>
                                <span class="text-sm text-emerald-600 font-medium">💰 $80k - $110k</span>
                            </div>
                            <p class="text-[#4a4a4a] mt-3">We're looking for a Digital Marketing Specialist to drive growth through SEO, content marketing, and social media strategies. You'll help our clients achieve their marketing goals.</p>
                            <div class="flex flex-wrap gap-2 mt-3">
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">SEO</span>
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">Content Strategy</span>
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">Analytics</span>
                            </div>
                        </div>
                        <a href="#apply" class="flex-shrink-0 px-6 py-2.5 bg-[#6c5ce7] text-white rounded-xl hover:bg-[#5a4bd1] transition-colors text-sm font-medium">
                            Apply Now →
                        </a>
                    </div>
                </div>
            </div>

            <div class="text-center mt-12">
                <p class="text-[#4a4a4a]">Don't see a position that fits? We're always looking for talented individuals.</p>
                <a href="{{ route('contact.index') }}" class="inline-flex items-center gap-2 mt-2 text-[#6c5ce7] font-medium hover:gap-3 transition-all">
                    Send us your resume →
                </a>
            </div>
        </div>
    </section>

    {{-- Benefits --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-sm font-semibold text-[#6c5ce7] uppercase tracking-wider">Benefits & Perks</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-2 text-[#0a0a0a]">What We Offer</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-5xl mx-auto">
                <div class="flex gap-4 p-6 rounded-2xl hover:bg-gray-50 transition-colors">
                    <div class="text-3xl flex-shrink-0">🏖️</div>
                    <div>
                        <h4 class="font-bold text-[#0a0a0a]">Flexible Work</h4>
                        <p class="text-sm text-[#4a4a4a]">Remote-first culture with flexible hours</p>
                    </div>
                </div>
                <div class="flex gap-4 p-6 rounded-2xl hover:bg-gray-50 transition-colors">
                    <div class="text-3xl flex-shrink-0">📚</div>
                    <div>
                        <h4 class="font-bold text-[#0a0a0a]">Learning Budget</h4>
                        <p class="text-sm text-[#4a4a4a]">Annual budget for courses and conferences</p>
                    </div>
                </div>
                <div class="flex gap-4 p-6 rounded-2xl hover:bg-gray-50 transition-colors">
                    <div class="text-3xl flex-shrink-0">🩺</div>
                    <div>
                        <h4 class="font-bold text-[#0a0a0a]">Health Benefits</h4>
                        <p class="text-sm text-[#4a4a4a]">Comprehensive health and wellness coverage</p>
                    </div>
                </div>
                <div class="flex gap-4 p-6 rounded-2xl hover:bg-gray-50 transition-colors">
                    <div class="text-3xl flex-shrink-0">🚀</div>
                    <div>
                        <h4 class="font-bold text-[#0a0a0a]">Growth Opportunities</h4>
                        <p class="text-sm text-[#4a4a4a]">Clear career progression paths</p>
                    </div>
                </div>
                <div class="flex gap-4 p-6 rounded-2xl hover:bg-gray-50 transition-colors">
                    <div class="text-3xl flex-shrink-0">🌴</div>
                    <div>
                        <h4 class="font-bold text-[#0a0a0a]">Paid Time Off</h4>
                        <p class="text-sm text-[#4a4a4a]">Generous vacation and holiday policy</p>
                    </div>
                </div>
                <div class="flex gap-4 p-6 rounded-2xl hover:bg-gray-50 transition-colors">
                    <div class="text-3xl flex-shrink-0">🎉</div>
                    <div>
                        <h4 class="font-bold text-[#0a0a0a]">Team Events</h4>
                        <p class="text-sm text-[#4a4a4a]">Regular team building and social events</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <x-sections.cta 
        title="Ready to Join Our Team?"
        subtitle="Start Your Career Journey"
        description="Apply today and become part of our mission to create exceptional digital experiences."
        primary-text="View Open Positions"
        primary-link="#openings"
        secondary-text="Contact Us"
        secondary-link="{{ route('contact.index') }}"
        variant="default"
    />
@endsection