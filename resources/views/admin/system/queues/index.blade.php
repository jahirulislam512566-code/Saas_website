{{-- resources/views/admin/system/queues/index.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Queue Management')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <a href="{{ route('admin.system.index') }}" class="text-gray-500 hover:text-gray-700">System</a>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">Queues</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- ===== HEADER ===== -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Queue Management</h1>
            <p class="text-sm text-gray-500 mt-1">Monitor and manage queue workers</p>
        </div>
        <div class="flex items-center space-x-3">
            <button onclick="restartQueue()" 
                    class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-sync mr-2"></i> Restart Worker
            </button>
            <a href="{{ route('admin.system.queues.monitor') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-chart-line mr-2"></i> Monitor
            </a>
        </div>
    </div>

    <!-- ===== STATS ===== -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Queue Driver</p>
                    <p class="text-xl font-bold text-gray-900">{{ $queueInfo['driver'] ?? 'sync' }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center">
                    <i class="fas fa-tasks"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Workers</p>
                    <p class="text-xl font-bold text-blue-600">{{ $queueInfo['workers'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-server"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Queue Length</p>
                    <p class="text-xl font-bold text-yellow-600">{{ $queueInfo['length'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center">
                    <i class="fas fa-list"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    <p class="text-xl font-bold text-green-600">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                            Running
                        </span>
                    </p>
                </div>
                <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== QUEUE WORKERS ===== -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($queues as $queue)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-lg bg-primary-100 text-primary-600 flex items-center justify-center">
                                <i class="fas fa-{{ $queue['icon'] ?? 'tasks' }}"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900">{{ ucfirst($queue['name']) }}</h4>
                                <p class="text-xs text-gray-500">{{ $queue['driver'] ?? 'default' }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $queue['status'] == 'running' ? 'bg-green-100 text-green-800' : 
                               ($queue['status'] == 'paused' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                            <span class="w-1.5 h-1.5 rounded-full mr-1.5 
                                {{ $queue['status'] == 'running' ? 'bg-green-500' : 
                                   ($queue['status'] == 'paused' ? 'bg-yellow-500' : 'bg-gray-400') }}"></span>
                            {{ ucfirst($queue['status']) }}
                        </span>
                    </div>
                </div>
                <div class="p-4 space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Jobs Processed</span>
                        <span class="text-gray-900">{{ $queue['processed'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Failed Jobs</span>
                        <span class="text-gray-900 text-red-600">{{ $queue['failed'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Memory Usage</span>
                        <span class="text-gray-900">{{ $queue['memory'] ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Uptime</span>
                        <span class="text-gray-900">{{ $queue['uptime'] ?? 'N/A' }}</span>
                    </div>
                    <div class="mt-3 flex space-x-2">
                        @if($queue['status'] == 'running')
                            <button onclick="pauseQueue('{{ $queue['name'] }}')" 
                                    class="flex-1 px-3 py-1.5 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors text-sm">
                                <i class="fas fa-pause mr-1"></i> Pause
                            </button>
                        @elseif($queue['status'] == 'paused')
                            <button onclick="resumeQueue('{{ $queue['name'] }}')" 
                                    class="flex-1 px-3 py-1.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                                <i class="fas fa-play mr-1"></i> Resume
                            </button>
                        @endif
                        <button onclick="restartQueue('{{ $queue['name'] }}')" 
                                class="flex-1 px-3 py-1.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors text-sm">
                            <i class="fas fa-sync mr-1"></i> Restart
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="text-center py-12 bg-white rounded-xl shadow-sm">
                    <i class="fas fa-tasks text-4xl text-gray-300 mb-3 block"></i>
                    <p class="text-lg font-medium text-gray-900">No queues configured</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- ===== QUEUE METRICS ===== -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-sm font-medium text-gray-900 mb-4">Queue Metrics</h3>
        <div class="h-64">
            <canvas id="queueMetricsChart"></canvas>
        </div>
    </div>
</div>

<!-- ===== FORMS ===== -->
<form id="queue-action-form" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="queue" id="queue-name">
    <input type="hidden" name="action" id="queue-action">
</form>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Queue Metrics Chart
        new Chart(document.getElementById('queueMetricsChart'), {
            type: 'line',
            data: {
                labels: @json($chartData['labels'] ?? []),
                datasets: [
                    {
                        label: 'Jobs Processed',
                        data: @json($chartData['processed'] ?? []),
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Failed Jobs',
                        data: @json($chartData['failed'] ?? []),
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        fill: true,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'top' } },
                scales: { y: { beginAtZero: true } }
            }
        });
    });

    function pauseQueue(queue) {
        if (confirm(`Pause ${queue} queue?`)) {
            const form = document.getElementById('queue-action-form');
            document.getElementById('queue-name').value = queue;
            document.getElementById('queue-action').value = 'pause';
            form.action = `/admin/system/queues/pause`;
            form.submit();
        }
    }

    function resumeQueue(queue) {
        if (confirm(`Resume ${queue} queue?`)) {
            const form = document.getElementById('queue-action-form');
            document.getElementById('queue-name').value = queue;
            document.getElementById('queue-action').value = 'resume';
            form.action = `/admin/system/queues/resume`;
            form.submit();
        }
    }

    function restartQueue(queue) {
        if (confirm(`Restart ${queue} queue worker?`)) {
            const form = document.getElementById('queue-action-form');
            document.getElementById('queue-name').value = queue || 'default';
            document.getElementById('queue-action').value = 'restart';
            form.action = `/admin/system/queues/restart`;
            form.submit();
        }
    }
</script>
@endpush
@endsection