@extends('layouts.website')

@section('title', 'My Projects')

@section('content')
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold text-center mb-4">My Projects</h1>
        <p class="text-center text-gray-600 mb-12">A selection of my recent work</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($projects ?? [
                ['title' => 'Project Alpha', 'category' => 'Web Development', 'image' => '/assets/images/project1.jpg', 'link' => '/projects/1'],
                ['title' => 'Project Beta', 'category' => 'Design', 'image' => '/assets/images/project2.jpg', 'link' => '/projects/2'],
                ['title' => 'Project Gamma', 'category' => 'Mobile Development', 'image' => '/assets/images/project3.jpg', 'link' => '/projects/3']
            ] as $project)
                <div class="group relative overflow-hidden rounded-lg shadow-sm hover:shadow-lg transition">
                    <img src="{{ $project['image'] }}" alt="{{ $project['title'] }}" class="w-full h-64 object-cover group-hover:scale-105 transition duration-300">
                    <div class="absolute inset-0 bg-black bg-opacity-70 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                        <div class="text-center text-white p-6">
                            <h3 class="text-xl font-bold">{{ $project['title'] }}</h3>
                            <p class="text-sm text-gray-300 mb-4">{{ $project['category'] }}</p>
                            <x-button variant="primary" size="sm" href="{{ $project['link'] }}">View Project</x-button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection