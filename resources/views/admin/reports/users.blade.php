{{-- resources/views/admin/reports/users.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Users Report')

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
            <span class="text-gray-500">Users</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- ===== HEADER ===== -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Users Report</h1>
            <p class="text-sm text-gray-500 mt-1">Comprehensive user analytics and statistics</p>
        </div>
        <div class="flex items-center space-x-3">
            <button onclick="exportReport('users')" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
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
                <label class="block text-sm font-medium text-gray-700 mb-1">Group By</label>
                <select name="group_by" class="rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="day" {{ request('group_by') == 'day' ? 'selected' : '' }}>Day</option>
                    <option value="week" {{ request('group_by') == 'week' ? 'selected' : '' }}>Week</option>
                    <option value="month" {{ request('group_by') == 'month' ? 'selected' : '' }}>Month</option>
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
            <p class="text-sm text-gray-500">Total Users</p>
            <p class="text-xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
            <div class="mt-1 text-xs text-green-600">
                <i class="fas fa-arrow-up mr-1"></i> +{{ $stats['growth'] ?? 0 }}%
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <p class="text-sm text-gray-500">New Users</p>
            <p class="text-xl font-bold text-blue-600">{{ $stats['new'] ?? 0 }}</p>
            <div class="mt-1 text-xs text-blue-600">
                <i class="fas fa-arrow-up mr-1"></i> +{{ $stats['new_growth'] ?? 0 }}%
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <p class="text-sm text-gray-500">Active Users</p>
            <p class="text-xl font-bold text-green-600">{{ $stats['active'] ?? 0 }}</p>
            <div class="mt-1 text-xs text-green-600">
                <i class="fas fa-arrow-up mr-1"></i> +{{ $stats['active_growth'] ?? 0 }}%
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <p class="text-sm text-gray-500">Churn Rate</p>
            <p class="text-xl font-bold text-red-600">{{ $stats['churn'] ?? 0 }}%</p>
            <div class="mt-1 text-xs text-red-600">
                <i class="fas fa-arrow-down mr-1"></i> -{{ $stats['churn_change'] ?? 0 }}%
            </div>
        </div>
    </div>

    <!-- ===== CHARTS ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-sm font-medium text-gray-900 mb-4">User Growth</h3>
            <div class="h-64">
                <canvas id="userGrowthChart"></canvas>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-sm font-medium text-gray-900 mb-4">User Distribution</h3>
            <div class="h-64">
                <canvas id="userDistributionChart"></canvas>
            </div>
        </div>
    </div>

    <!-- ===== USER TABLE ===== -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-sm font-medium text-gray-900">User Details</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Joined</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Last Active</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <x-admin.avatar :src="$user->avatar" :name="$user->name" size="sm" />
                                    <span class="ml-3 text-sm font-medium text-gray-900">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.users.show', $user) }}" class="text-gray-400 hover:text-blue-600 transition-colors">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">No users found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $users->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // User Growth Chart
        new Chart(document.getElementById('userGrowthChart'), {
            type: 'line',
            data: {
                labels: @json($chartData['labels'] ?? []),
                datasets: [{
                    label: 'New Users',
                    data: @json($chartData['new'] ?? []),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4
                }, {
                    label: 'Total Users',
                    data: @json($chartData['total'] ?? []),
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'top' } },
                scales: { y: { beginAtZero: true } }
            }
        });

        // User Distribution Chart
        new Chart(document.getElementById('userDistributionChart'), {
            type: 'doughnut',
            data: {
                labels: @json($distribution['labels'] ?? []),
                datasets: [{
                    data: @json($distribution['data'] ?? []),
                    backgroundColor: ['#6366f1', '#8b5cf6', '#3b82f6', '#22c55e', '#f59e0b', '#ef4444']
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

@push('styles')
<style>
    .card-hover {
        transition: all 0.2s ease;
    }
    .card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    }
    @media print {
        .topbar-wrapper, .sidebar, .footer-wrapper, .btn, form { display: none !important; }
        .content-wrapper { padding: 0 !important; }
    }
</style>
@endpush
@endsection