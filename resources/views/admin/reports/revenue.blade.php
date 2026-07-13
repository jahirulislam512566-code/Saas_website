{{-- resources/views/admin/reports/revenue.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Revenue Report')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <a href="{{ route('admin.reports.index') }}" class="text-gray-500 hover:text-gray-700">Reports</a>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">Revenue</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- ===== HEADER ===== -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Revenue Report</h1>
            <p class="text-sm text-gray-500 mt-1">Track revenue and financial performance</p>
        </div>
        <div class="flex items-center space-x-3">
            <button onclick="exportReport('revenue')" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-download mr-2"></i> Export
            </button>
            <button onclick="printReport()" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-print mr-2"></i> Print
            </button>
        </div>
    </div>

    <!-- ===== DATE RANGE ===== -->
    <div class="bg-white rounded-xl shadow-sm p-4">
        <form method="GET" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                <input type="date" name="start_date" value="{{ request('start_date', now()->subDays(30)->format('Y-m-d')) }}"
                       class="rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                <input type="date" name="end_date" value="{{ request('end_date', now()->format('Y-m-d')) }}"
                       class="rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Currency</label>
                <select name="currency" class="rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="USD" {{ request('currency', 'USD') == 'USD' ? 'selected' : '' }}>USD</option>
                    <option value="EUR" {{ request('currency') == 'EUR' ? 'selected' : '' }}>EUR</option>
                    <option value="GBP" {{ request('currency') == 'GBP' ? 'selected' : '' }}>GBP</option>
                    <option value="CAD" {{ request('currency') == 'CAD' ? 'selected' : '' }}>CAD</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-sync mr-2"></i> Update Report
            </button>
        </form>
    </div>

    <!-- ===== STATS CARDS ===== -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <p class="text-sm text-gray-500">Total Revenue</p>
            <p class="text-xl font-bold text-gray-900">${{ number_format($stats['total'] ?? 0, 2) }}</p>
            <div class="mt-1 text-xs text-green-600">
                <i class="fas fa-arrow-up mr-1"></i> +{{ $stats['growth'] ?? 0 }}%
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <p class="text-sm text-gray-500">MRR</p>
            <p class="text-xl font-bold text-blue-600">${{ number_format($stats['mrr'] ?? 0, 2) }}</p>
            <div class="mt-1 text-xs text-blue-600">
                <i class="fas fa-arrow-up mr-1"></i> +{{ $stats['mrr_growth'] ?? 0 }}%
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <p class="text-sm text-gray-500">ARR</p>
            <p class="text-xl font-bold text-green-600">${{ number_format($stats['arr'] ?? 0, 2) }}</p>
            <div class="mt-1 text-xs text-green-600">
                <i class="fas fa-arrow-up mr-1"></i> +{{ $stats['arr_growth'] ?? 0 }}%
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <p class="text-sm text-gray-500">ARPU</p>
            <p class="text-xl font-bold text-purple-600">${{ number_format($stats['arpu'] ?? 0, 2) }}</p>
            <div class="mt-1 text-xs text-purple-600">
                <i class="fas fa-arrow-up mr-1"></i> +{{ $stats['arpu_growth'] ?? 0 }}%
            </div>
        </div>
    </div>

    <!-- ===== CHARTS ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-sm font-medium text-gray-900 mb-4">Revenue Trends</h3>
            <div class="h-64">
                <canvas id="revenueTrendChart"></canvas>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-sm font-medium text-gray-900 mb-4">Revenue Breakdown</h3>
            <div class="h-64">
                <canvas id="revenueBreakdownChart"></canvas>
            </div>
        </div>
    </div>

    <!-- ===== REVENUE TABLE ===== -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-sm font-medium text-gray-900">Revenue Details</h3>
            <span class="text-xs text-gray-500">Total: ${{ number_format($revenueTotal ?? 0, 2) }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Revenue</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subscriptions</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">MRR</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ARR</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Growth</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($revenueData as $data)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $data['date'] }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">${{ number_format($data['revenue'], 2) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $data['subscriptions'] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">${{ number_format($data['mrr'], 2) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">${{ number_format($data['arr'], 2) }}</td>
                            <td class="px-6 py-4 text-sm {{ $data['growth'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $data['growth'] >= 0 ? '+' : '' }}{{ number_format($data['growth'], 1) }}%
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">No revenue data found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new Chart(document.getElementById('revenueTrendChart'), {
            type: 'line',
            data: {
                labels: @json($chartData['labels'] ?? []),
                datasets: [
                    {
                        label: 'Revenue',
                        data: @json($chartData['revenue'] ?? []),
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'MRR',
                        data: @json($chartData['mrr'] ?? []),
                        borderColor: '#22c55e',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        fill: true,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'top' } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: function(v) { return '$' + v; } }
                    }
                }
            }
        });

        new Chart(document.getElementById('revenueBreakdownChart'), {
            type: 'doughnut',
            data: {
                labels: @json($breakdown['labels'] ?? []),
                datasets: [{
                    data: @json($breakdown['data'] ?? []),
                    backgroundColor: ['#6366f1', '#8b5cf6', '#3b82f6', '#22c55e', '#f59e0b']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 12, padding: 12, font: { size: 11 } }
                    }
                }
            }
        });
    });

    function exportReport(type) {
        window.location.href = `/admin/reports/export/${type}?start_date={{ request('start_date') }}&end_date={{ request('end_date') }}`;
    }

    function printReport() {
        window.print();
    }
</script>
@endpush
@endsection