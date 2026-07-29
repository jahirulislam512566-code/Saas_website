{{-- resources/views/frontend/partials/pagination.blade.php --}}
@props([
    'current' => 1,
    'total' => 1,
    'perPage' => 10,
    'url' => '#',
    'showInfo' => true,
    'showFirstLast' => true,
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
    
    $from = ($current - 1) * $perPage + 1;
    $to = min($current * $perPage, $total);
@endphp

@if($totalPages > 1)
    <nav class="flex flex-col sm:flex-row items-center justify-between gap-4" aria-label="Pagination">
        {{-- Info --}}
        @if($showInfo)
            <div class="text-sm text-[#4a4a4a] order-2 sm:order-1">
                Showing <span class="font-medium">{{ $from }}</span> 
                to <span class="font-medium">{{ $to }}</span> 
                of <span class="font-medium">{{ $total }}</span> results
            </div>
        @endif

        {{-- Pagination Links --}}
        <div class="flex items-center gap-1 order-1 sm:order-2">
            {{-- First --}}
            @if($showFirstLast)
                <a href="{{ $current > 1 ? $url . '?page=1' : '#' }}" 
                   class="px-3 py-2 text-sm rounded-lg border border-gray-200 hover:border-[#6c5ce7] hover:text-[#6c5ce7] transition-colors {{ $current <= 1 ? 'opacity-50 pointer-events-none' : '' }}">
                    « First
                </a>
            @endif

            {{-- Previous --}}
            <a href="{{ $current > 1 ? $url . '?page=' . ($current - 1) : '#' }}" 
               class="px-3 py-2 text-sm rounded-lg border border-gray-200 hover:border-[#6c5ce7] hover:text-[#6c5ce7] transition-colors {{ $current <= 1 ? 'opacity-50 pointer-events-none' : '' }}">
                ←
            </a>

            {{-- Page numbers --}}
            @if($start > 1)
                <a href="{{ $url }}?page=1" class="px-3.5 py-2 text-sm rounded-lg hover:bg-[#6c5ce7]/10 transition-colors">1</a>
                @if($start > 2)
                    <span class="px-2 text-[#4a4a4a]">…</span>
                @endif
            @endif

            @for($i = $start; $i <= $end; $i++)
                <a href="{{ $url }}?page={{ $i }}" 
                   class="px-3.5 py-2 text-sm rounded-lg transition-all duration-200 {{ $i === $current ? 'bg-[#6c5ce7] text-white shadow-lg shadow-[#6c5ce7]/30' : 'hover:bg-[#6c5ce7]/10' }}">
                    {{ $i }}
                </a>
            @endfor

            @if($end < $totalPages)
                @if($end < $totalPages - 1)
                    <span class="px-2 text-[#4a4a4a]">…</span>
                @endif
                <a href="{{ $url }}?page={{ $totalPages }}" class="px-3.5 py-2 text-sm rounded-lg hover:bg-[#6c5ce7]/10 transition-colors">{{ $totalPages }}</a>
            @endif

            {{-- Next --}}
            <a href="{{ $current < $totalPages ? $url . '?page=' . ($current + 1) : '#' }}" 
               class="px-3 py-2 text-sm rounded-lg border border-gray-200 hover:border-[#6c5ce7] hover:text-[#6c5ce7] transition-colors {{ $current >= $totalPages ? 'opacity-50 pointer-events-none' : '' }}">
                →
            </a>

            {{-- Last --}}
            @if($showFirstLast)
                <a href="{{ $current < $totalPages ? $url . '?page=' . $totalPages : '#' }}" 
                   class="px-3 py-2 text-sm rounded-lg border border-gray-200 hover:border-[#6c5ce7] hover:text-[#6c5ce7] transition-colors {{ $current >= $totalPages ? 'opacity-50 pointer-events-none' : '' }}">
                    Last »
                </a>
            @endif
        </div>

        {{-- Per Page Selector --}}
        <div class="flex items-center gap-2 text-sm text-[#4a4a4a] order-3">
            <label for="per-page" class="hidden sm:inline">Show:</label>
            <select id="per-page" 
                    class="px-3 py-1.5 rounded-lg border-2 border-gray-200 focus:border-[#6c5ce7] focus:outline-none text-sm bg-white"
                    onchange="window.location.href = this.value">
                <option value="{{ $url }}?per_page=10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                <option value="{{ $url }}?per_page=25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                <option value="{{ $url }}?per_page=50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                <option value="{{ $url }}?per_page=100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
            </select>
            <span class="hidden sm:inline">per page</span>
        </div>
    </nav>
@endif