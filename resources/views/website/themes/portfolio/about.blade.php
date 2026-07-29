@extends('layouts.website')

@section('title', 'About Me')

@section('content')
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <h1 class="text-4xl font-bold mb-6">About Me</h1>
                <p class="text-lg text-gray-600 mb-6">I'm a passionate designer and developer with over 8 years of experience creating digital solutions that make a difference.</p>
                <p class="text-lg text-gray-600 mb-6">My approach combines creativity with technical expertise to deliver projects that not only look amazing but also perform flawlessly.</p>
                
                <div class="space-y-4">
                    @foreach($skills ?? [
                        ['name' => 'UI/UX Design', 'percentage' => 90],
                        ['name' => 'Frontend Development', 'percentage' => 85],
                        ['name' => 'Backend Development', 'percentage' => 75],
                        ['name' => 'Project Management', 'percentage' => 80]
                    ] as $skill)
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="font-medium">{{ $skill['name'] }}</span>
                                <span class="text-sm text-gray-500">{{ $skill['percentage'] }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-primary-600 h-2.5 rounded-full" style="width: {{ $skill['percentage'] }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div>
                <img src="/assets/images/profile.jpg" alt="Profile" class="rounded-lg shadow-lg w-full">
            </div>
        </div>
    </div>
</section>

<!-- Experience -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center mb-12">Experience</h2>
        <div class="space-y-8">
            @foreach($experience ?? [
                ['title' => 'Senior Developer', 'company' => 'Tech Corp', 'period' => '2020 - Present', 'description' => 'Leading development team for enterprise applications'],
                ['title' => 'Full Stack Developer', 'company' => 'Digital Agency', 'period' => '2018 - 2020', 'description' => 'Developed web applications for various clients'],
                ['title' => 'Junior Developer', 'company' => 'StartUp Inc', 'period' => '2016 - 2018', 'description' => 'Built and maintained web applications']
            ] as $exp)
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-xl font-bold">{{ $exp['title'] }}</h3>
                            <p class="text-primary-600">{{ $exp['company'] }}</p>
                        </div>
                        <span class="text-sm text-gray-500">{{ $exp['period'] }}</span>
                    </div>
                    <p class="text-gray-600 mt-2">{{ $exp['description'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection