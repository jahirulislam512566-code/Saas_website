{{-- resources/views/admin/dashboard/charts/revenue.blade.php --}}
<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-sm font-medium text-gray-900">Revenue Overview</h3>
        <div class="flex items-center space-x-2">
            <button onclick="setChartPeriod('week')" 
                    class="text-xs px-2 py-1 rounded {{ $chartPeriod == 'week' ? 'bg-primary-100 text-primary-700' : 'text-gray-500 hover:text-gray-700' }}">
                Week
            </button>
            <button onclick="setChartPeriod('month')" 
                    class="text-xs px-2 py-1 rounded {{ $chartPeriod == 'month' ? 'bg-primary-100 text-primary-700' : 'text-gray-500 hover:text-gray-700' }}">
                Month
            </button>
            <button onclick="setChartPeriod('year')" 
                    class="text-xs px-2 py-1 rounded {{ $chartPeriod == 'year' ? 'bg-primary-100 text-primary-700' : 'text-gray-500 hover:text-gray-700' }}">
                Year
            </button>
            <button onclick="toggleChartType('revenue')" 
                    class="text-xs text-gray-400 hover:text-gray-600">
                <i class="fas fa-chart-bar"></i>
            </button>
        </div>
    </div>
    <div class="h-64">
        <canvas id="revenueChart"></canvas>
    </div>
</div>