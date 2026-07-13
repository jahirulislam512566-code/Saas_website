{{-- resources/views/admin/system/jobs/index.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Jobs Management')

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
            <span class="text-gray-500">Jobs</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- ===== HEADER ===== -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Jobs Management</h1>
            <p class="text-sm text-gray-500 mt-1">Monitor and manage background jobs</p>
        </div>
        <div class="flex items-center space-x-3">
            <button onclick="retryFailedJobs()" 
                    class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                <i class="fas fa-redo mr-2"></i> Retry Failed
            </button>
            <button onclick="flushJobs()" 
                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                <i class="fas fa-trash mr-2"></i> Flush All
            </button>
        </div>
    </div>

    <!-- ===== STATS ===== -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Pending</p>
                    <p class="text-xl font-bold text-gray-900">{{ $stats['pending'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Processing</p>
                    <p class="text-xl font-bold text-blue-600">{{ $stats['processing'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-spinner"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Completed</p>
                    <p class="text-xl font-bold text-green-600">{{ $stats['completed'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Failed</p>
                    <p class="text-xl font-bold text-red-600">{{ $stats['failed'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total</p>
                    <p class="text-xl font-bold text-purple-600">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                    <i class="fas fa-list"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== FILTERS ===== -->
    <div class="bg-white rounded-xl shadow-sm p-4">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Queue</label>
                <select name="queue" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">All Queues</option>
                    <option value="default" {{ request('queue') == 'default' ? 'selected' : '' }}>Default</option>
                    <option value="high" {{ request('queue') == 'high' ? 'selected' : '' }}>High Priority</option>
                    <option value="low" {{ request('queue') == 'low' ? 'selected' : '' }}>Low Priority</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" placeholder="Search jobs..." value="{{ request('search') }}" 
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    <i class="fas fa-search mr-2"></i> Filter
                </button>
                <a href="{{ route('admin.system.jobs') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-times mr-2"></i> Clear
                </a>
            </div>
        </form>
    </div>

    <!-- ===== JOBS TABLE ===== -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Job</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Queue</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Attempts</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($jobs as $job)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ class_basename($job->payload['displayName'] ?? $job->payload['job'] ?? 'Unknown') }}</p>
                                    <p class="text-xs text-gray-500">ID: {{ $job->id }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $job->queue }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'processing' => 'bg-blue-100 text-blue-800',
                                        'completed' => 'bg-green-100 text-green-800',
                                        'failed' => 'bg-red-100 text-red-800',
                                    ];
                                    $color = $statusColors[$job->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                    {{ ucfirst($job->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $job->attempts ?? 0 }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <div>{{ $job->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-400">{{ $job->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-1">
                                    @if($job->status === 'failed')
                                        <button onclick="retryJob('{{ $job->id }}')" 
                                                class="p-2 text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors" 
                                                title="Retry">
                                            <i class="fas fa-redo text-sm"></i>
                                        </button>
                                    @endif
                                    @if(in_array($job->status, ['pending', 'processing']))
                                        <button onclick="cancelJob('{{ $job->id }}')" 
                                                class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" 
                                                title="Cancel">
                                            <i class="fas fa-times text-sm"></i>
                                        </button>
                                    @endif
                                    @if($job->status === 'completed')
                                        <button onclick="viewJobDetails('{{ $job->id }}')" 
                                                class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" 
                                                title="View Details">
                                            <i class="fas fa-eye text-sm"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-tasks text-4xl text-gray-300 mb-3 block"></i>
                                <p class="text-lg font-medium">No jobs found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $jobs->links() }}
        </div>
    </div>
</div>

<!-- ===== FORMS ===== -->
<form id="retry-job-form" method="POST" style="display: none;">
    @csrf
</form>

<form id="cancel-job-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
    function retryJob(jobId) {
        if (confirm('Retry this job?')) {
            const form = document.getElementById('retry-job-form');
            form.action = `/admin/system/jobs/${jobId}/retry`;
            form.submit();
        }
    }

    function retryFailedJobs() {
        if (confirm('Retry all failed jobs?')) {
            const form = document.getElementById('retry-job-form');
            form.action = `/admin/system/jobs/retry-all`;
            form.submit();
        }
    }

    function cancelJob(jobId) {
        if (confirm('Cancel this job?')) {
            const form = document.getElementById('cancel-job-form');
            form.action = `/admin/system/jobs/${jobId}`;
            form.submit();
        }
    }

    function flushJobs() {
        if (confirm('Flush all jobs? This action cannot be undone.')) {
            const form = document.getElementById('cancel-job-form');
            form.action = `/admin/system/jobs/flush`;
            form.submit();
        }
    }

    function viewJobDetails(jobId) {
        // Implement job details modal
        alert('Job details for ID: ' + jobId);
    }
</script>
@endpush
@endsection