<!-- admin/partials/empty-state.blade.php -->
<div class="flex flex-col items-center justify-center py-16 text-center">
    <!-- Icon -->
    <div class="w-20 h-20 mb-4 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
        <i class="fas fa-{{ $icon ?? 'inbox' }} text-3xl text-gray-400 dark:text-gray-600"></i>
    </div>
    
    <!-- Title -->
    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
        {{ $title ?? 'No items found' }}
    </h3>
    
    <!-- Description -->
    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 max-w-sm">
        {{ $description ?? 'Get started by creating your first item.' }}
    </p>
    
    <!-- Action -->
    @if (isset($action))
        <a href="{{ $action['url'] }}" 
           class="mt-6 inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all hover:shadow-md">
            <i class="fas fa-{{ $action['icon'] ?? 'plus' }} text-xs"></i>
            {{ $action['label'] ?? 'Create New' }}
        </a>
    @endif
    
    <!-- Additional actions -->
    @if (isset($actions))
        <div class="mt-4 flex items-center gap-3">
            @foreach ($actions as $action)
                <a href="{{ $action['url'] }}" 
                   class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                    {{ $action['label'] }}
                </a>
            @endforeach
        </div>
    @endif
</div>