<!-- admin/partials/pagination.blade.php -->
@if ($paginator->hasPages())
<div class="flex flex-col sm:flex-row items-center justify-between gap-4 px-4 py-3 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800">
    <!-- Result count -->
    <div class="text-sm text-gray-700 dark:text-gray-300">
        <span class="font-medium">{{ $paginator->firstItem() }}</span>
        to <span class="font-medium">{{ $paginator->lastItem() }}</span>
        of <span class="font-medium">{{ $paginator->total() }}</span> results
    </div>
    
    <!-- Pagination links -->
    <div class="flex items-center gap-1">
        <!-- Previous -->
        @if ($paginator->onFirstPage())
            <span class="px-3 py-1.5 text-gray-400 dark:text-gray-600 rounded-lg border border-gray-200 dark:border-gray-800 cursor-not-allowed">
                <i class="fas fa-chevron-left text-xs"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" 
               class="px-3 py-1.5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-800 transition-colors">
                <i class="fas fa-chevron-left text-xs"></i>
            </a>
        @endif
        
        <!-- Pages -->
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-3 py-1.5 text-gray-400 dark:text-gray-600">…</span>
            @endif
            
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="px-3 py-1.5 bg-indigo-600 text-white rounded-lg font-medium">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" 
                           class="px-3 py-1.5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach
        
        <!-- Next -->
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" 
               class="px-3 py-1.5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-800 transition-colors">
                <i class="fas fa-chevron-right text-xs"></i>
            </a>
        @else
            <span class="px-3 py-1.5 text-gray-400 dark:text-gray-600 rounded-lg border border-gray-200 dark:border-gray-800 cursor-not-allowed">
                <i class="fas fa-chevron-right text-xs"></i>
            </span>
        @endif
    </div>
</div>
@endif