{{-- resources/views/admin/media/upload.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Upload Media')

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
            <span class="text-gray-500">Upload</span>
        </div>
    </li>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- ===== HEADER ===== -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Upload Media</h1>
            <p class="text-sm text-gray-500 mt-1">Upload files to your media library</p>
        </div>
        <a href="{{ route('admin.media.library') }}" class="text-gray-500 hover:text-gray-700">
            <i class="fas fa-arrow-left mr-2"></i> Back to Library
        </a>
    </div>

    <!-- ===== UPLOAD AREA ===== -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="p-6">
            <!-- Dropzone Upload Area -->
            <div id="dropzone" 
                 class="border-2 border-dashed border-gray-300 rounded-xl p-12 text-center hover:border-primary-500 transition-colors cursor-pointer">
                <i class="fas fa-cloud-upload-alt text-5xl text-gray-400 mb-4"></i>
                <p class="text-lg font-medium text-gray-700">Drop files here or click to upload</p>
                <p class="text-sm text-gray-500 mt-1">Supported files: Images, Videos, Documents, Audio</p>
                <p class="text-xs text-gray-400 mt-2">Maximum file size: {{ ini_get('upload_max_filesize') }}</p>
                <input type="file" id="fileInput" multiple class="hidden" accept="image/*,video/*,audio/*,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.rar">
            </div>

            <!-- Upload Progress -->
            <div id="upload-progress" class="mt-6 hidden">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">Uploading...</span>
                    <span class="text-sm text-gray-500" id="progress-percentage">0%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div id="progress-bar" class="bg-primary-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                </div>
                <p class="text-xs text-gray-400 mt-2" id="upload-status">Preparing upload...</p>
            </div>

            <!-- Uploaded Files List -->
            <div id="uploaded-files" class="mt-6 space-y-2 hidden">
                <h4 class="text-sm font-medium text-gray-900">Uploaded Files</h4>
                <div id="files-list" class="space-y-2"></div>
            </div>
        </div>
    </div>

    <!-- ===== UPLOAD OPTIONS ===== -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-sm font-medium text-gray-900 mb-4">Upload Options</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Folder
                </label>
                <select id="folder-select" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">Root</option>
                    @foreach($folders ?? [] as $folder)
                        <option value="{{ $folder->id }}">{{ $folder->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Visibility
                </label>
                <select id="visibility-select" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="public">Public</option>
                    <option value="private">Private</option>
                </select>
            </div>
        </div>
    </div>

    <!-- ===== RECENT UPLOADS ===== -->
    @if(isset($recentUploads) && $recentUploads->count() > 0)
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-sm font-medium text-gray-900">Recent Uploads</h3>
            </div>
            <div class="divide-y divide-gray-200">
                @foreach($recentUploads as $file)
                    <div class="px-6 py-3 flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-lg bg-primary-100 text-primary-600 flex items-center justify-center">
                                <i class="fas {{ $file->is_image ? 'fa-image' : ($file->is_video ? 'fa-video' : 'fa-file') }}"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $file->name }}</p>
                                <p class="text-xs text-gray-500">{{ $file->formatted_size }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <a href="{{ $file->url }}" target="_blank" class="text-gray-400 hover:text-blue-600 transition-colors">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.media.library') }}" class="text-gray-400 hover:text-primary-600 transition-colors">
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('fileInput');
        const progressDiv = document.getElementById('upload-progress');
        const progressBar = document.getElementById('progress-bar');
        const progressPercentage = document.getElementById('progress-percentage');
        const uploadStatus = document.getElementById('upload-status');
        const uploadedFilesDiv = document.getElementById('uploaded-files');
        const filesList = document.getElementById('files-list');
        const folderSelect = document.getElementById('folder-select');
        const visibilitySelect = document.getElementById('visibility-select');

        // Click to upload
        dropzone.addEventListener('click', () => fileInput.click());

        // Drag and drop
        dropzone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropzone.classList.add('border-primary-500', 'bg-primary-50');
        });

        dropzone.addEventListener('dragleave', () => {
            dropzone.classList.remove('border-primary-500', 'bg-primary-50');
        });

        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzone.classList.remove('border-primary-500', 'bg-primary-50');
            handleFiles(e.dataTransfer.files);
        });

        // File input change
        fileInput.addEventListener('change', (e) => {
            handleFiles(e.target.files);
        });

        // Handle files upload
        async function handleFiles(files) {
            if (files.length === 0) return;

            progressDiv.classList.remove('hidden');
            uploadedFilesDiv.classList.remove('hidden');
            filesList.innerHTML = '';

            const totalFiles = files.length;
            let uploaded = 0;

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const formData = new FormData();
                formData.append('file', file);
                formData.append('folder_id', folderSelect.value);
                formData.append('visibility', visibilitySelect.value);

                try {
                    const response = await fetch('{{ route("admin.media.upload.store") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (data.success) {
                        uploaded++;
                        addFileToList(file.name, data.data.url);
                        updateProgress(uploaded, totalFiles);
                    } else {
                        addErrorToList(file.name, data.message || 'Upload failed');
                    }
                } catch (error) {
                    addErrorToList(file.name, 'Network error');
                }
            }

            if (uploaded === totalFiles) {
                uploadStatus.textContent = 'All files uploaded successfully!';
                uploadStatus.className = 'text-xs text-green-600 mt-2';
            } else {
                uploadStatus.textContent = `${uploaded} of ${totalFiles} files uploaded successfully`;
                uploadStatus.className = 'text-xs text-yellow-600 mt-2';
            }
        }

        function updateProgress(uploaded, total) {
            const percentage = Math.round((uploaded / total) * 100);
            progressBar.style.width = percentage + '%';
            progressPercentage.textContent = percentage + '%';
            uploadStatus.textContent = `Uploaded ${uploaded} of ${total} files...`;
        }

        function addFileToList(name, url) {
            const div = document.createElement('div');
            div.className = 'flex items-center justify-between p-2 bg-green-50 rounded-lg';
            div.innerHTML = `
                <div class="flex items-center space-x-2">
                    <i class="fas fa-check-circle text-green-500"></i>
                    <span class="text-sm text-gray-700">${name}</span>
                </div>
                <a href="${url}" target="_blank" class="text-sm text-primary-600 hover:text-primary-700">
                    View <i class="fas fa-arrow-right ml-1"></i>
                </a>
            `;
            filesList.appendChild(div);
        }

        function addErrorToList(name, error) {
            const div = document.createElement('div');
            div.className = 'flex items-center justify-between p-2 bg-red-50 rounded-lg';
            div.innerHTML = `
                <div class="flex items-center space-x-2">
                    <i class="fas fa-times-circle text-red-500"></i>
                    <span class="text-sm text-gray-700">${name}</span>
                    <span class="text-xs text-red-600">- ${error}</span>
                </div>
            `;
            filesList.appendChild(div);
        }
    });
</script>
@endpush

@push('styles')
<style>
    #dropzone {
        transition: all 0.3s ease;
        min-height: 200px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    #dropzone:hover {
        border-color: #6366f1;
        background-color: #f8fafc;
    }
</style>
@endpush
@endsection