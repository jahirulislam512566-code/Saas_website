<!-- admin/partials/confirm-modal.blade.php -->
<div x-data="{ open: false }" 
     x-show="open" 
     x-cloak
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
     @click.away="open = false">
    
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200 delay-100"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl max-w-md w-full p-6"
         @click.stop>
        
        <!-- Icon -->
        <div class="flex items-center justify-center w-14 h-14 mx-auto mb-4 rounded-full bg-blue-100 dark:bg-blue-900/20">
            <i class="fas fa-{{ $icon ?? 'question-circle' }} text-2xl text-blue-600 dark:text-blue-400"></i>
        </div>
        
        <!-- Content -->
        <h3 class="text-lg font-semibold text-center text-gray-900 dark:text-white">
            {{ $title ?? 'Confirm Action' }}
        </h3>
        <p class="mt-2 text-sm text-center text-gray-500 dark:text-gray-400">
            {{ $message ?? 'Are you sure you want to proceed with this action?' }}
        </p>
        
        <!-- Details -->
        @if (isset($details))
            <div class="mt-4 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg text-sm">
                <pre class="text-gray-600 dark:text-gray-400 whitespace-pre-wrap">{{ $details }}</pre>
            </div>
        @endif
        
        <!-- Actions -->
        <div class="flex items-center justify-center gap-3 mt-6">
            <button @click="open = false" 
                    class="px-5 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                Cancel
            </button>
            <button @click="$dispatch('confirm-action')" 
                    class="px-5 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm transition-all hover:shadow-md">
                <i class="fas fa-check mr-1.5 text-xs"></i>
                Confirm
            </button>
        </div>
    </div>
</div>