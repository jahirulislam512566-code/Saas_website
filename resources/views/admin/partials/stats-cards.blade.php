<!-- admin/partials/stats-cards.blade.php -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    @foreach ($stats as $stat)
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm hover:shadow-md transition-all duration-300 group">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ $stat['label'] }}
                    </p>
                    <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $stat['value'] }}
                    </p>
                </div>
                <div class="w-11 h-11 rounded-lg bg-{{ $stat['color'] ?? 'indigo' }}-100 dark:bg-{{ $stat['color'] ?? 'indigo' }}-900/20 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-{{ $stat['icon'] }} text-{{ $stat['color'] ?? 'indigo' }}-600 dark:text-{{ $stat['color'] ?? 'indigo' }}-400 text-lg"></i>
                </div>
            </div>
            
            @if (isset($stat['change']))
                <div class="mt-3 flex items-center gap-1.5 text-sm">
                    <span class="{{ $stat['change'] >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }} font-medium">
                        <i class="fas fa-{{ $stat['change'] >= 0 ? 'arrow-up' : 'arrow-down' }} text-xs mr-0.5"></i>
                        {{ abs($stat['change']) }}%
                    </span>
                    <span class="text-gray-500 dark:text-gray-400">vs last month</span>
                </div>
            @endif
            
            @if (isset($stat['trend']))
                <div class="mt-2 w-full h-1 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                    <div class="h-full bg-{{ $stat['color'] ?? 'indigo' }}-500 rounded-full transition-all duration-500" 
                         style="width: {{ min($stat['trend'], 100) }}%"></div>
                </div>
            @endif
        </div>
    @endforeach
</div>