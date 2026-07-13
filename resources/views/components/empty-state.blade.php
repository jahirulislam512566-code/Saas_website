@props([
    'icon' => 'fa-inbox',
    'title' => 'No items found',
    'description' => 'There are no items to display at the moment.',
    'action' => null,
    'actionLabel' => 'Create New',
])

<div class="text-center py-12">
    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
        <i class="fas {{ $icon }} text-2xl text-gray-400"></i>
    </div>
    <h3 class="text-lg font-medium text-gray-900 mb-1">{{ $title }}</h3>
    <p class="text-sm text-gray-500">{{ $description }}</p>
    
    @if($action)
        <div class="mt-4">
            {{ $action }}
        </div>
    @endif
</div>