{{-- resources/views/admin/backups/index.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Backup Management')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">Backups</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- ===== HEADER ===== -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Backup Management</h1>
            <p class="text-sm text-gray-500 mt-1">Manage database and file backups</p>
        </div>
        <div class="flex items-center space-x-3">
            <button onclick="createBackup('full')" 
                    class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-database mr-2"></i> Backup Now
            </button>
            <button onclick="showScheduleModal()" 
                    class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-clock mr-2"></i> Schedule
            </button>
        </div>
    </div>

    <!-- ===== STATS CARDS ===== -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Backups</p>
                    <p class="text-xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center">
                    <i class="fas fa-archive"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Database</p>
                    <p class="text-xl font-bold text-blue-600">{{ $stats['database'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-database"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Files</p>
                    <p class="text-xl font-bold text-green-600">{{ $stats['files'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="fas fa-folder"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Size</p>
                    <p class="text-xl font-bold text-purple-600">{{ $stats['total_size'] ?? '0 MB' }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                    <i class="fas fa-hdd"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== BACKUP ACTIONS ===== -->
    <div class="bg-white rounded-xl shadow-sm p-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <button onclick="createBackup('database')" 
                    class="flex items-center justify-center px-4 py-3 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors">
                <i class="fas fa-database mr-2"></i>
                <div class="text-left">
                    <p class="text-sm font-medium">Database Backup</p>
                    <p class="text-xs text-gray-500">Backup database only</p>
                </div>
            </button>
            
            <button onclick="createBackup('files')" 
                    class="flex items-center justify-center px-4 py-3 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors">
                <i class="fas fa-folder mr-2"></i>
                <div class="text-left">
                    <p class="text-sm font-medium">Files Backup</p>
                    <p class="text-xs text-gray-500">Backup files only</p>
                </div>
            </button>
            
            <button onclick="createBackup('full')" 
                    class="flex items-center justify-center px-4 py-3 bg-primary-50 text-primary-700 rounded-lg hover:bg-primary-100 transition-colors">
                <i class="fas fa-archive mr-2"></i>
                <div class="text-left">
                    <p class="text-sm font-medium">Full Backup</p>
                    <p class="text-xs text-gray-500">Backup everything</p>
                </div>
            </button>
            
            <button onclick="showRestoreModal()" 
                    class="flex items-center justify-center px-4 py-3 bg-yellow-50 text-yellow-700 rounded-lg hover:bg-yellow-100 transition-colors">
                <i class="fas fa-undo mr-2"></i>
                <div class="text-left">
                    <p class="text-sm font-medium">Restore</p>
                    <p class="text-xs text-gray-500">Restore from backup</p>
                </div>
            </button>
        </div>
    </div>

    <!-- ===== BACKUPS LIST ===== -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-sm font-medium text-gray-900">Backup History</h3>
            <div class="flex items-center space-x-2">
                <button onclick="refreshBackups()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-sync"></i>
                </button>
                <span class="text-xs text-gray-500">{{ $backups->count() }} backups</span>
            </div>
        </div>
        
        <div class="divide-y divide-gray-200">
            @forelse($backups as $backup)
                <div class="px-6 py-4 hover:bg-gray-50 transition-colors group">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center 
                                {{ $backup->type == 'database' ? 'bg-blue-100 text-blue-600' : 
                                   ($backup->type == 'files' ? 'bg-green-100 text-green-600' : 'bg-primary-100 text-primary-600') }}">
                                <i class="fas {{ $backup->type == 'database' ? 'fa-database' : 
                                     ($backup->type == 'files' ? 'fa-folder' : 'fa-archive') }}"></i>
                            </div>
                            <div>
                                <div class="flex items-center space-x-2">
                                    <p class="text-sm font-medium text-gray-900">{{ $backup->name }}</p>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $backup->status == 'completed' ? 'bg-green-100 text-green-800' : 
                                           ($backup->status == 'processing' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($backup->status) }}
                                    </span>
                                </div>
                                <div class="flex items-center space-x-3 text-xs text-gray-500">
                                    <span>{{ ucfirst($backup->type) }}</span>
                                    <span>•</span>
                                    <span>{{ $backup->size ?? 'N/A' }}</span>
                                    <span>•</span>
                                    <span>{{ $backup->created_at->diffForHumans() }}</span>
                                    @if($backup->created_by)
                                        <span>•</span>
                                        <span>By {{ $backup->creator->name ?? 'System' }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            @if($backup->status === 'completed')
                                <a href="{{ route('admin.backups.download', $backup) }}" 
                                   class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors" 
                                   title="Download">
                                    <i class="fas fa-download text-sm"></i>
                                </a>
                                <button onclick="restoreBackup('{{ $backup->id }}')" 
                                        class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" 
                                        title="Restore">
                                    <i class="fas fa-undo text-sm"></i>
                                </button>
                            @endif
                            <button onclick="deleteBackup('{{ $backup->id }}', '{{ $backup->name }}')" 
                                    class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" 
                                    title="Delete">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>
                    </div>
                    
                    @if($backup->notes)
                        <p class="mt-1 text-xs text-gray-500">{{ $backup->notes }}</p>
                    @endif
                </div>
            @empty
                <div class="px-6 py-12 text-center">
                    <i class="fas fa-archive text-4xl text-gray-300 mb-3 block"></i>
                    <p class="text-lg font-medium text-gray-900">No backups found</p>
                    <p class="text-sm text-gray-500 mt-1">Create your first backup</p>
                </div>
            @endforelse
        </div>
        
        <!-- ===== PAGINATION ===== -->
        <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
            @if($backups instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="text-sm text-gray-500">
                    Showing {{ $backups->firstItem() ?? 0 }} to {{ $backups->lastItem() ?? 0 }} of {{ $backups->total() }} results
                </div>
                <div>
                    {{ $backups->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-sm text-gray-500">
                    Showing {{ $backups->count() }} results
                </div>
            @endif
        </div>
    </div>
</div>

<!-- ===== SCHEDULE MODAL ===== -->
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
            <form action="{{ route('admin.backups.schedule') }}" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Schedule Backup</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Frequency <span class="text-red-500">*</span>
                                    </label>
                                    <select name="frequency" required
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <option value="daily">Daily</option>
                                        <option value="weekly">Weekly</option>
                                        <option value="monthly">Monthly</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Time <span class="text-red-500">*</span>
                                    </label>
                                    <input type="time" name="time" value="02:00" required
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Type <span class="text-red-500">*</span>
                                    </label>
                                    <select name="type" required
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <option value="database">Database Only</option>
                                        <option value="files">Files Only</option>
                                        <option value="full">Full Backup</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Retention
                                    </label>
                                    <select name="retention" 
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <option value="7">7 days</option>
                                        <option value="14">14 days</option>
                                        <option value="30" selected>30 days</option>
                                        <option value="60">60 days</option>
                                        <option value="90">90 days</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Schedule Backup
                    </button>
                    <button type="button" @click="show = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ===== RESTORE MODAL ===== -->
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
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Restore Backup</h3>
                        <div class="mt-4">
                            <p class="text-sm text-gray-600">Select a backup to restore:</p>
                            <select id="restore-backup-select" 
                                    class="mt-2 w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                <option value="">Select a backup</option>
                                @foreach($backups as $backup)
                                    @if($backup->status === 'completed')
                                        <option value="{{ $backup->id }}">{{ $backup->name }} ({{ $backup->created_at->format('M d, Y H:i') }})</option>
                                    @endif
                                @endforeach
                            </select>
                            <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <p class="text-sm text-yellow-800">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    Restoring will overwrite current data. This action cannot be undone.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button onclick="confirmRestore()" 
                        class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-yellow-600 text-base font-medium text-white hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Restore Backup
                </button>
                <button type="button" @click="show = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ===== FORMS ===== -->
<form id="create-backup-form" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="type" id="backup-type">
</form>

<form id="restore-backup-form" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="backup_id" id="restore-backup-id">
</form>

<form id="delete-backup-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
    function showScheduleModal() {
        document.querySelectorAll('[x-data]')[1].__x.$data.show = true;
    }

    function showRestoreModal() {
        document.querySelectorAll('[x-data]')[2].__x.$data.show = true;
    }

    function createBackup(type) {
        if (confirm(`Create ${type} backup?`)) {
            const form = document.getElementById('create-backup-form');
            document.getElementById('backup-type').value = type;
            form.submit();
        }
    }

    function restoreBackup(backupId) {
        if (confirm('Restore this backup? This will overwrite current data.')) {
            const form = document.getElementById('restore-backup-form');
            document.getElementById('restore-backup-id').value = backupId;
            form.submit();
        }
    }

    function confirmRestore() {
        const select = document.getElementById('restore-backup-select');
        if (!select.value) {
            alert('Please select a backup to restore.');
            return;
        }
        restoreBackup(select.value);
    }

    function deleteBackup(backupId, backupName) {
        if (confirm(`Delete backup "${backupName}"?`)) {
            const form = document.getElementById('delete-backup-form');
            form.action = `/admin/backups/${backupId}`;
            form.submit();
        }
    }

    function refreshBackups() {
        window.location.reload();
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
</style>
@endpush
@endsection