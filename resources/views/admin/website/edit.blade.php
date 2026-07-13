{{-- resources/views/admin/websites/edit.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Edit Website')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <a href="{{ route('admin.websites.index') }}" class="text-gray-500 hover:text-gray-700">Websites</a>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-700">{{ $website->name }}</span>
        </div>
    </li>
@endsection

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Edit Website</h2>
                <p class="text-sm text-gray-500 mt-1">Update website information and settings</p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                    {{ $website->status == 'published' ? 'bg-green-100 text-green-800' : 
                       ($website->status == 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                    {{ ucfirst($website->status) }}
                </span>
                <a href="{{ route('admin.websites.show', $website) }}" 
                   class="text-gray-400 hover:text-blue-600 transition-colors" title="View">
                    <i class="fas fa-eye"></i>
                </a>
            </div>
        </div>
        
        <form action="{{ route('admin.websites.update', $website) }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Basic Information -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-4">Basic Information</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Website Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $website->name) }}" required
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('name') border-red-500 @enderror"
                                   placeholder="My Awesome Website">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="domain" class="block text-sm font-medium text-gray-700 mb-1">
                                Domain
                            </label>
                            <input type="text" name="domain" id="domain" value="{{ old('domain', $website->domain) }}"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('domain') border-red-500 @enderror"
                                   placeholder="example.com">
                            <p class="mt-1 text-xs text-gray-500">Custom domain for the website</p>
                            @error('domain')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                            Description
                        </label>
                        <textarea name="description" id="description" rows="3"
                                  class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('description') border-red-500 @enderror"
                                  placeholder="Brief description of the website">{{ old('description', $website->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Screenshot -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-4">Screenshot</h4>
                    
                    @if($website->screenshot)
                        <div class="mb-3 relative inline-block">
                            <img src="{{ Storage::disk('public')->url($website->screenshot) }}" 
                                 alt="{{ $website->name }}" 
                                 class="w-full max-w-md rounded-lg border border-gray-200">
                            <button type="button" onclick="removeScreenshot()" 
                                    class="absolute top-2 right-2 p-1.5 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif
                    
                    <input type="file" name="screenshot" id="screenshot" accept="image/*"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                    <p class="mt-1 text-xs text-gray-500">PNG, JPG up to 5MB</p>
                </div>

                <!-- Status -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-4">Status</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" id="status" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('status') border-red-500 @enderror">
                                <option value="draft" {{ old('status', $website->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status', $website->status) == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="archived" {{ old('status', $website->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Features</label>
                            <div class="space-y-2 pt-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $website->is_featured) ? 'checked' : '' }}
                                           class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Featured Website</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="has_ssl" value="1" {{ old('has_ssl', $website->has_ssl) ? 'checked' : '' }}
                                           class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Enable SSL</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.websites.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                        <i class="fas fa-save mr-2"></i> Update Website
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Danger Zone -->
    <div class="mt-6 bg-white rounded-xl shadow-sm overflow-hidden border-2 border-red-200">
        <div class="px-6 py-4 border-b border-red-200 bg-red-50">
            <h3 class="text-sm font-bold text-red-700 flex items-center">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Danger Zone
            </h3>
        </div>
        <div class="px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-900">Delete this website</p>
                    <p class="text-xs text-gray-500">This action cannot be undone. All website data will be permanently removed.</p>
                </div>
                <form action="{{ route('admin.websites.destroy', $website) }}" method="POST" 
                      onsubmit="return confirm('Are you sure you want to delete this website? This action cannot be undone!')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                        <i class="fas fa-trash mr-2"></i> Delete Website
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function removeScreenshot() {
        if (confirm('Remove the screenshot?')) {
            fetch('{{ route('admin.websites.screenshot.destroy', $website) }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            }).then(() => window.location.reload());
        }
    }
</script>
@endpush
@endsection