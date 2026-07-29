{{-- resources/views/website/careers/show.blade.php --}}
@extends('layouts.website')

@section('title', $job['title'] . ' - Careers')
@section('meta_description', $job['description'] ?? 'Join our team and help us build the future of SaaS.')

@section('content')
    {{-- Job Header --}}
    <section class="py-16 bg-gradient-to-r from-primary-600 to-secondary-600">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto text-center text-white">
                @if(isset($job['featured']) && $job['featured'])
                    <span class="inline-block px-3 py-1 text-xs font-semibold bg-yellow-400 text-gray-900 rounded-full mb-4">
                        Featured Position
                    </span>
                @endif
                <h1 class="text-4xl md:text-5xl font-bold">{{ $job['title'] }}</h1>
                <div class="mt-4 flex flex-wrap justify-center gap-4 text-sm">
                    <span class="inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z"/>
                            <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z"/>
                        </svg>
                        {{ $job['department'] }}
                    </span>
                    <span class="inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                        {{ $job['location'] }}
                    </span>
                    <span class="inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                        {{ $job['type'] }}
                    </span>
                    <span class="inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                        Posted {{ $job['posted'] }}
                    </span>
                </div>
                <div class="mt-6 flex flex-wrap justify-center gap-4">
                    <a href="#apply" class="inline-flex items-center px-8 py-3 border-2 border-white text-base font-medium rounded-md text-white hover:bg-white hover:text-primary-600 transition-colors duration-200">
                        Apply Now
                        <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                    <a href="{{ route('careers.index') }}" class="inline-flex items-center px-8 py-3 border border-white text-base font-medium rounded-md text-white hover:bg-white hover:text-primary-600 transition-colors duration-200">
                        View All Jobs
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Job Details --}}
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {{-- Main Content --}}
                    <div class="lg:col-span-2 space-y-8">
                        {{-- About the Role --}}
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">About the Role</h2>
                            <div class="mt-4 prose prose-gray max-w-none">
                                <p>{{ $job['description'] ?? 'We are looking for a talented individual to join our team and help us build the future of SaaS.' }}</p>
                                <p>You will work closely with cross-functional teams to deliver high-quality solutions that make a real impact.</p>
                            </div>
                        </div>

                        {{-- Responsibilities --}}
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Key Responsibilities</h2>
                            <ul class="mt-4 space-y-3">
                                @foreach($job['responsibilities'] ?? ['Design and implement new features', 'Collaborate with product and design teams', 'Write clean, maintainable code', 'Participate in code reviews', 'Debug and resolve technical issues'] as $responsibility)
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-700">{{ $responsibility }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        {{-- Requirements --}}
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Requirements</h2>
                            <ul class="mt-4 space-y-3">
                                @foreach($job['requirements'] ?? ['5+ years of relevant experience', 'Strong understanding of SaaS products', 'Excellent communication skills', 'Bachelor\'s degree in related field', 'Experience with agile methodologies'] as $requirement)
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-blue-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-700">{{ $requirement }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        {{-- Benefits --}}
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Benefits</h2>
                            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($job['benefits'] ?? ['Competitive salary', 'Health insurance', 'Remote work', 'Flexible hours', 'Learning stipend', 'Equity options'] as $benefit)
                                    <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                                        <svg class="w-5 h-5 text-primary-600 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-sm text-gray-700">{{ $benefit }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Sidebar --}}
                    <div class="lg:col-span-1">
                        {{-- Job Summary --}}
                        <div class="bg-gray-50 rounded-xl p-6 sticky top-24">
                            <h3 class="text-lg font-semibold text-gray-900">Job Summary</h3>
                            <dl class="mt-4 space-y-3 divide-y divide-gray-200">
                                <div class="pt-3 first:pt-0">
                                    <dt class="text-sm font-medium text-gray-500">Department</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $job['department'] ?? 'Engineering' }}</dd>
                                </div>
                                <div class="pt-3">
                                    <dt class="text-sm font-medium text-gray-500">Location</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $job['location'] ?? 'Remote' }}</dd>
                                </div>
                                <div class="pt-3">
                                    <dt class="text-sm font-medium text-gray-500">Employment Type</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $job['type'] ?? 'Full-time' }}</dd>
                                </div>
                                <div class="pt-3">
                                    <dt class="text-sm font-medium text-gray-500">Salary Range</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $job['salary'] ?? 'Competitive' }}</dd>
                                </div>
                                <div class="pt-3">
                                    <dt class="text-sm font-medium text-gray-500">Posted</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $job['posted'] ?? 'Recently' }}</dd>
                                </div>
                            </dl>

                            {{-- Apply Button --}}
                            <div id="apply" class="mt-6">
                                <a href="#" class="block w-full px-4 py-3 bg-primary-600 hover:bg-primary-700 text-white text-center font-semibold rounded-lg transition-colors duration-200">
                                    Apply Now
                                </a>
                                <p class="mt-2 text-xs text-gray-500 text-center">We\'ll respond within 5 business days</p>
                            </div>

                            {{-- Share --}}
                            <div class="mt-6">
                                <p class="text-sm font-medium text-gray-700">Share this job</p>
                                <div class="mt-2 flex space-x-2">
                                    <a href="#" class="p-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">
                                        <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                        </svg>
                                    </a>
                                    <a href="#" class="p-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">
                                        <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 01-1.93.07 4.28 4.28 0 004 2.98 8.521 8.521 0 01-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                                        </svg>
                                    </a>
                                    <a href="#" class="p-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">
                                        <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Related Jobs --}}
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <x-website.section-header 
                title="Related Positions"
                subtitle="Explore other opportunities at our company"
                alignment="center"
            />
            
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4">
                @php
                    $relatedJobs = [
                        ['title' => 'Frontend Developer', 'department' => 'Engineering', 'location' => 'Remote'],
                        ['title' => 'Product Designer', 'department' => 'Design', 'location' => 'Remote'],
                        ['title' => 'DevOps Engineer', 'department' => 'Engineering', 'location' => 'Remote']
                    ];
                @endphp
                @foreach($relatedJobs as $related)
                    <a href="#" class="p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 flex items-center justify-between group">
                        <div>
                            <h4 class="font-medium text-gray-900 group-hover:text-primary-600 transition-colors">{{ $related['title'] }}</h4>
                            <div class="flex items-center gap-3 text-sm text-gray-500">
                                <span>{{ $related['department'] }}</span>
                                <span>•</span>
                                <span>{{ $related['location'] }}</span>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-primary-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <x-website.cta-section 
        title="Don\'t See Your Perfect Role?"
        subtitle="We're always looking for talented people. Send us your resume anyway."
        cta_text="Send Your Resume"
        cta_link="#"
        secondary_text="View All Jobs"
        secondary_link="{{ route('careers.index') }}"
        variant="gradient"
    />
@endsection