<!-- admin/partials/breadcrumb.blade.php -->
<nav class="flex items-center gap-2 text-sm" aria-label="Breadcrumb">
    <ol class="flex items-center gap-2 flex-wrap">
        @foreach ($breadcrumbs as $index => $crumb)
            @if ($index > 0)
                <i class="fas fa-chevron-right text-xs text-gray-400 dark:text-gray-600"></i>
            @endif
            
            @if ($loop->last)
                <li class="text-gray-700 dark:text-gray-300 font-medium" aria-current="page">
                    {{ $crumb['label'] }}
                </li>
            @else
                <li>
                    <a href="{{ $crumb['url'] }}" class="text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                        {{ $crumb['label'] }}
                    </a>
                </li>
            @endif
        @endforeach
    </ol>
</nav>