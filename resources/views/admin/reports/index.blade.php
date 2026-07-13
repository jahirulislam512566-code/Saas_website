{{-- resources/views/admin/reports/index.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Reports & Analytics')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-slate-400 mx-2 text-sm"></i>
            <span class="text-slate-500">Reports</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Reports & Analytics</h1>
            <p class="text-sm text-slate-500 mt-1">View and generate reports for your application</p>
        </div>
        <div class="flex items-center space-x-3">
            <button onclick="exportReport()" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm font-medium">
                <i class="fas fa-file-export mr-2"></i> Export Report
            </button>
            <button onclick="scheduleReport()" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                <i class="fas fa-clock mr-2"></i> Schedule Report
            </button>
        </div>
    </div>

    <!-- Report Types -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 cursor-pointer" onclick="loadReport('sales')">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-chart-line text-lg"></i>
                </div>
                <span class="text-xs text-slate-400">New</span>
            </div>
            <h3 class="text-sm font-semibold text-slate-900">Sales Report</h3>
            <p class="text-xs text-slate-500 mt-1">Revenue, orders, and sales trends</p>
            <div class="mt-3 flex items-center text-xs text-slate-400">
                <i class="fas fa-calendar-alt mr-1"></i>
                <span>Last 30 days</span>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 cursor-pointer" onclick="loadReport('subscriptions')">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                    <i class="fas fa-crown text-lg"></i>
                </div>
                <span class="text-xs text-slate-400">Popular</span>
            </div>
            <h3 class="text-sm font-semibold text-slate-900">Subscriptions Report</h3>
            <p class="text-xs text-slate-500 mt-1">Active, churn, and subscription metrics</p>
            <div class="mt-3 flex items-center text-xs text-slate-400">
                <i class="fas fa-calendar-alt mr-1"></i>
                <span>Last 30 days</span>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 cursor-pointer" onclick="loadReport('users')">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                    <i class="fas fa-users text-lg"></i>
                </div>
            </div>
            <h3 class="text-sm font-semibold text-slate-900">Users Report</h3>
            <p class="text-xs text-slate-500 mt-1">User growth, activity, and demographics</p>
            <div class="mt-3 flex items-center text-xs text-slate-400">
                <i class="fas fa-calendar-alt mr-1"></i>
                <span>Last 30 days</span>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 cursor-pointer" onclick="loadReport('revenue')">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-lg"></i>
                </div>
                <span class="text-xs text-slate-400">New</span>
            </div>
            <h3 class="text-sm font-semibold text-slate-900">Revenue Report</h3>
            <p class="text-xs text-slate-500 mt-1">MRR, ARR, and revenue breakdown</p>
            <div class="mt-3 flex items-center text-xs text-slate-400">
                <i class="fas fa-calendar-alt mr-1"></i>
                <span>Last 30 days</span>
            </div>
        </div>
    </div>

    <!-- Report Content -->
    <div id="report-content" class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 id="report-title" class="text-lg font-semibold text-slate-900">Sales Report</h2>
                <p id="report-subtitle" class="text-sm text-slate-500">Overview of your sales performance</p>
            </div>
            <div class="flex items-center space-x-2">
                <button onclick="refreshReport()" class="p-2 text-slate-400 hover:text-indigo-600 rounded-lg hover:bg-indigo-50 transition-colors">
                    <i class="fas fa-sync-alt"></i>
                </button>
                <button onclick="downloadReport()" class="p-2 text-slate-400 hover:text-emerald-600 rounded-lg hover:bg-emerald-50 transition-colors">
                    <i class="fas fa-download"></i>
                </button>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-slate-50 rounded-lg p-4">
                <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Total Revenue</p>
                <p class="text-2xl font-bold text-slate-900 mt-1" id="stat-total-revenue">$12,458</p>
                <p class="text-xs text-emerald-600 mt-1">
                    <i class="fas fa-arrow-up mr-1"></i> 12.5%
                </p>
            </div>
            <div class="bg-slate-50 rounded-lg p-4">
                <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Total Orders</p>
                <p class="text-2xl font-bold text-slate-900 mt-1" id="stat-total-orders">1,247</p>
                <p class="text-xs text-emerald-600 mt-1">
                    <i class="fas fa-arrow-up mr-1"></i> 8.3%
                </p>
            </div>
            <div class="bg-slate-50 rounded-lg p-4">
                <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Average Order Value</p>
                <p class="text-2xl font-bold text-slate-900 mt-1" id="stat-avg-order">$248</p>
                <p class="text-xs text-red-600 mt-1">
                    <i class="fas fa-arrow-down mr-1"></i> 2.1%
                </p>
            </div>
            <div class="bg-slate-50 rounded-lg p-4">
                <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Conversion Rate</p>
                <p class="text-2xl font-bold text-slate-900 mt-1" id="stat-conversion">3.8%</p>
                <p class="text-xs text-emerald-600 mt-1">
                    <i class="fas fa-arrow-up mr-1"></i> 0.5%
                </p>
            </div>
        </div>

        <!-- Chart Placeholder -->
        <div class="bg-slate-50 rounded-lg p-8 mb-6 text-center">
            <div class="flex items-center justify-center">
                <div class="w-full max-w-4xl h-64 bg-white rounded-lg shadow-sm flex items-center justify-center">
                    <div class="text-center">
                        <i class="fas fa-chart-bar text-4xl text-slate-300 mb-3 block"></i>
                        <p class="text-sm text-slate-500">Chart will be displayed here</p>
                        <p class="text-xs text-slate-400">(Chart.js integration coming soon)</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Orders</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Revenue</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Avg Order</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Conversion</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @for($i = 0; $i < 7; $i++)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-3 text-sm text-slate-600">{{ now()->subDays($i)->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-sm text-slate-600">{{ rand(10, 50) }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-slate-900">${{ number_format(rand(500, 5000), 2) }}</td>
                        <td class="px-4 py-3 text-sm text-slate-600">${{ number_format(rand(50, 500), 2) }}</td>
                        <td class="px-4 py-3 text-sm text-slate-600">{{ number_format(rand(1, 10) / 10, 1) }}%</td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>

    <!-- Scheduled Reports -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">Scheduled Reports</h2>
                <p class="text-sm text-slate-500">Reports that are automatically generated and sent</p>
            </div>
            <button onclick="showScheduleModal()" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                <i class="fas fa-plus mr-1"></i> Add Schedule
            </button>
        </div>

        <div class="space-y-3">
            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                        <i class="fas fa-file-alt text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-900">Weekly Sales Report</p>
                        <p class="text-xs text-slate-500">Every Monday at 9:00 AM</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                        <span class="w-1 h-1 rounded-full bg-emerald-500 mr-1"></span>
                        Active
                    </span>
                    <button class="p-1 text-slate-400 hover:text-red-600 transition-colors">
                        <i class="fas fa-trash text-sm"></i>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                        <i class="fas fa-crown text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-900">Monthly Subscription Report</p>
                        <p class="text-xs text-slate-500">Every 1st at 8:00 AM</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                        <span class="w-1 h-1 rounded-full bg-emerald-500 mr-1"></span>
                        Active
                    </span>
                    <button class="p-1 text-slate-400 hover:text-red-600 transition-colors">
                        <i class="fas fa-trash text-sm"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Schedule Report Modal -->
<div id="schedule-modal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="hideScheduleModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-slate-900">Schedule Report</h3>
                <button onclick="hideScheduleModal()" class="text-slate-400 hover:text-slate-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form onsubmit="saveSchedule(event)">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Report Type</label>
                        <select class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option>Sales Report</option>
                            <option>Subscriptions Report</option>
                            <option>Users Report</option>
                            <option>Revenue Report</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Frequency</label>
                        <select class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option>Daily</option>
                            <option>Weekly</option>
                            <option selected>Monthly</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Recipients</label>
                        <input type="text" placeholder="Enter email addresses (comma separated)" 
                               class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Format</label>
                        <div class="flex space-x-4">
                            <label class="flex items-center">
                                <input type="radio" name="format" value="pdf" checked class="mr-2">
                                <span class="text-sm text-slate-600">PDF</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="format" value="csv" class="mr-2">
                                <span class="text-sm text-slate-600">CSV</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="format" value="excel" class="mr-2">
                                <span class="text-sm text-slate-600">Excel</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-2 mt-6">
                    <button type="button" onclick="hideScheduleModal()" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors text-sm font-medium">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                        <i class="fas fa-clock mr-1"></i> Schedule
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function loadReport(type) {
        // Update title and subtitle
        const titles = {
            'sales': { title: 'Sales Report', subtitle: 'Overview of your sales performance' },
            'subscriptions': { title: 'Subscriptions Report', subtitle: 'Subscription metrics and analytics' },
            'users': { title: 'Users Report', subtitle: 'User growth and activity overview' },
            'revenue': { title: 'Revenue Report', subtitle: 'Revenue breakdown and trends' }
        };
        
        const data = titles[type] || titles['sales'];
        document.getElementById('report-title').textContent = data.title;
        document.getElementById('report-subtitle').textContent = data.subtitle;
        
        // Update stats with random data
        document.getElementById('stat-total-revenue').textContent = '$' + (Math.random() * 20000 + 5000).toFixed(0);
        document.getElementById('stat-total-orders').textContent = Math.floor(Math.random() * 2000 + 500);
        document.getElementById('stat-avg-order').textContent = '$' + (Math.random() * 500 + 100).toFixed(0);
        document.getElementById('stat-conversion').textContent = (Math.random() * 5 + 1).toFixed(1) + '%';
        
        // Show toast notification
        showToast('Loading ' + data.title + '...', 'info');
    }

    function refreshReport() {
        showToast('Refreshing report data...', 'info');
        setTimeout(() => {
            showToast('Report refreshed successfully!', 'success');
        }, 1000);
    }

    function downloadReport() {
        showToast('Downloading report...', 'info');
        setTimeout(() => {
            showToast('Report downloaded successfully!', 'success');
        }, 1500);
    }

    function exportReport() {
        showToast('Preparing export...', 'info');
        setTimeout(() => {
            showToast('Report exported successfully!', 'success');
        }, 1500);
    }

    function scheduleReport() {
        showScheduleModal();
    }

    function showScheduleModal() {
        document.getElementById('schedule-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function hideScheduleModal() {
        document.getElementById('schedule-modal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function saveSchedule(event) {
        event.preventDefault();
        showToast('Report scheduled successfully!', 'success');
        hideScheduleModal();
    }

    // Close modal on escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            hideScheduleModal();
        }
    });
</script>
@endpush