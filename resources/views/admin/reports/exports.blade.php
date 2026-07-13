{{-- resources/views/admin/reports/exports.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Export Reports')

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
            <span class="text-gray-500">Exports</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- ===== HEADER ===== -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Export Reports</h1>
            <p class="text-sm text-gray-500 mt-1">Generate and download custom reports</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.reports.schedule') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-clock mr-2"></i> Schedule Export
            </a>
        </div>
    </div>

    <!-- ===== EXPORT OPTIONS ===== -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Users Export -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-white">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Users Report</h3>
                        <p class="text-xs text-gray-500">Export user data</p>
                    </div>
                </div>
            </div>
            <div class="p-4 space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Format</span>
                    <select class="border-0 bg-transparent text-sm focus:ring-0">
                        <option value="csv">CSV</option>
                        <option value="excel">Excel</option>
                        <option value="pdf">PDF</option>
                    </select>
                </div>
                <button onclick="exportReport('users')" 
                        class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                    <i class="fas fa-download mr-2"></i> Download
                </button>
            </div>
        </div>

        <!-- Revenue Export -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-white">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-lg bg-green-100 text-green-600 flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Revenue Report</h3>
                        <p class="text-xs text-gray-500">Export revenue data</p>
                    </div>
                </div>
            </div>
            <div class="p-4 space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Format</span>
                    <select class="border-0 bg-transparent text-sm focus:ring-0">
                        <option value="csv">CSV</option>
                        <option value="excel">Excel</option>
                        <option value="pdf">PDF</option>
                    </select>
                </div>
                <button onclick="exportReport('revenue')" 
                        class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                    <i class="fas fa-download mr-2"></i> Download
                </button>
            </div>
        </div>

        <!-- Subscriptions Export -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-white">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center">
                        <i class="fas fa-receipt text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Subscriptions Report</h3>
                        <p class="text-xs text-gray-500">Export subscription data</p>
                    </div>
                </div>
            </div>
            <div class="p-4 space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Format</span>
                    <select class="border-0 bg-transparent text-sm focus:ring-0">
                        <option value="csv">CSV</option>
                        <option value="excel">Excel</option>
                        <option value="pdf">PDF</option>
                    </select>
                </div>
                <button onclick="exportReport('subscriptions')" 
                        class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors text-sm">
                    <i class="fas fa-download mr-2"></i> Download
                </button>
            </div>
        </div>

        <!-- Invoices Export -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-orange-50 to-white">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center">
                        <i class="fas fa-file-invoice text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Invoices Report</h3>
                        <p class="text-xs text-gray-500">Export invoice data</p>
                    </div>
                </div>
            </div>
            <div class="p-4 space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Format</span>
                    <select class="border-0 bg-transparent text-sm focus:ring-0">
                        <option value="csv">CSV</option>
                        <option value="excel">Excel</option>
                        <option value="pdf">PDF</option>
                    </select>
                </div>
                <button onclick="exportReport('invoices')" 
                        class="w-full px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors text-sm">
                    <i class="fas fa-download mr-2"></i> Download
                </button>
            </div>
        </div>

        <!-- Custom Export -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-lg bg-gray-100 text-gray-600 flex items-center justify-center">
                        <i class="fas fa-sliders-h text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Custom Export</h3>
                        <p class="text-xs text-gray-500">Create custom export</p>
                    </div>
                </div>
            </div>
            <div class="p-4">
                <button onclick="showCustomExport()" 
                        class="w-full px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors text-sm">
                    <i class="fas fa-cog mr-2"></i> Configure Custom Export
                </button>
            </div>
        </div>
    </div>

    <!-- ===== EXPORT HISTORY ===== -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-sm font-medium text-gray-900">Export History</h3>
            <span class="text-xs text-gray-500">Last 30 days</span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Report</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Format</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Size</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($exports as $export)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ ucfirst($export->type) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ strtoupper($export->format) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $export->created_at->format('M d, Y H:i') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $export->size ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $export->status == 'completed' ? 'bg-green-100 text-green-800' : 
                                       ($export->status == 'processing' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($export->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-1">
                                    @if($export->status == 'completed')
                                        <a href="{{ route('admin.reports.exports.download', $export) }}" 
                                           class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors" 
                                           title="Download">
                                            <i class="fas fa-download text-sm"></i>
                                        </a>
                                    @endif
                                    <button onclick="deleteExport('{{ $export->id }}')" 
                                            class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" 
                                            title="Delete">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-history text-3xl text-gray-300 mb-3 block"></i>
                                <p class="text-lg font-medium">No export history</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Custom Export Modal -->
<div x-data="{ show: false }" 
     x-show="show"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto" 
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" @click="show = false">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form action="{{ route('admin.reports.exports.generate') }}" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Custom Export</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Report Type <span class="text-red-500">*</span>
                                    </label>
                                    <select name="type" required
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <option value="users">Users</option>
                                        <option value="revenue">Revenue</option>
                                        <option value="subscriptions">Subscriptions</option>
                                        <option value="invoices">Invoices</option>
                                        <option value="websites">Websites</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Format <span class="text-red-500">*</span>
                                    </label>
                                    <select name="format" required
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <option value="csv">CSV</option>
                                        <option value="excel">Excel</option>
                                        <option value="pdf">PDF</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Date Range <span class="text-red-500">*</span>
                                    </label>
                                    <div class="grid grid-cols-2 gap-4">
                                        <input type="date" name="start_date" required
                                               class="rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <input type="date" name="end_date" required
                                               class="rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Fields
                                    </label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="fields[]" value="id" checked>
                                            <span class="ml-2 text-sm">ID</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="fields[]" value="name" checked>
                                            <span class="ml-2 text-sm">Name</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="fields[]" value="email" checked>
                                            <span class="ml-2 text-sm">Email</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="fields[]" value="status">
                                            <span class="ml-2 text-sm">Status</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="fields[]" value="created_at" checked>
                                            <span class="ml-2 text-sm">Created At</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="fields[]" value="updated_at">
                                            <span class="ml-2 text-sm">Updated At</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Generate Export
                    </button>
                    <button type="button" @click="show = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="delete-export-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
    function showCustomExport() {
        document.querySelector('[x-data]').__x.$data.show = true;
    }

    function exportReport(type) {
        const format = document.querySelector(`.bg-${type === 'users' ? 'blue' : 
                                               type === 'revenue' ? 'green' : 
                                               type === 'subscriptions' ? 'purple' : 
                                               'orange'}-600`)
                               .closest('.border')
                               .querySelector('select').value;
        window.location.href = `/admin/reports/export/${type}?format=${format}`;
    }

    function deleteExport(exportId) {
        if (confirm('Delete this export?')) {
            const form = document.getElementById('delete-export-form');
            form.action = `/admin/reports/exports/${exportId}`;
            form.submit();
        }
    }
</script>
@endpush
@endsection