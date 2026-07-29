{{-- resources/views/website/careers/index.blade.php --}}
@extends('layouts.website')

@section('title', 'Careers - Join Our Team')
@section('meta_description', 'Join our growing team and help us build the future of SaaS. Explore open positions and career opportunities at our company.')

@section('content')
    {{-- Hero Section --}}
    <x-website.hero-section 
        title="Join Our Team"
        subtitle="Help us build the future of SaaS and make a difference in how businesses operate."
        cta_text="View Open Positions"
        cta_link="#open-positions"
        secondary_text="Learn About Us"
        secondary_link="#culture"
        gradient="from-primary-600 to-secondary-600"
    />

    {{-- Why Join Us Section --}}
    <section class="py-20 bg-white" aria-label="Why join us">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <x-website.section-header 
                title="Why Work With Us"
                subtitle="We're building a culture where innovation thrives and people grow."
                alignment="center"
            />
            
            <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
                <x-website.benefit-card 
                    icon="fa-heart"
                    title="Purpose-Driven Work"
                    description="Make a real impact on how businesses operate and grow."
                    color="red"
                />
                <x-website.benefit-card 
                    icon="fa-users"
                    title="Great People"
                    description="Work alongside talented, passionate individuals who love what they do."
                    color="blue"
                />
                <x-website.benefit-card 
                    icon="fa-chart-line"
                    title="Career Growth"
                    description="Learn, grow, and advance your career with ongoing development opportunities."
                    color="green"
                />
                <x-website.benefit-card 
                    icon="fa-home"
                    title="Remote First"
                    description="Work from anywhere in the world with our flexible remote-first culture."
                    color="purple"
                />
                <x-website.benefit-card 
                    icon="fa-gift"
                    title="Great Benefits"
                    description="Competitive salary, equity, health benefits, and more."
                    color="orange"
                />
                <x-website.benefit-card 
                    icon="fa-lightbulb"
                    title="Innovation Culture"
                    description="Experiment, take risks, and bring your boldest ideas to life."
                    color="teal"
                />
            </div>
        </div>
    </section>

    {{-- Culture Section --}}
    <section id="culture" class="py-20 bg-gray-50" aria-label="Our culture">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <x-website.section-header 
                        title="Our Culture"
                        subtitle="We believe in transparency, collaboration, and continuous learning."
                        alignment="left"
                    />
                    
                    <div class="mt-6 space-y-4">
                        <x-website.culture-value 
                            icon="fa-handshake"
                            title="Trust & Transparency"
                            description="Open communication and honest feedback are at our core."
                        />
                        <x-website.culture-value 
                            icon="fa-users-cog"
                            title="Collaboration"
                            description="We work together across teams and time zones."
                        />
                        <x-website.culture-value 
                            icon="fa-graduation-cap"
                            title="Continuous Learning"
                            description="Weekly workshops, learning budgets, and mentorship programs."
                        />
                        <x-website.culture-value 
                            icon="fa-balance-scale"
                            title="Work-Life Balance"
                            description="Flexible hours and unlimited PTO to recharge."
                        />
                    </div>
                </div>
                <div class="relative">
                    <img 
                        src="{{ asset('images/careers/culture.jpg') }}" 
                        alt="Our team collaborating" 
                        class="rounded-2xl shadow-xl w-full"
                        loading="lazy"
                    >
                    <div class="absolute -bottom-6 -right-6 bg-white rounded-lg shadow-lg p-4">
                        <div class="flex items-center space-x-3">
                            <div class="flex -space-x-2">
                                <img src="{{ asset('images/avatars/team1.jpg') }}" alt="Team member" class="w-10 h-10 rounded-full border-2 border-white">
                                <img src="{{ asset('images/avatars/team2.jpg') }}" alt="Team member" class="w-10 h-10 rounded-full border-2 border-white">
                                <img src="{{ asset('images/avatars/team3.jpg') }}" alt="Team member" class="w-10 h-10 rounded-full border-2 border-white">
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">100+ Team Members</p>
                                <p class="text-xs text-gray-500">Growing every month</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Open Positions Section --}}
    <section id="open-positions" class="py-20 bg-white" aria-label="Open positions">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <x-website.section-header 
                title="Open Positions"
                subtitle="Find your next career opportunity and join our team."
                alignment="center"
            />
            
            {{-- Filters --}}
            <div class="mt-8 flex flex-wrap gap-4 justify-center">
                <x-website.filter-tag active="true">All</x-website.filter-tag>
                <x-website.filter-tag>Engineering</x-website.filter-tag>
                <x-website.filter-tag>Product</x-website.filter-tag>
                <x-website.filter-tag>Design</x-website.filter-tag>
                <x-website.filter-tag>Marketing</x-website.filter-tag>
                <x-website.filter-tag>Sales</x-website.filter-tag>
                <x-website.filter-tag>Operations</x-website.filter-tag>
            </div>
            
            {{-- Job Listings --}}
            <div class="mt-10 space-y-4">
                @php
                    $jobs = [
                        [
                            'title' => 'Senior Full Stack Developer',
                            'department' => 'Engineering',
                            'location' => 'Remote',
                            'type' => 'Full-time',
                            'posted' => '2 days ago',
                            'salary' => '$120k - $180k',
                            'featured' => true
                        ],
                        [
                            'title' => 'Product Manager',
                            'department' => 'Product',
                            'location' => 'Remote',
                            'type' => 'Full-time',
                            'posted' => '3 days ago',
                            'salary' => '$110k - $160k',
                            'featured' => false
                        ],
                        [
                            'title' => 'UX/UI Designer',
                            'department' => 'Design',
                            'location' => 'Remote',
                            'type' => 'Full-time',
                            'posted' => '1 week ago',
                            'salary' => '$90k - $140k',
                            'featured' => false
                        ],
                        [
                            'title' => 'DevOps Engineer',
                            'department' => 'Engineering',
                            'location' => 'Remote',
                            'type' => 'Full-time',
                            'posted' => '1 week ago',
                            'salary' => '$130k - $190k',
                            'featured' => true
                        ],
                        [
                            'title' => 'Marketing Lead',
                            'department' => 'Marketing',
                            'location' => 'Remote',
                            'type' => 'Full-time',
                            'posted' => '2 weeks ago',
                            'salary' => '$100k - $150k',
                            'featured' => false
                        ]
                    ];
                @endphp
                
                @foreach($jobs as $job)
                    <x-website.job-card 
                        :title="$job['title']"
                        :department="$job['department']"
                        :location="$job['location']"
                        :type="$job['type']"
                        :posted="$job['posted']"
                        :salary="$job['salary']"
                        :featured="$job['featured']"
                        :link="route('careers.show', ['slug' => Str::slug($job['title'])])"
                    />
                @endforeach
            </div>
            
            {{-- View All Button --}}
            <div class="mt-10 text-center">
                <a href="#" class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                    View All Positions
                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    {{-- Perks & Benefits Section --}}
    <section class="py-20 bg-gray-50" aria-label="Perks and benefits">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <x-website.section-header 
                title="Perks & Benefits"
                subtitle="We take care of our people so they can focus on doing their best work."
                alignment="center"
            />
            
            <div class="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <x-website.perk-card 
                    icon="fa-dollar-sign"
                    title="Competitive Salary"
                    description="Industry-leading compensation packages"
                    icon_color="green"
                />
                <x-website.perk-card 
                    icon="fa-stethoscope"
                    title="Health Benefits"
                    description="Medical, dental, and vision coverage"
                    icon_color="blue"
                />
                <x-website.perk-card 
                    icon="fa-clock"
                    title="Flexible Hours"
                    description="Work when you're most productive"
                    icon_color="purple"
                />
                <x-website.perk-card 
                    icon="fa-plane"
                    title="Remote Work"
                    description="Work from anywhere in the world"
                    icon_color="orange"
                />
                <x-website.perk-card 
                    icon="fa-book"
                    title="Learning Budget"
                    description="$2,000 annual learning stipend"
                    icon_color="red"
                />
                <x-website.perk-card 
                    icon="fa-gamepad"
                    title="Team Events"
                    description="Virtual and in-person team building"
                    icon_color="teal"
                />
                <x-website.perk-card 
                    icon="fa-gem"
                    title="Equity Options"
                    description="Share in our success with stock options"
                    icon_color="indigo"
                />
                <x-website.perk-card 
                    icon="fa-coffee"
                    title="Home Office Setup"
                    description="$1,000 home office stipend"
                    icon_color="yellow"
                />
            </div>
        </div>
    </section>

    {{-- Team Section --}}
    <section class="py-20 bg-white" aria-label="Meet the team">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <x-website.section-header 
                title="Meet the Team"
                subtitle="The brilliant minds behind our success"
                alignment="center"
            />
            
            <div class="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <x-website.team-card 
                    name="Sarah Johnson"
                    role="CEO & Co-Founder"
                    image="{{ asset('images/team/sarah.jpg') }}"
                    bio="Ex-Google, 15+ years in SaaS"
                    social_linkedin="#"
                    social_twitter="#"
                />
                <x-website.team-card 
                    name="Mike Chen"
                    role="CTO & Co-Founder"
                    image="{{ asset('images/team/mike.jpg') }}"
                    bio="Full-stack architect, open source contributor"
                    social_linkedin="#"
                    social_twitter="#"
                />
                <x-website.team-card 
                    name="Emily Rodriguez"
                    role="Head of Product"
                    image="{{ asset('images/team/emily.jpg') }}"
                    bio="Product leader, Stanford MBA"
                    social_linkedin="#"
                    social_twitter="#"
                />
                <x-website.team-card 
                    name="David Kim"
                    role="Engineering Manager"
                    image="{{ asset('images/team/david.jpg') }}"
                    bio="Distributed systems expert"
                    social_linkedin="#"
                    social_twitter="#"
                />
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <x-website.cta-section 
        title="Ready to Join Us?"
        subtitle="We're excited to meet you. Apply today and start your journey with us."
        cta_text="View Open Positions"
        cta_link="#open-positions"
        secondary_text="Learn More"
        secondary_link="#"
        variant="gradient"
    />
@endsection

@push('styles')
<style>
    .job-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .job-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
    }
</style>
@endpush