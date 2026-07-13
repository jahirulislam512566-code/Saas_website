{{-- resources/views/admin/websites/create.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Create Website')

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
            <span class="text-gray-500">Create</span>
        </div>
    </li>
@endsection

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">Create New Website</h2>
            <p class="text-sm text-gray-500 mt-1">Set up a new website for your users</p>
        </div>
        
        <form action="{{ route('admin.websites.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            
            <div class="space-y-6">
                <!-- Basic Information -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-4">Basic Information</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Website Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('name') border-red-500 @enderror"
                                   placeholder="My Awesome Website">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Owner <span class="text-red-500">*</span>
                            </label>
                            <select name="user_id" id="user_id" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('user_id') border-red-500 @enderror">
                                <option value="">Select a user</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label for="domain" class="block text-sm font-medium text-gray-700 mb-1">
                            Domain
                        </label>
                        <input type="text" name="domain" id="domain" value="{{ old('domain') }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('domain') border-red-500 @enderror"
                               placeholder="example.com">
                        <p class="mt-1 text-xs text-gray-500">Custom domain for the website (optional)</p>
                        @error('domain')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mt-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                            Description
                        </label>
                        <textarea name="description" id="description" rows="3"
                                  class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('description') border-red-500 @enderror"
                                  placeholder="Brief description of the website">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Template Selection -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-4">Template</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="template_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Select Template <span class="text-red-500">*</span>
                            </label>
                            <select name="template_id" id="template_id" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('template_id') border-red-500 @enderror">
                                <option value="">Select a template</option>
                                @foreach($templates as $template)
                                    <option value="{{ $template->id }}" {{ old('template_id') == $template->id ? 'selected' : '' }}>
                                        {{ $template->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('template_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="template_preview" class="block text-sm font-medium text-gray-700 mb-1">
                                Preview
                            </label>
                            <div id="template_preview" class="h-32 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200">
                                <span class="text-gray-400 text-sm">Select a template to preview</span>
                            </div>
                        </div>
                    </div>
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
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Features</label>
                            <div class="space-y-2 pt-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                                           class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Featured Website</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="has_ssl" value="1" {{ old('has_ssl') ? 'checked' : '' }}
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
                        <i class="fas fa-save mr-2"></i> Create Website
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Template preview on select
    document.getElementById('template_id').addEventListener('change', function() {
        const preview = document.getElementById('template_preview');
        const selected = this.options[this.selectedIndex];
        if (this.value) {
            preview.innerHTML = `
                <div class="text-center">
                    <i class="fas fa-file-code text-primary-400 text-2xl mb-2 block"></i>
                    <span class="text-sm text-gray-700">${selected.text}</span>
                    <p class="text-xs text-gray-400 mt-1">Template selected</p>
                </div>
            `;
        } else {
            preview.innerHTML = `<span class="text-gray-400 text-sm">Select a template to preview</span>`;
        }
    });
</script>
@endpush
@endsection