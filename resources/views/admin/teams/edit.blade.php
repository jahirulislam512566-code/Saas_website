{{-- resources/views/admin/teams/edit.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Edit Team')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <a href="{{ route('admin.teams.index') }}" class="text-gray-500 hover:text-gray-700">Teams</a>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-700">{{ $team->name }}</span>
        </div>
    </li>
@endsection

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Edit Team</h2>
                <p class="text-sm text-gray-500 mt-1">Update team information and settings</p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                    {{ $team->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ $team->is_active ? 'Active' : 'Inactive' }}
                </span>
                <a href="{{ route('admin.teams.show', $team) }}" 
                   class="text-gray-400 hover:text-blue-600 transition-colors" title="View">
                    <i class="fas fa-eye"></i>
                </a>
            </div>
        </div>
        
        <form action="{{ route('admin.teams.update', $team) }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Basic Information -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-4">Basic Information</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Team Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $team->name) }}" required
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('name') border-red-500 @enderror"
                                   placeholder="e.g., Marketing Team">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">
                                Slug
                            </label>
                            <input type="text" name="slug" id="slug" value="{{ old('slug', $team->slug) }}" required
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('slug') border-red-500 @enderror"
                                   placeholder="team-slug">
                            @error('slug')
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
                                  placeholder="Brief description of the team">{{ old('description', $team->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Owner & Settings -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-4">Owner & Settings</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="owner_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Team Owner <span class="text-red-500">*</span>
                            </label>
                            <select name="owner_id" id="owner_id" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('owner_id') border-red-500 @enderror">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('owner_id', $team->owner_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('owner_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="max_members" class="block text-sm font-medium text-gray-700 mb-1">
                                Max Members
                            </label>
                            <input type="number" name="max_members" id="max_members" value="{{ old('max_members', $team->max_members) }}"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('max_members') border-red-500 @enderror"
                                   placeholder="Unlimited">
                            <p class="mt-1 text-xs text-gray-500">Leave empty for unlimited</p>
                            @error('max_members')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $team->is_active) ? 'checked' : '' }}
                                   class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-700">Active Team</span>
                        </label>
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.teams.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                        <i class="fas fa-save mr-2"></i> Update Team
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
                    <p class="text-sm font-medium text-gray-900">Delete this team</p>
                    <p class="text-xs text-gray-500">This action cannot be undone. All team data will be permanently removed.</p>
                </div>
                <form action="{{ route('admin.teams.destroy', $team) }}" method="POST" 
                      onsubmit="return confirm('Are you sure you want to delete this team? This action cannot be undone!')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                        <i class="fas fa-trash mr-2"></i> Delete Team
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-generate slug from name
    document.getElementById('name').addEventListener('input', function() {
        const slugField = document.getElementById('slug');
        if (!slugField.dataset.manual) {
            slugField.value = this.value
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }
    });
    
    document.getElementById('slug').addEventListener('focus', function() {
        this.dataset.manual = 'true';
    });
</script>
@endpush
@endsection