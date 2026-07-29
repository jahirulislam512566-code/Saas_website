{{-- resources/views/components/cards/project-card.blade.php --}}
@props([
    'image' => null,
    'title' => 'Project Title',
    'client' => 'Client Name',
    'status' => 'Completed',
    'progress' => 100,
    'deadline' => null,
    'team' => [],
    'link' => '#',
])

@php
    $statusColors = [
        'Completed' => 'bg-emerald-100 text-emerald-700',
        'In Progress' => 'bg-blue-100 text-blue-700',
        'On Hold' => 'bg-amber-100 text-amber-700',
        'Planning' => 'bg-indigo-100 text-indigo-700',
        'Review' => 'bg-purple-100 text-purple-700',
    ];
    $statusColor = $statusColors[$status] ?? 'bg-gray-100 text-gray-700';
@endphp

<div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
    {{-- Header --}}
    <div class="flex items-start justify-between p-6 pb-4 border-b border-gray-100">
        <div>
            <h3 class="text-lg font-bold text-[#0a0a0a]">{{ $title }}</h3>
            <p class="text-sm text-[#4a4a4a]">{{ $client }}</p>
        </div>
        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusColor }}">
            {{ $status }}
        </span>
    </div>

    {{-- Progress --}}
    @if($progress !== null)
        <div class="px-6 py-4">
            <div class="flex items-center justify-between text-sm mb-1.5">
                <span class="text-[#4a4a4a]">Progress</span>
                <span class="font-semibold text-[#0a0a0a]">{{ $progress }}%</span>
            </div>
            <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-[#6c5ce7] to-[#a78bfa] rounded-full transition-all duration-1000" style="width: {{ $progress }}%"></div>
            </div>
        </div>
    @endif

    {{-- Details --}}
    <div class="px-6 pb-4 flex flex-wrap gap-4 text-sm">
        @if($deadline)
            <div class="flex items-center gap-2 text-[#4a4a4a]">
                <span>📅</span>
                <span>Due: {{ $deadline }}</span>
            </div>
        @endif
        @if(count($team) > 0)
            <div class="flex items-center gap-2">
                <div class="flex -space-x-2">
                    @foreach(array_slice($team, 0, 3) as $member)
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#6c5ce7] to-[#a78bfa] flex items-center justify-center text-xs font-semibold text-white ring-2 ring-white">
                            {{ strtoupper(substr($member, 0, 2)) }}
                        </div>
                    @endforeach
                    @if(count($team) > 3)
                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-semibold text-[#4a4a4a] ring-2 ring-white">
                            +{{ count($team) - 3 }}
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    {{-- Footer --}}
    @if($link)
        <div class="px-6 py-3 bg-gray-50 border-t border-gray-100">
            <a href="{{ $link }}" class="text-sm font-medium text-[#6c5ce7] hover:text-[#5a4bd1] transition-colors flex items-center gap-2">
                View Project Details →
            </a>
        </div>
    @endif
</div>