{{-- resources/views/admin/notifications/announcements/index.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Announcements')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <a href="{{ route('admin.notifications.index') }}" class="text-gray-500 hover:text-gray-700">Notifications</a>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">Announcements</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- ===== HEADER ===== -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Announcements</h1>
            <p class="text-sm text-gray-500 mt-1">Create and manage announcements</p>
        </div>
        <button onclick="showCreateAnnouncement()" 
                class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
            <i class="fas fa-plus mr-2"></i> New Announcement
        </button>
    </div>

    <!-- ===== STATS ===== -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Total Announcements</p>
            <p class="text-xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Published</p>
            <p class="text-xl font-bold text-green-600">{{ $stats['published'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Draft</p>
            <p class="text-xl font-bold text-yellow-600">{{ $stats['draft'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Scheduled</p>
            <p class="text-xl font-bold text-blue-600">{{ $stats['scheduled'] ?? 0 }}</p>
        </div>
    </div>

    <!-- ===== ANNOUNCEMENTS LIST ===== -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($announcements as $announcement)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow group">
                <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-lg {{ $announcement->priority == 'high' ? 'bg-red-100 text-red-600' : 'bg-purple-100 text-purple-600' }} flex items-center justify-center">
                                <i class="fas fa-bullhorn"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900">{{ $announcement->title }}</h4>
                                <p class="text-xs text-gray-500">
                                    {{ $announcement->created_at->format('M d, Y') }}
                                </p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $announcement->status == 'published' ? 'bg-green-100 text-green-800' : 
                               ($announcement->status == 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                            {{ ucfirst($announcement->status) }}
                        </span>
                    </div>
                </div>
                
                <div class="p-4">
                    <p class="text-sm text-gray-600 line-clamp-3">{{ $announcement->content }}</p>
                    
                    <div class="mt-3 flex items-center justify-between">
                        <div class="flex items-center space-x-2 text-xs text-gray-500">
                            <span><i class="fas fa-users mr-1"></i>{{ $announcement->views ?? 0 }} views</span>
                            @if($announcement->priority == 'high')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-exclamation-circle mr-1"></i> High Priority
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center space-x-1">
                            <button onclick="editAnnouncement('{{ $announcement->id }}')" 
                                    class="p-1.5 text-gray-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" 
                                    title="Edit">
                                <i class="fas fa-pen text-sm"></i>
                            </button>
                            @if($announcement->status == 'draft')
                                <button onclick="publishAnnouncement('{{ $announcement->id }}')" 
                                        class="p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors" 
                                        title="Publish">
                                    <i class="fas fa-check text-sm"></i>
                                </button>
                            @endif
                            <button onclick="deleteAnnouncement('{{ $announcement->id }}', '{{ $announcement->title }}')" 
                                    class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" 
                                    title="Delete">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="text-center py-12 bg-white rounded-xl shadow-sm">
                    <i class="fas fa-bullhorn text-4xl text-gray-300 mb-3 block"></i>
                    <p class="text-lg font-medium text-gray-900">No announcements</p>
                    <p class="text-sm text-gray-500 mt-1">Create your first announcement</p>
                </div>
            </div>
        @endforelse
    </div>
</div>

<!-- ===== CREATE/EDIT ANNOUNCEMENT MODAL ===== -->
<div x-data="{ show: false, editing: false, announcementId: null }" 
     x-show="show"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto" 
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" @click="show = false">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <form id="announcement-form" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="announcement-modal-title">Create Announcement</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Title <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="title" id="announcement-title" required
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                           placeholder="Announcement title">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Content <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="content" id="announcement-content" rows="5" required
                                              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                              placeholder="Announcement content..."></textarea>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Priority
                                        </label>
                                        <select name="priority" id="announcement-priority"
                                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                            <option value="normal">Normal</option>
                                            <option value="high">High</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Status
                                        </label>
                                        <select name="status" id="announcement-status"
                                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                            <option value="draft">Draft</option>
                                            <option value="published">Published</option>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="send_email" value="1" checked
                                               class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                        <span class="ml-2 text-sm text-gray-700">Send email notification</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Save Announcement
                    </button>
                    <button type="button" @click="show = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ===== FORMS ===== -->
<form id="publish-announcement-form" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
</form>

<form id="delete-announcement-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
    function showCreateAnnouncement() {
        const modal = document.querySelector('[x-data]').__x.$data;
        modal.show = true;
        modal.editing = false;
        modal.announcementId = null;
        document.getElementById('announcement-modal-title').textContent = 'Create Announcement';
        document.getElementById('announcement-form').action = '{{ route("admin.notifications.announcements.store") }}';
        document.getElementById('announcement-form').querySelector('input[name="_method"]')?.remove();
        document.getElementById('announcement-title').value = '';
        document.getElementById('announcement-content').value = '';
        document.getElementById('announcement-priority').value = 'normal';
        document.getElementById('announcement-status').value = 'draft';
        document.querySelector('#announcement-form input[name="send_email"]').checked = true;
    }

    function editAnnouncement(id) {
        fetch(`/admin/notifications/announcements/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const modal = document.querySelector('[x-data]').__x.$data;
                    modal.show = true;
                    modal.editing = true;
                    modal.announcementId = id;
                    document.getElementById('announcement-modal-title').textContent = 'Edit Announcement';
                    document.getElementById('announcement-form').action = `/admin/notifications/announcements/${id}`;
                    
                    let methodInput = document.getElementById('announcement-form').querySelector('input[name="_method"]');
                    if (!methodInput) {
                        methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        document.getElementById('announcement-form').appendChild(methodInput);
                    }
                    methodInput.value = 'PUT';
                    
                    document.getElementById('announcement-title').value = data.data.title;
                    document.getElementById('announcement-content').value = data.data.content;
                    document.getElementById('announcement-priority').value = data.data.priority;
                    document.getElementById('announcement-status').value = data.data.status;
                    document.querySelector('#announcement-form input[name="send_email"]').checked = false;
                }
            });
    }

    function publishAnnouncement(id) {
        if (confirm('Publish this announcement?')) {
            const form = document.getElementById('publish-announcement-form');
            form.action = `/admin/notifications/announcements/${id}/publish`;
            form.submit();
        }
    }

    function deleteAnnouncement(id, title) {
        if (confirm(`Delete announcement "${title}"?`)) {
            const form = document.getElementById('delete-announcement-form');
            form.action = `/admin/notifications/announcements/${id}`;
            form.submit();
        }
    }
</script>
@endpush
@endsection