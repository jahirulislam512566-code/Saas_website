@extends('admin.layouts.admin')

@section('title', 'Sales Report')

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
            <span class="text-gray-500">Sales Report</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Sales Report</h1>
            <p class="text-sm text-gray-500 mt-1">Comprehensive sales analysis and performance metrics</p>
        </div>
        <div class="flex items-center space-x-3">
            <button type="button" onclick="window.print()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-print mr-2"></i> Print
            </button>
            <button type="button" onclick="exportReport('sales')" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-download mr-2"></i> Export
            </button>
        </div>
    </div>
    
    <!-- Report Filters -->
    <div class="bg-white rounded-xl shadow-sm p-4">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                <select name="period" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Today</option>
                    <option value="week" {{ request('period') == 'week' ? 'selected' : '' }}>This Week</option>
                    <option value="month" {{ request('period') == 'month' ? 'selected' : '' }}>This Month</option>
                    <option value="quarter" {{ request('period') == 'quarter' ? 'selected' : '' }}>This Quarter</option>
                    <option value="year" {{ request('period') == 'year' ? 'selected' : '' }}>This Year</option>
                    <option value="custom" {{ request('period') == 'custom' ? 'selected' : '' }}>Custom</option>
                </select>
            </div>
            
            <div id="custom-date-range" style="{{ request('period') == 'custom' ? '' : 'display: none;' }}">
                <label class="block text-sm font-medium text-gray-700 mb-1">From</label>
                <input type="date" name="from" value="{{ request('from') }}" 
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
            </div>
            
            <div id="custom-date-end" style="{{ request('period') == 'custom' ? '' : 'display: none;' }}">
                <label class="block text-sm font-medium text-gray-700 mb-1">To</label>
                <input type="date" name="to" value="{{ request('to') }}" 
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    <i class="fas fa-search mr-2"></i> Generate
                </button>
                <a href="{{ route('admin.reports.sales') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-times mr-2"></i> Reset
                </a>
            </div>
        </form>
    </div>
    
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-primary-500">
            <p class="text-sm text-gray-500">Total Sales</p>
            <p class="text-2xl font-bold text-gray-900">${{ number_format($reportData['total_sales'] ?? 0, 2) }}</p>
            <span class="text-xs {{ ($reportData['sales_growth'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                <i class="fas fa-{{ ($reportData['sales_growth'] ?? 0) >= 0 ? 'arrow-up' : 'arrow-down' }} mr-1"></i>
                {{ abs($reportData['sales_growth'] ?? 0) }}% from previous period
            </span>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-green-500">
            <p class="text-sm text-gray-500">Orders</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($reportData['total_orders'] ?? 0) }}</p>
            <span class="text-xs {{ ($reportData['orders_growth'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                <i class="fas fa-{{ ($reportData['orders_growth'] ?? 0) >= 0 ? 'arrow-up' : 'arrow-down' }} mr-1"></i>
                {{ abs($reportData['orders_growth'] ?? 0) }}% from previous period
            </span>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-blue-500">
            <p class="text-sm text-gray-500">Average Order Value</p>
            <p class="text-2xl font-bold text-gray-900">${{ number_format($reportData['average_order_value'] ?? 0, 2) }}</p>
            <span class="text-xs {{ ($reportData['aov_growth'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                <i class="fas fa-{{ ($reportData['aov_growth'] ?? 0) >= 0 ? 'arrow-up' : 'arrow-down' }} mr-1"></i>
                {{ abs($reportData['aov_growth'] ?? 0) }}% from previous period
            </span>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-yellow-500">
            <p class="text-sm text-gray-500">Refund Rate</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($reportData['refund_rate'] ?? 0, 1) }}%</p>
            <span class="text-xs {{ ($reportData['refund_growth'] ?? 0) <= 0 ? 'text-green-600' : 'text-red-600' }}">
                <i class="fas fa-{{ ($reportData['refund_growth'] ?? 0) <= 0 ? 'arrow-down' : 'arrow-up' }} mr-1"></i>
                {{ abs($reportData['refund_growth'] ?? 0) }}% from previous period
            </span>
        </div>
    </div>
    
    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Sales Trend</h3>
            <div class="h-72" x-data="salesTrendChart()">
                <canvas id="salesTrendChart"></canvas>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Sales by Plan</h3>
            <div class="h-72" x-data="salesByPlanChart()">
                <canvas id="salesByPlanChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Sales Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Transaction Details</h3>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-500">Showing {{ $transactions->firstItem() ?? 0 }} - {{ $transactions->lastItem() ?? 0 }} of {{ $transactions->total() ?? 0 }}</span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($transactions ?? [] as $transaction)
                        <tr>
                            <td class="px-6 py-4 text-sm font-mono text-gray-900">#{{ $transaction->id }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 text-sm font-medium">
                                        {{ substr($transaction->user->name ?? 'N/A', 0, 2) }}
                                    </div>
                                    <span class="ml-3 text-sm text-gray-900">{{ $transaction->user->name ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $transaction->subscription->plan->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">${{ number_format($transaction->amount, 2) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $transaction->payment_method ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $transaction->status === 'succeeded' ? 'bg-green-100 text-green-800' : 
                                       ($transaction->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                       ($transaction->status === 'refunded' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800')) }}">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-file-invoice text-4xl text-gray-300 mb-3 block"></i>
                                <p class="text-lg font-medium">No transactions found</p>
                                <p class="text-sm mt-1">Try adjusting your date range or filters</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if(isset($transactions) && $transactions instanceof \Illuminate\Pagination\LengthAwarePaginator && $transactions->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.querySelector('select[name="period"]').addEventListener('change', function() {
        const customDateRange = document.getElementById('custom-date-range');
        const customDateEnd = document.getElementById('custom-date-end');
        if (this.value === 'custom') {
            customDateRange.style.display = 'block';
            customDateEnd.style.display = 'block';
        } else {
            customDateRange.style.display = 'none';
            customDateEnd.style.display = 'none';
        }
    });
    
    function salesTrendChart() {
        return {
            init() {
                const ctx = document.getElementById('salesTrendChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($chartData['trend_labels'] ?? []) !!},
                        datasets: [{
                            label: 'Sales',
                            data: {!! json_encode($chartData['trend_data'] ?? []) !!},
                            borderColor: '#6366f1',
                            backgroundColor: 'rgba(99, 102, 241, 0.1)',
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return '$' + context.parsed.y;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return '$' + value;
                                    }
                                }
                            }
                        }
                    }
                });
            }
        }
    }
    
    function salesByPlanChart() {
        return {
            init() {
                const ctx = document.getElementById('salesByPlanChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($chartData['plan_labels'] ?? []) !!},
                        datasets: [{
                            label: 'Revenue',
                            data: {!! json_encode($chartData['plan_data'] ?? []) !!},
                            backgroundColor: ['#6366f1', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981'],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return '$' + value;
                                    }
                                }
                            }
                        }
                    }
                });
            }
        }
    }
    
    function exportReport(type) {
        const period = document.querySelector('select[name="period"]').value;
        const from = document.querySelector('input[name="from"]')?.value || '';
        const to = document.querySelector('input[name="to"]')?.value || '';
        window.location.href = `{{ route('admin.reports.export') }}?type=${type}&period=${period}&from=${from}&to=${to}`;
    }
</script>
@endpush
@endsection