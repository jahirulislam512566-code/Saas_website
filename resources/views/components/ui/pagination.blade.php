{{-- resources/views/components/ui/pagination.blade.php --}}
@props([
    'current' => 1,
    'total' => 1,
    'perPage' => 10,
    'url' => '#',
])

@php
    $totalPages = ceil($total / $perPage);
    $showPages = 5;
    $half = floor($showPages / 2);
    $start = max(1, $current - $half);
    $end = min($totalPages, $current + $half);
    
    if ($end - $start + 1 < $showPages) {
        if ($start === 1) {
            $end = min($totalPages, $start + $showPages - 1);
        } else {
            $start = max(1, $end - $showPages + 1);
        }
    }
@endphp

@if($totalPages > 1)
    <nav class="flex items-center justify-between gap-4 flex-wrap" aria-label="Pagination">
        <div class="flex items-center gap-2">
            {{-- Previous --}}
            <a href="{{ $current > 1 ? $url . '?page=' . ($current - 1) : '#' }}" 
               class="px-3 py-2 text-sm rounded-lg border border-gray-200 hover:border-[#6c5ce7] hover:text-[#6c5ce7] transition-colors {{ $current <= 1 ? 'opacity-50 pointer-events-none' : '' }}">
                ← Previous
            </a>
            
            {{-- Page numbers --}}
            @if($start > 1)
                <a href="{{ $url }}?page=1" class="px-3 py-2 text-sm rounded-lg hover:bg-[#6c5ce7]/10 transition-colors">1</a>
                @if($start > 2)
                    <span class="px-2">…</span>
                @endif
            @endif

            @for($i = $start; $i <= $end; $i++)
                <a href="{{ $url }}?page={{ $i }}" 
                   class="px-3.5 py-2 text-sm rounded-lg transition-colors {{ $i === $current ? 'bg-[#6c5ce7] text-white' : 'hover:bg-[#6c5ce7]/10' }}">
                    {{ $i }}
                </a>
            @endfor

            @if($end < $totalPages)
                @if($end < $totalPages - 1)
                    <span class="px-2">…</span>
                @endif
                <a href="{{ $url }}?page={{ $totalPages }}" class="px-3 py-2 text-sm rounded-lg hover:bg-[#6c5ce7]/10 transition-colors">{{ $totalPages }}</a>
            @endif

            {{-- Next --}}
            <a href="{{ $current < $totalPages ? $url . '?page=' . ($current + 1) : '#' }}" 
               class="px-3 py-2 text-sm rounded-lg border border-gray-200 hover:border-[#6c5ce7] hover:text-[#6c5ce7] transition-colors {{ $current >= $totalPages ? 'opacity-50 pointer-events-none' : '' }}">
                Next →
            </a>
        </div>
        <span class="text-sm text-[#4a4a4a]">
            Page {{ $current }} of {{ $totalPages }}
        </span>
    </nav>
@endif