<!-- admin/partials/charts.blade.php -->
<div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                {{ $title ?? 'Analytics' }}
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                {{ $subtitle ?? 'Overview of your key metrics' }}
            </p>
        </div>
        <div class="flex items-center gap-2">
            @foreach (['Week', 'Month', 'Year'] as $period)
                <button class="px-3 py-1 text-xs font-medium rounded-lg transition-all
                              {{ ($activePeriod ?? 'Week') == $period 
                                  ? 'bg-indigo-600 text-white shadow-sm' 
                                  : 'text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                    {{ $period }}
                </button>
            @endforeach
            <button class="px-3 py-1 text-xs font-medium text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg transition-all">
                <i class="fas fa-download"></i>
            </button>
        </div>
    </div>
    
    <!-- Chart Container -->
    <div class="h-72 relative">
        <canvas x-data="chartComponent({{ json_encode($labels ?? []), json_encode($datasets ?? []) }})" 
                x-init="initChart()"
                x-ref="chart"></canvas>
    </div>
    
    <!-- Legend -->
    @if (isset($datasets) && count($datasets) > 1)
        <div class="flex flex-wrap items-center justify-center gap-4 mt-4 pt-4 border-t border-gray-200 dark:border-gray-800">
            @foreach ($datasets as $dataset)
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full" style="background-color: {{ $dataset['borderColor'] ?? '#4f46e5' }}"></span>
                    <span class="text-xs text-gray-600 dark:text-gray-400">{{ $dataset['label'] }}</span>
                </div>
            @endforeach
        </div>
    @endif
</div>

@push('scripts')
<script>
function chartComponent(labels, datasets) {
    return {
        initChart() {
            const ctx = this.$refs.chart.getContext('2d');
            const isDark = document.documentElement.classList.contains('dark');
            
            new Chart(ctx, {
                type: '{{ $type ?? "line" }}',
                data: {
                    labels: labels,
                    datasets: datasets.map(ds => ({
                        ...ds,
                        borderWidth: 2,
                        tension: 0.4,
                        pointRadius: 3,
                        pointHoverRadius: 6,
                    }))
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: isDark ? '#1f2937' : '#ffffff',
                            titleColor: isDark ? '#f3f4f6' : '#111827',
                            bodyColor: isDark ? '#d1d5db' : '#374151',
                            borderColor: isDark ? '#374151' : '#e5e7eb',
                            borderWidth: 1,
                            padding: 12,
                            cornerRadius: 8,
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                color: isDark ? '#1f2937' : '#f3f4f6',
                                drawBorder: false,
                            },
                            ticks: {
                                color: isDark ? '#9ca3af' : '#6b7280',
                                maxRotation: 45,
                                font: { size: 11 }
                            }
                        },
                        y: {
                            grid: {
                                color: isDark ? '#1f2937' : '#f3f4f6',
                                drawBorder: false,
                            },
                            ticks: {
                                color: isDark ? '#9ca3af' : '#6b7280',
                                font: { size: 11 },
                                callback: function(value) {
                                    if (value >= 1000) return value/1000 + 'k';
                                    return value;
                                }
                            },
                            beginAtZero: true
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                }
            });
        }
    }
}
</script>
@endpush