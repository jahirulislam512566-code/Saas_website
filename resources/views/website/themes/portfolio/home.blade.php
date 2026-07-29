@extends('layouts.website')

@section('title', 'Creative Portfolio - ' . config('app.name'))

@push('styles')
<style>
    .hero-gradient {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
    }
    .project-card:hover .project-overlay {
        opacity: 1;
    }
    .project-card:hover .project-image {
        transform: scale(1.1);
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="hero-gradient min-h-screen flex items-center text-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-10 left-10 w-32 h-32 bg-white rounded-full animate-pulse"></div>
        <div class="absolute bottom-10 right-10 w-40 h-40 bg-primary-400 rounded-full animate-pulse delay-100"></div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-3xl">
            <div class="inline-block bg-primary-500/20 backdrop-blur-sm text-primary-300 px-4 py-1 rounded-full text-sm font-semibold mb-6">
                🚀 Available for Projects
            </div>
            <h1 class="text-5xl md:text-7xl font-bold mb-6 leading-tight">
                Creating Digital <span class="text-primary-400">Excellence</span>
            </h1>
            <p class="text-xl md:text-2xl mb-8 text-gray-300 font-light">Transforming ideas into beautiful, functional digital experiences</p>
            <div class="flex flex-col sm:flex-row gap-4">
                <x-button variant="primary" size="lg" href="{{ route('themes.portfolio.portfolio') }}" icon="grid">View My Work</x-button>
                <x-button variant="outline" size="lg" href="{{ route('themes.portfolio.contact') }}" icon="mail">Get in Touch</x-button>
            </div>
        </div>
    </div>
</section>

<!-- Featured Projects -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-primary-600 font-semibold text-sm uppercase tracking-wider">Portfolio</span>
            <h2 class="text-3xl md:text-4xl font-bold mt-2 text-gray-900">Featured Projects</h2>
            <div class="w-24 h-1 bg-primary-600 mx-auto mt-4"></div>
            <p class="text-gray-600 mt-4 max-w-2xl mx-auto">Each project is crafted with attention to detail and a passion for excellence</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($featuredProjects ?? $defaultFeaturedProjects as $project)
                <div class="project-card group bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300">
                    <div class="relative overflow-hidden h-64">
                        <img src="{{ $project['image'] ?? '/assets/images/placeholder.jpg' }}" alt="{{ $project['title'] ?? 'Project' }}" class="project-image w-full h-full object-cover transition duration-500">
                        <div class="project-overlay absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 transition duration-300 flex items-end p-6">
                            <div class="text-white">
                                <span class="text-sm text-primary-300">{{ $project['category'] ?? 'Category' }}</span>
                                <h3 class="text-xl font-bold">{{ $project['title'] ?? 'Project Title' }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-600 text-sm mb-4">{{ $project['description'] ?? 'Project description' }}</p>
                        <x-button variant="primary" size="sm" href="{{ $project['link'] ?? '#' }}" icon="arrow-right">View Project</x-button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Skills Section -->
<section class="py-20 bg-primary-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <span class="text-primary-300 font-semibold text-sm uppercase tracking-wider">Skills & Expertise</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-2 mb-6">What I Bring to the Table</h2>
                <p class="text-gray-300 mb-8">With years of experience across multiple disciplines, I deliver comprehensive digital solutions.</p>
                <div class="grid grid-cols-2 gap-4">
                    @foreach($skillStats ?? $defaultSkillStats as $stat)
                        <div class="bg-white/10 backdrop-blur-sm p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-primary-400">{{ $stat['number'] ?? '0' }}</div>
                            <div class="text-sm text-gray-300">{{ $stat['label'] ?? 'Stat' }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="space-y-4">
                @foreach($skills ?? $defaultSkills as $skill)
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="font-medium">{{ $skill['name'] ?? 'Skill' }}</span>
                            <span class="text-sm text-gray-400">{{ $skill['percentage'] ?? 0 }}%</span>
                        </div>
                        <div class="w-full bg-white/20 rounded-full h-2.5">
                            <div class="bg-primary-500 h-2.5 rounded-full transition-all duration-1000" style="width: {{ $skill['percentage'] ?? 0 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endsection

@php
$defaultFeaturedProjects = [
    ['title' => 'E-commerce Platform', 'category' => 'Web Development', 'description' => 'Full-featured online store with payment integration', 'image' => '/assets/images/project1.jpg', 'link' => '#'],
    ['title' => 'Brand Identity', 'category' => 'Design', 'description' => 'Complete brand redesign and identity system', 'image' => '/assets/images/project2.jpg', 'link' => '#'],
    ['title' => 'Mobile App', 'category' => 'Mobile Development', 'description' => 'Cross-platform mobile application', 'image' => '/assets/images/project3.jpg', 'link' => '#']
];

$defaultSkills = [
    ['name' => 'UI/UX Design', 'percentage' => 90],
    ['name' => 'Frontend Development', 'percentage' => 85],
    ['name' => 'Backend Development', 'percentage' => 75],
    ['name' => 'Project Management', 'percentage' => 80]
];

$defaultSkillStats = [
    ['number' => '8+', 'label' => 'Years Experience'],
    ['number' => '50+', 'label' => 'Projects Completed'],
    ['number' => '30+', 'label' => 'Happy Clients'],
    ['number' => '15+', 'label' => 'Awards Won']
];
@endphp