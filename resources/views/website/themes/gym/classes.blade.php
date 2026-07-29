@extends('layouts.website')

@section('title', 'Class Schedule')

@section('content')
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold text-center mb-4">Class Schedule</h1>
        <p class="text-center text-gray-600 mb-12">Find the perfect class for your fitness journey</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($schedule ?? [
                ['day' => 'Monday', 'classes' => [
                    ['name' => 'Yoga Flow', 'time' => '9:00 AM', 'instructor' => 'Sarah', 'spots' => 8],
                    ['name' => 'HIIT', 'time' => '6:00 PM', 'instructor' => 'Mike', 'spots' => 12]
                ]],
                ['day' => 'Tuesday', 'classes' => [
                    ['name' => 'Spinning', 'time' => '7:00 AM', 'instructor' => 'Lisa', 'spots' => 10],
                    ['name' => 'Pilates', 'time' => '12:00 PM', 'instructor' => 'Sarah', 'spots' => 6]
                ]]
            ] as $day)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-2xl font-bold mb-4 text-primary-600">{{ $day['day'] }}</h2>
                    <div class="space-y-4">
                        @foreach($day['classes'] as $class)
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-semibold">{{ $class['name'] }}</p>
                                    <p class="text-sm text-gray-500">{{ $class['instructor'] }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-medium">{{ $class['time'] }}</p>
                                    <p class="text-xs text-gray-500">{{ $class['spots'] }} spots</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection