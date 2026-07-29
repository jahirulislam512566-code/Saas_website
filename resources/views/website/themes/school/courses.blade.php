@extends('layouts.website')

@section('title', 'Courses')

@section('content')
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold text-center mb-4">Our Programs</h1>
        <p class="text-center text-gray-600 mb-12">Comprehensive programs designed to inspire and educate</p>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            @foreach($courses ?? [
                ['name' => 'Science Program', 'description' => 'Comprehensive science education with hands-on experiments', 'features' => ['Biology', 'Chemistry', 'Physics'], 'link' => '/courses/science'],
                ['name' => 'Mathematics Program', 'description' => 'Advanced mathematics curriculum for all levels', 'features' => ['Algebra', 'Geometry', 'Calculus'], 'link' => '/courses/math'],
                ['name' => 'Arts Program', 'description' => 'Creative arts education including visual and performing arts', 'features' => ['Drawing', 'Music', 'Drama'], 'link' => '/courses/arts'],
                ['name' => 'Technology Program', 'description' => 'Modern technology and computer science education', 'features' => ['Programming', 'Robotics', 'Digital Design'], 'link' => '/courses/tech']
            ] as $course)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-lg transition">
                    <div class="h-48 bg-primary-600 flex items-center justify-center text-white">
                        <h2 class="text-2xl font-bold">{{ $course['name'] }}</h2>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-600 mb-4">{{ $course['description'] }}</p>
                        <div class="flex flex-wrap gap-2 mb-4">
                            @foreach($course['features'] as $feature)
                                <x-badge variant="primary" size="sm">{{ $feature }}</x-badge>
                            @endforeach
                        </div>
                        <x-button variant="primary" size="sm" href="{{ $course['link'] }}">Learn More</x-button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection