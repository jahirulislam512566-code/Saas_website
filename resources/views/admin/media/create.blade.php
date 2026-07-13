@extends('admin.layouts.admin')

@section('title', 'Upload Media')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">Upload Media</h2>
            <p class="text-sm text-gray-500 mt-1">Upload new media files</p>
        </div>

        <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf

            <div class="space-y-6">
                <!-- File Upload -->
                <div>
                    <label for="file" class="block text-sm font-medium text-gray-700 mb-1">
                        File <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-primary-500 transition-colors">
                        <div class="space-y-1 text-center">
                            <i class="fas fa-cloud-upload-alt text-3xl text-gray-400"></i>
                            <div class="flex text-sm text-gray-600">
                                <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none">
                                    <span>Upload a file</span>
                                    <input id="file-upload" name="file" type="file" class="sr-only" required>
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, PDF, MP4 up to 10MB</p>
                        </div>
                    </div>
                    @error('file')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- File Preview -->
                <div id="file-preview" class="hidden">
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                        <i class="fas fa-file text-gray-400 text-2xl"></i>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900" id="file-name">File name</p>
                            <p class="text-xs text-gray-500" id="file-size">File size</p>
                        </div>
                        <button type="button" onclick="removeFile()" class="text-red-600 hover:text-red-800">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <!-- Collection -->
                <div>
                    <label for="collection" class="block text-sm font-medium text-gray-700 mb-1">Collection</label>
                    <select name="collection" id="collection" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        <option value="default">Default</option>
                        <option value="posts">Posts</option>
                        <option value="products">Products</option>
                        <option value="profiles">Profiles</option>
                        <option value="banners">Banners</option>
                    </select>
                </div>

                <!-- Alt Text -->
                <div>
                    <label for="alt_text" class="block text-sm font-medium text-gray-700 mb-1">Alt Text</label>
                    <input type="text" name="alt_text" id="alt_text" 
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                           placeholder="Describe the image for accessibility">
                </div>

                <!-- Caption -->
                <div>
                    <label for="caption" class="block text-sm font-medium text-gray-700 mb-1">Caption</label>
                    <textarea name="caption" id="caption" rows="2" 
                              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                              placeholder="Add a caption"></textarea>
                </div>

                <!-- Submit -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.media.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                        <i class="fas fa-upload mr-2"></i> Upload
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('file-upload').addEventListener('change', function(e) {
    const file = this.files[0];
    if (file) {
        document.getElementById('file-name').textContent = file.name;
        document.getElementById('file-size').textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
        document.getElementById('file-preview').classList.remove('hidden');
    }
});

function removeFile() {
    document.getElementById('file-upload').value = '';
    document.getElementById('file-preview').classList.add('hidden');
}
</script>
@endpush
@endsection