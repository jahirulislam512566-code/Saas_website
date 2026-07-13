{{-- resources/views/admin/roles/edit.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Edit Role')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <a href="{{ route('admin.roles.index') }}" class="text-gray-500 hover:text-gray-700">Roles</a>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-700">{{ $role->display_name }}</span>
        </div>
    </li>
@endsection

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Edit Role</h2>
                <p class="text-sm text-gray-500 mt-1">Update role details and permissions</p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                    {{ $role->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ $role->is_active ? 'Active' : 'Inactive' }}
                </span>
                <a href="{{ route('admin.roles.permissions', $role) }}" 
                   class="text-gray-400 hover:text-purple-600 transition-colors" title="Manage Permissions">
                    <i class="fas fa-lock"></i>
                </a>
            </div>
        </div>
        
        <form action="{{ route('admin.roles.update', $role) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Role Details -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-4">Role Details</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Role Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}" required
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('name') border-red-500 @enderror"
                                   placeholder="e.g., editor">
                            <p class="mt-1 text-xs text-gray-500">Unique identifier (lowercase, no spaces)</p>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="display_name" class="block text-sm font-medium text-gray-700 mb-1">
                                Display Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="display_name" id="display_name" value="{{ old('display_name', $role->display_name) }}" required
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('display_name') border-red-500 @enderror"
                                   placeholder="e.g., Editor">
                            @error('display_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                            Description
                        </label>
                        <textarea name="description" id="description" rows="2"
                                  class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('description') border-red-500 @enderror"
                                  placeholder="Describe the purpose of this role">{{ old('description', $role->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Permissions -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-4">Permissions</h4>
                    
                    <div class="space-y-4">
                        @forelse($permissions as $group => $perms)
                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                <div class="px-4 py-2 bg-gray-50 border-b border-gray-200">
                                    <h5 class="text-sm font-medium text-gray-700 capitalize">{{ $group }}</h5>
                                </div>
                                <div class="px-4 py-3 grid grid-cols-2 md:grid-cols-3 gap-2">
                                    @foreach($perms as $permission)
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                                   class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                                                   {{ $role->permissions->contains($permission->id) ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">{{ $permission->display_name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No permissions available</p>
                        @endforelse
                    </div>
                    @error('permissions')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-4">Status</h4>
                    
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $role->is_active) ? 'checked' : '' }}
                               class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-700">Active</span>
                    </label>
                </div>

                <!-- Submit -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.roles.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                        <i class="fas fa-save mr-2"></i> Update Role
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Danger Zone -->
    @if($role->name !== 'admin')
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
                        <p class="text-sm font-medium text-gray-900">Delete this role</p>
                        <p class="text-xs text-gray-500">This action cannot be undone. Users will lose associated permissions.</p>
                    </div>
                    <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete role: {{ $role->display_name }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                            <i class="fas fa-trash mr-2"></i> Delete Role
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    // Auto-generate slug from display name
    document.getElementById('display_name').addEventListener('input', function() {
        const nameField = document.getElementById('name');
        if (!nameField.dataset.manual) {
            nameField.value = this.value
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }
    });
    
    document.getElementById('name').addEventListener('focus', function() {
        this.dataset.manual = 'true';
    });
</script>
@endpush
@endsection