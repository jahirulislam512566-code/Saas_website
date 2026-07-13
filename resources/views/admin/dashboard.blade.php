@extends('admin.layouts.admin')

@section('title', 'Dashboard')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">Dashboard</span>
        </div>
    </li>
@endsection

@section('content')
<div x-data="dashboardComponent()" x-init="initDashboard()" class="space-y-6">
    <!-- Welcome Message -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    Welcome back, {{ auth()->user()->name ?? 'Admin' }}!
                </h2>
                <p class="text-gray-500 mt-1">Here's what's happening with your application today.</p>
            </div>
            <div class="flex space-x-2">
                <span class="text-sm text-gray-500">
                    <i class="far fa-calendar-alt mr-1"></i>
                    {{ now()->format('l, F j, Y') }}
                </span>
                <span class="text-sm text-gray-500">
                    <i class="far fa-clock mr-1"></i>
                    {{ now()->format('g:i A') }}
                </span>
            </div>
        </div>
    </div>
    
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" id="statsGrid">
        @if(isset($stats) && count($stats) > 0)
            @foreach($stats as $stat)
                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 {{ $stat['border_color'] ?? 'border-indigo-500' }}">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">{{ $stat['label'] ?? 'Stat' }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stat['value'] ?? 0 }}</p>
                            @if(isset($stat['change']))
                                <p class="text-xs {{ $stat['change'] >= 0 ? 'text-green-500' : 'text-red-500' }}">
                                    {{ $stat['change'] >= 0 ? '+' : '' }}{{ $stat['change'] }}%
                                    <span class="text-gray-400">from last month</span>
                                </p>
                            @endif
                        </div>
                        <div class="w-12 h-12 rounded-full {{ $stat['icon_bg'] ?? 'bg-indigo-100' }} {{ $stat['icon_color'] ?? 'text-indigo-600' }} flex items-center justify-center">
                            <i class="fas {{ $stat['icon'] ?? 'fa-dollar-sign' }} text-xl"></i>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <!-- Default stats -->
            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-indigo-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Revenue</p>
                        <p class="text-2xl font-bold text-gray-900">$0.00</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Active Subscriptions</p>
                        <p class="text-2xl font-bold text-gray-900">0</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Users</p>
                        <p class="text-2xl font-bold text-gray-900">0</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                        <i class="fas fa-user text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Open Tickets</p>
                        <p class="text-2xl font-bold text-gray-900">0</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center">
                        <i class="fas fa-headset text-xl"></i>
                    </div>
                </div>
            </div>
        @endif
    </div>
    
    <!-- Top Widgets Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6" id="topWidgets">
        @if(isset($topWidgets) && count($topWidgets) > 0)
            @foreach($topWidgets as $widget)
                <div class="bg-white rounded-xl shadow-sm p-6 {{ $widget['class'] ?? '' }}">
                    {!! $widget['content'] ?? '' !!}
                </div>
            @endforeach
        @else
            <!-- Revenue Chart -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Revenue Overview</h3>
                <div class="h-80">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
            
            <!-- Recent Activity -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h3>
                <div class="space-y-4" id="recentActivity">
                    <p class="text-gray-500 text-center py-4">No recent activity</p>
                </div>
            </div>
        @endif
    </div>
    
    <!-- Bottom Widgets -->
    <div class="grid grid-cols-1 gap-6" id="bottomWidgets">
        @if(isset($bottomWidgets) && count($bottomWidgets) > 0)
            @foreach($bottomWidgets as $widget)
                <div class="bg-white rounded-xl shadow-sm p-6 {{ $widget['class'] ?? '' }}">
                    {!! $widget['content'] ?? '' !!}
                </div>
            @endforeach
        @else
            <!-- Recent Subscriptions -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Subscriptions</h3>
                    <a href="{{ route('admin.subscriptions.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">No recent subscriptions</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
    
    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('admin.users.create') }}" class="flex items-center justify-center p-4 border border-gray-200 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition-colors group">
                <i class="fas fa-user-plus text-indigo-600 mr-2 group-hover:scale-110 transition-transform"></i>
                <span class="text-sm font-medium text-gray-700">Add User</span>
            </a>
            <a href="{{ route('admin.support.create') }}" class="flex items-center justify-center p-4 border border-gray-200 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition-colors group">
                <i class="fas fa-ticket-alt text-indigo-600 mr-2 group-hover:scale-110 transition-transform"></i>
                <span class="text-sm font-medium text-gray-700">New Ticket</span>
            </a>
            <a href="{{ route('admin.subscriptions.create') }}" class="flex items-center justify-center p-4 border border-gray-200 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition-colors group">
                <i class="fas fa-plus-circle text-indigo-600 mr-2 group-hover:scale-110 transition-transform"></i>
                <span class="text-sm font-medium text-gray-700">New Subscription</span>
            </a>
            <a href="{{ route('admin.media.upload') }}" class="flex items-center justify-center p-4 border border-gray-200 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition-colors group">
                <i class="fas fa-upload text-indigo-600 mr-2 group-hover:scale-110 transition-transform"></i>
                <span class="text-sm font-medium text-gray-700">Upload Media</span>
            </a>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="dashboardModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 id="modalTitle" class="text-lg font-semibold text-gray-900">Modal Title</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="modalContent" class="mt-2"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Load Chart.js dynamically
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
        script.onload = function() {
            initChart();
        };
        document.head.appendChild(script);
    });

    function initChart() {
        const revenueChartElement = document.getElementById('revenueChart');
        if (revenueChartElement && typeof Chart !== 'undefined') {
            const ctx = revenueChartElement.getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartLabels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']) !!},
                    datasets: [{
                        label: 'Revenue',
                        data: {!! json_encode($chartData ?? [0, 0, 0, 0, 0, 0]) !!},
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
                        legend: {
                            display: false
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

    function loadDashboardWidgets() {
        // Load widgets dynamically via AJAX if needed
        // fetch('/admin/dashboard/widgets')
        //     .then(response => response.json())
        //     .then(data => {
        //         updateWidgets(data);
        //     })
        //     .catch(error => console.error('Error loading widgets:', error));
    }

    function updateWidgets(data) {
        if (data.stats) {
            const statsGrid = document.getElementById('statsGrid');
            // Update stats dynamically
        }
        if (data.topWidgets) {
            document.getElementById('topWidgets').innerHTML = data.topWidgets;
        }
        if (data.bottomWidgets) {
            document.getElementById('bottomWidgets').innerHTML = data.bottomWidgets;
        }
    }

    function openModal(title, content) {
        document.getElementById('modalTitle').textContent = title;
        document.getElementById('modalContent').innerHTML = content;
        document.getElementById('dashboardModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('dashboardModal').classList.add('hidden');
    }

    window.onclick = function(event) {
        const modal = document.getElementById('dashboardModal');
        if (event.target == modal) {
            closeModal();
        }
    }
</script>
@endpush