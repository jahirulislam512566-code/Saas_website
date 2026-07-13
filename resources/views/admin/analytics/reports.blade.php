{{-- resources/views/admin/analytics/reports.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Analytics Reports')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <a href="{{ route('admin.analytics.dashboard') }}" class="text-gray-500 hover:text-gray-700">Analytics</a>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">Reports</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Analytics Reports</h1>
            <p class="text-sm text-gray-500 mt-1">Generate and export detailed analytics reports</p>
        </div>
        <button onclick="generateReport()" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
            <i class="fas fa-plus mr-2"></i> Generate Report
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <form id="reportForm" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Report Type</label>
                <select name="type" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="revenue">Revenue Report</option>
                    <option value="subscriptions">Subscription Report</option>
                    <option value="visitors">Visitor Report</option>
                    <option value="sales">Sales Report</option>
                    <option value="templates">Template Usage Report</option>
                    <option value="custom">Custom Report</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                <select name="range" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month" selected>This Month</option>
                    <option value="quarter">This Quarter</option>
                    <option value="year">This Year</option>
                    <option value="custom">Custom Range</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Format</label>
                <select name="format" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="pdf">PDF</option>
                    <option value="excel">Excel</option>
                    <option value="csv">CSV</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                    <i class="fas fa-download mr-2"></i> Download
                </button>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Reports</p>
                    <p class="text-xl font-bold text-gray-900">{{ $reports['total'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center">
                    <i class="fas fa-file-alt"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Generated This Month</p>
                    <p class="text-xl font-bold text-blue-600">{{ $reports['this_month'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-calendar-alt"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Scheduled Reports</p>
                    <p class="text-xl font-bold text-green-600">{{ $reports['scheduled'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-sm font-medium text-gray-900">Recent Reports</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Report Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date Range</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Size</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Generated</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports['recent'] ?? [] as $report)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $report->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ ucfirst($report->type) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $report->date_range }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $report->size }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $report->created_at->diffForHumans() }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.analytics.reports.download', $report) }}" class="text-gray-400 hover:text-primary-600">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <button onclick="deleteReport('{{ $report->id }}')" class="text-gray-400 hover:text-red-600">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">No reports generated yet</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('reportForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const type = this.querySelector('[name="type"]').value;
        const range = this.querySelector('[name="range"]').value;
        const format = this.querySelector('[name="format"]').value;
        
        window.location.href = `/admin/analytics/reports/generate?type=${type}&range=${range}&format=${format}`;
    });

    function deleteReport(id) {
        if (confirm('Delete this report?')) {
            fetch(`/admin/analytics/reports/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                }
            });
        }
    }

    function generateReport() {
        document.getElementById('reportForm').dispatchEvent(new Event('submit'));
    }
</script>
@endpush
@endsection