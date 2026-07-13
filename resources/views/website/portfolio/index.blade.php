{{-- resources/views/website/portfolio/index.blade.php --}}
@extends('layouts.website')

@section('title', 'Portfolio - SaaS Platform')

@section('content')
    <!-- Portfolio Header -->
    <x-website.hero-section 
        title="Our Portfolio"
        subtitle="Explore our work and see how we've helped businesses succeed."
        cta_text="Start Your Project"
        cta_link="{{ route('website.contact') }}"
        secondary_text="View Services"
        secondary_link="{{ route('website.services') }}"
    />
    
    <!-- Portfolio Categories -->
    <section class="py-8 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap gap-2">
                <a href="#" class="px-4 py-2 text-sm font-medium text-indigo-600 bg-indigo-100 rounded-full">All Projects</a>
                <a href="#" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-full transition">Web Apps</a>
                <a href="#" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-full transition">Mobile Apps</a>
                <a href="#" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-full transition">Enterprise</a>
                <a href="#" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-full transition">Startups</a>
            </div>
        </div>
    </section>
    
    <!-- Portfolio Grid -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($projects ?? [] as $project)
                    <x-website.portfolio-card :project="$project" />
                @empty
                    <div class="col-span-3 text-center py-12">
                        <p class="text-gray-500">No portfolio items found.</p>
                    </div>
                @endforelse
            </div>
            
            <!-- Pagination -->
            @if(isset($projects) && method_exists($projects, 'links'))
                <div class="mt-12">
                    {{ $projects->links() }}
                </div>
            @endif
        </div>
    </section>
    
    <!-- CTA Section -->
    <x-website.cta-section 
        title="Ready to Build Something Amazing?"
        subtitle="Let's work together to bring your vision to life."
        cta_text="Contact Us"
        cta_link="{{ route('website.contact') }}"
    />
@endsection