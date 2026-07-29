{{-- resources/views/components/website/culture-value.blade.php --}}
@props(['icon', 'title', 'description'])

<div class="flex items-start space-x-4 p-4 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-200">
    <div class="flex-shrink-0 w-12 h-12 bg-primary-50 rounded-lg flex items-center justify-center">
        <i class="fas {{ $icon }} text-primary-600 text-xl"></i>
    </div>
    <div>
        <h4 class="font-semibold text-gray-900">{{ $title }}</h4>
        <p class="text-sm text-gray-600">{{ $description }}</p>
    </div>
</div>