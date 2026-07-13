{{-- resources/views/components/website/quick-action.blade.php --}}
@props([
    'icon' => 'fa-user-plus',
    'title' => 'Action Title',
    'description' => 'Action description goes here.',
    'link' => '#'
])

<a href="{{ $link }}" class="block bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition group">
    <div class="flex items-start gap-4">
        <div class="w-12 h-12 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
            <i class="fas {{ $icon }} text-xl"></i>
        </div>
        <div>
            <h3 class="font-semibold text-gray-900">{{ $title }}</h3>
            <p class="text-sm text-gray-600 mt-1">{{ $description }}</p>
        </div>
    </div>
</a>