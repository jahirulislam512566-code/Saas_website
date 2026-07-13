{{-- resources/views/admin/media/folders.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Media Folders')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <a href="{{ route('admin.media.library') }}" class="text-gray-500 hover:text-gray-700">Media</a>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">Folders</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- ===== HEADER ===== -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Media Folders</h1>
            <p class="text-sm text-gray-500 mt-1">Organize your media files into folders</p>
        </div>
        <button onclick="showAddFolder()" 
                class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
            <i class="fas fa-folder-plus mr-2"></i> New Folder
        </button>
    </div>

    <!-- ===== STATS ===== -->
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Total Folders</p>
            <p class="text-xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Total Files</p>
            <p class="text-xl font-bold text-blue-600">{{ $stats['files'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Storage Used</p>
            <p class="text-xl font-bold text-green-600">{{ $stats['storage'] ?? '0 MB' }}</p>
        </div>
    </div>

    <!-- ===== FOLDERS GRID ===== -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Root Folder -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow group">
            <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-lg bg-primary-100 text-primary-600 flex items-center justify-center">
                            <i class="fas fa-folder-open"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900">Root</h4>
                            <p class="text-xs text-gray-500">{{ $rootFiles ?? 0 }} files</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.media.library') }}" class="text-gray-400 hover:text-primary-600 transition-colors">
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        @forelse($folders as $folder)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow group">
                <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-lg bg-primary-100 text-primary-600 flex items-center justify-center">
                                <i class="fas fa-folder"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900">{{ $folder->name }}</h4>
                                <p class="text-xs text-gray-500">{{ $folder->files_count ?? 0 }} files</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-1">
                            <a href="{{ route('admin.media.library', ['folder' => $folder->id]) }}" 
                               class="p-1.5 text-gray-400 hover:text-blue-600 transition-colors rounded-lg hover:bg-blue-50" 
                               title="View Files">
                                <i class="fas fa-eye text-sm"></i>
                            </a>
                            <button onclick="editFolder('{{ $folder->id }}')" 
                                    class="p-1.5 text-gray-400 hover:text-primary-600 transition-colors rounded-lg hover:bg-primary-50" 
                                    title="Edit">
                                <i class="fas fa-pen text-sm"></i>
                            </button>
                            <button onclick="deleteFolder('{{ $folder->id }}', '{{ $folder->name }}')" 
                                    class="p-1.5 text-gray-400 hover:text-red-600 transition-colors rounded-lg hover:bg-red-50" 
                                    title="Delete">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                @if($folder->description)
                    <div class="p-3">
                        <p class="text-sm text-gray-600">{{ Str::limit($folder->description, 100) }}</p>
                    </div>
                @endif
            </div>
        @empty
            <div class="col-span-full">
                <div class="text-center py-12 bg-white rounded-xl shadow-sm">
                    <i class="fas fa-folder-open text-4xl text-gray-300 mb-3 block"></i>
                    <p class="text-lg font-medium text-gray-900">No folders created yet</p>
                    <p class="text-sm text-gray-500 mt-1">Create your first folder to organize your media</p>
                </div>
            </div>
        @endforelse
    </div>
</div>

<!-- Add/Edit Folder Modal -->
<div x-data="{ show: false, editing: false, folderId: null }" 
     x-show="show"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto" 
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" @click="show = false">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="folder-form" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="folder-modal-title">New Folder</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Folder Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="name" id="folder-name" required
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                           placeholder="e.g., Blog Images">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Description
                                    </label>
                                    <textarea name="description" id="folder-description" rows="2"
                                              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                              placeholder="Optional description"></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Parent Folder
                                    </label>
                                    <select name="parent_id" id="folder-parent" 
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <option value="">Root</option>
                                        @foreach($folders as $folder)
                                            <option value="{{ $folder->id }}">{{ $folder->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Save Folder
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
<form id="folder-delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
    function showAddFolder() {
        const modal = document.querySelector('[x-data]').__x.$data;
        modal.show = true;
        modal.editing = false;
        modal.folderId = null;
        document.getElementById('folder-modal-title').textContent = 'New Folder';
        document.getElementById('folder-form').action = '{{ route("admin.media.folders.store") }}';
        document.getElementById('folder-form').querySelector('input[name="_method"]')?.remove();
        document.getElementById('folder-name').value = '';
        document.getElementById('folder-description').value = '';
        document.getElementById('folder-parent').value = '';
    }

    function editFolder(folderId) {
        fetch(`/admin/media/folders/${folderId}/edit`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const modal = document.querySelector('[x-data]').__x.$data;
                    modal.show = true;
                    modal.editing = true;
                    modal.folderId = folderId;
                    document.getElementById('folder-modal-title').textContent = 'Edit Folder';
                    document.getElementById('folder-form').action = `/admin/media/folders/${folderId}`;
                    
                    let methodInput = document.getElementById('folder-form').querySelector('input[name="_method"]');
                    if (!methodInput) {
                        methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        document.getElementById('folder-form').appendChild(methodInput);
                    }
                    methodInput.value = 'PUT';
                    
                    document.getElementById('folder-name').value = data.data.name;
                    document.getElementById('folder-description').value = data.data.description || '';
                    document.getElementById('folder-parent').value = data.data.parent_id || '';
                }
            });
    }

    function deleteFolder(folderId, folderName) {
        if (confirm(`Delete folder "${folderName}"? All files inside will be moved to root.`)) {
            const form = document.getElementById('folder-delete-form');
            form.action = `/admin/media/folders/${folderId}`;
            form.submit();
        }
    }
</script>
@endpush
@endsection