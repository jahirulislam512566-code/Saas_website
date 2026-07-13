{{-- resources/views/components/website/team-card.blade.php --}}
@props([
    'name' => 'John Doe',
    'role' => 'Team Member',
    'avatar' => 'JD',
    'bio' => 'Team member bio goes here.',
    'social' => []
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-2xl shadow-sm hover:shadow-xl transition border border-gray-100 overflow-hidden group']) }}>
    <div class="relative h-48 bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center">
        <div class="w-24 h-24 rounded-full bg-white shadow-lg flex items-center justify-center text-3xl font-bold text-indigo-600">
            {{ $avatar }}
        </div>
    </div>
    <div class="p-6 text-center">
        <h3 class="text-lg font-bold text-gray-900">{{ $name }}</h3>
        <p class="text-sm text-indigo-600 font-medium">{{ $role }}</p>
        <p class="mt-2 text-sm text-gray-600">{{ $bio }}</p>
        
        @if(!empty($social))
            <div class="mt-4 flex justify-center gap-2">
                @foreach($social as $platform => $url)
                    <a href="{{ $url }}" target="_blank" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-indigo-100 hover:text-indigo-600 transition">
                        <i class="fab fa-{{ $platform }}"></i>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>