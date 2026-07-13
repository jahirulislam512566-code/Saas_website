{{-- resources/views/admin/reports/invoices.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Invoices Report')

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
            <span class="text-gray-500">Invoices</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- ===== HEADER ===== -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Invoices Report</h1>
            <p class="text-sm text-gray-500 mt-1">Track invoice generation and payments</p>
        </div>
        <div class="flex items-center space-x-3">
            <button onclick="exportReport('invoices')" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-download mr-2"></i> Export
            </button>
            <button onclick="printReport()" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-print mr-2"></i> Print
            </button>
        </div>
    </div>

    <!-- ===== STATS CARDS ===== -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <p class="text-sm text-gray-500">Total Invoices</p>
            <p class="text-xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
            <div class="mt-1 text-xs text-green-600">
                <i class="fas fa-arrow-up mr-1"></i> +{{ $stats['growth'] ?? 0 }}%
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <p class="text-sm text-gray-500">Paid</p>
            <p class="text-xl font-bold text-green-600">{{ $stats['paid'] ?? 0 }}</p>
            <div class="mt-1 text-xs text-green-600">
                {{ number_format($stats['paid_percentage'] ?? 0, 1) }}% of total
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <p class="text-sm text-gray-500">Unpaid</p>
            <p class="text-xl font-bold text-red-600">{{ $stats['unpaid'] ?? 0 }}</p>
            <div class="mt-1 text-xs text-red-600">
                {{ number_format($stats['unpaid_percentage'] ?? 0, 1) }}% of total
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <p class="text-sm text-gray-500">Total Amount</p>
            <p class="text-xl font-bold text-purple-600">${{ number_format($stats['total_amount'] ?? 0, 2) }}</p>
            <div class="mt-1 text-xs text-purple-600">
                Avg. ${{ number_format($stats['avg_amount'] ?? 0, 2) }} per invoice
            </div>
        </div>
    </div>

    <!-- ===== CHARTS ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-sm font-medium text-gray-900 mb-4">Invoice Trends</h3>
            <div class="h-64">
                <canvas id="invoiceTrendChart"></canvas>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-sm font-medium text-gray-900 mb-4">Invoice Status</h3>
            <div class="h-64">
                <canvas id="invoiceStatusChart"></canvas>
            </div>
        </div>
    </div>

    <!-- ===== INVOICES TABLE ===== -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-sm font-medium text-gray-900">Invoice Details</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Due Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($invoices as $invoice)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">#{{ $invoice->invoice_number }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $invoice->user->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">${{ number_format($invoice->amount, 2) }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $invoice->status == 'paid' ? 'bg-green-100 text-green-800' : 
                                       ($invoice->status == 'unpaid' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $invoice->due_date->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.billing.invoices.view', $invoice) }}" class="text-gray-400 hover:text-blue-600 transition-colors">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">No invoices found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $invoices->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new Chart(document.getElementById('invoiceTrendChart'), {
            type: 'line',
            data: {
                labels: @json($chartData['labels'] ?? []),
                datasets: [
                    {
                        label: 'Amount',
                        data: @json($chartData['amount'] ?? []),
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Count',
                        data: @json($chartData['count'] ?? []),
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

        new Chart(document.getElementById('invoiceStatusChart'), {
            type: 'doughnut',
            data: {
                labels: @json($statusData['labels'] ?? []),
                datasets: [{
                    data: @json($statusData['data'] ?? []),
                    backgroundColor: ['#22c55e', '#ef4444', '#eab308']
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
        window.location.href = `/admin/reports/export/${type}`;
    }

    function printReport() {
        window.print();
    }
</script>
@endpush
@endsection