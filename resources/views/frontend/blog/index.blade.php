{{-- resources/views/frontend/blog/index.blade.php --}}
@php
    $pageTitle = 'Blog - AgencyPro';
    $pageDescription = 'Insights, tips, and stories from our team. Stay updated with the latest trends in digital design, development, and strategy.';
@endphp

@extends('layouts.app')

@section('title', $pageTitle)
@section('description', $pageDescription)

@section('schema')
    <x-seo.schema type="WebSite" :data="[
        'name' => 'AgencyPro Blog',
        'url' => url('/blog'),
        'description' => $pageDescription,
    ]" />
@endsection

@section('content')
    {{-- Page Header --}}
    <x-layout.page-header 
        title="Blog & Insights"
        subtitle="Latest Articles"
        :breadcrumb="[['label' => 'Blog']]"
    >
        <x-slot:actions>
            <form action="{{ route('blog.search') }}" method="GET" class="flex gap-2">
                <input type="text" name="q" placeholder="Search articles..." 
                       class="px-4 py-2.5 rounded-xl border-2 border-gray-200 focus:border-[#6c5ce7] focus:ring-[#6c5ce7]/20 transition-all outline-none text-sm">
                <button type="submit" class="px-4 py-2.5 bg-[#6c5ce7] text-white rounded-xl hover:bg-[#5a4bd1] transition-colors text-sm font-medium">
                    Search
                </button>
            </form>
        </x-slot:actions>
    </x-layout.page-header>

    {{-- Featured Post --}}
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-5xl mx-auto">
                <x-cards.blog-card 
                    image="{{ asset('images/blog/featured.jpg') }}"
                    title="10 Web Design Trends That Will Dominate 2024"
                    excerpt="Discover the latest design trends that are shaping the future of the web. From AI-powered interfaces to immersive experiences, here's what you need to know."
                    author="Jane Smith"
                    date="Dec 18, 2024"
                    category="Design"
                    readingTime="10 min read"
                    :tags="['Web Design', 'Trends', 'UI/UX']"
                    link="{{ route('blog.show', 'web-design-trends-2024') }}"
                    featured="true"
                />
            </div>
        </div>
    </section>

    {{-- Categories --}}
    <section class="py-8 bg-gray-50 border-y border-gray-100">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap justify-center gap-3">
                <a href="{{ route('blog.index') }}" class="px-4 py-2 bg-[#6c5ce7] text-white rounded-full text-sm font-medium transition-colors hover:bg-[#5a4bd1]">
                    All
                </a>
                <a href="{{ route('blog.category', 'design') }}" class="px-4 py-2 bg-white text-[#4a4a4a] rounded-full text-sm font-medium hover:bg-[#6c5ce7] hover:text-white transition-colors">
                    Design
                </a>
                <a href="{{ route('blog.category', 'development') }}" class="px-4 py-2 bg-white text-[#4a4a4a] rounded-full text-sm font-medium hover:bg-[#6c5ce7] hover:text-white transition-colors">
                    Development
                </a>
                <a href="{{ route('blog.category', 'marketing') }}" class="px-4 py-2 bg-white text-[#4a4a4a] rounded-full text-sm font-medium hover:bg-[#6c5ce7] hover:text-white transition-colors">
                    Marketing
                </a>
                <a href="{{ route('blog.category', 'strategy') }}" class="px-4 py-2 bg-white text-[#4a4a4a] rounded-full text-sm font-medium hover:bg-[#6c5ce7] hover:text-white transition-colors">
                    Strategy
                </a>
                <a href="{{ route('blog.category', 'technology') }}" class="px-4 py-2 bg-white text-[#4a4a4a] rounded-full text-sm font-medium hover:bg-[#6c5ce7] hover:text-white transition-colors">
                    Technology
                </a>
            </div>
        </div>
    </section>

    {{-- Blog Grid --}}
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach(range(1, 9) as $i)
                    <x-cards.blog-card 
                        image="{{ asset('images/blog/post' . $i . '.jpg') }}"
                        title="{{ ['Building Scalable Web Applications', 'The Future of AI in Design', 'SEO Strategies That Work', 'Mobile-First Design Principles', 'How to Build a Brand Identity', 'The Rise of Headless CMS', 'Performance Optimization Tips', 'User Research Best Practices', 'The Psychology of Color in Design'][$i-1] }}"
                        excerpt="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua."
                        author="{{ ['John Doe', 'Jane Smith', 'Mike Johnson', 'Sarah Chen', 'Alex Rivera', 'Maria Santos', 'James Park', 'Emily Watson', 'David Kim'][$i-1] }}"
                        date="{{ date('M d, Y', strtotime('-' . $i . ' days')) }}"
                        category="{{ ['Design', 'Development', 'Marketing', 'Design', 'Strategy', 'Technology', 'Development', 'Design', 'Marketing'][$i-1] }}"
                        readingTime="{{ rand(4, 12) }} min read"
                        :tags="[['Design', 'Trends'], ['Development', 'Scalability'], ['SEO', 'Marketing'], ['Design', 'Mobile'], ['Branding', 'Strategy'], ['CMS', 'Technology'], ['Performance', 'Optimization'], ['Research', 'UX'], ['Design', 'Psychology']][$i-1]"
                        link="{{ route('blog.show', 'post-' . $i) }}"
                        :featured="false"
                    />
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-12">
                <x-ui.pagination :current="1" :total="100" :per-page="9" url="{{ route('blog.index') }}" />
            </div>
        </div>
    </section>

    {{-- Newsletter --}}
    <x-sections.newsletter 
        title="Subscribe to Our Newsletter"
        subtitle="Stay Updated"
        description="Get the latest insights and updates delivered straight to your inbox."
        form-action="#"
        button-text="Subscribe"
    />

    {{-- CTA --}}
    <x-sections.cta 
        title="Want to Contribute to Our Blog?"
        subtitle="Write for Us"
        description="We're always looking for talented writers and industry experts to share their insights."
        primary-text="Contact Us"
        primary-link="#contact"
        secondary-text="View All Posts"
        secondary-link="{{ route('blog.index') }}"
        variant="default"
    />
@endsection