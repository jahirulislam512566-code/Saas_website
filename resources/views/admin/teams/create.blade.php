{{-- resources/views/admin/teams/create.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Create Team')

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
            <span class="text-gray-500">Create</span>
        </div>
    </li>
@endsection

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">Create New Team</h2>
            <p class="text-sm text-gray-500 mt-1">Create a new team and add members</p>
        </div>
        
        <form action="{{ route('admin.teams.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            
            <div class="space-y-6">
                <!-- Basic Information -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-4">Basic Information</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Team Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
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
                            <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('slug') border-red-500 @enderror"
                                   placeholder="auto-generated">
                            <p class="mt-1 text-xs text-gray-500">Leave blank to auto-generate from name</p>
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
                                  placeholder="Brief description of the team">{{ old('description') }}</textarea>
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
                                <option value="">Select an owner</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('owner_id') == $user->id ? 'selected' : '' }}>
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
                            <input type="number" name="max_members" id="max_members" value="{{ old('max_members') }}"
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
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                                   class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-700">Active Team</span>
                        </label>
                    </div>
                </div>

                <!-- Initial Members -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-4">Initial Members</h4>
                    
                    <div x-data="{ members: [] }">
                        <div class="space-y-2">
                            <template x-for="(member, index) in members" :key="index">
                                <div class="flex items-center space-x-2">
                                    <select :name="'members[' + index + '][user_id]'" 
                                            class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <option value="">Select a user</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                        @endforeach
                                    </select>
                                    <select :name="'members[' + index + '][role]'" 
                                            class="w-32 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <option value="admin">Admin</option>
                                        <option value="editor">Editor</option>
                                        <option value="member" selected>Member</option>
                                    </select>
                                    <button type="button" @click="members.splice(index, 1)" 
                                            class="p-2 text-red-500 hover:text-red-700 transition-colors">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </template>
                        </div>
                        
                        <button type="button" @click="members.push({ user_id: '', role: 'member' })" 
                                class="mt-2 inline-flex items-center text-sm text-primary-600 hover:text-primary-700 transition-colors">
                            <i class="fas fa-plus mr-1"></i> Add Member
                        </button>
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.teams.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                        <i class="fas fa-save mr-2"></i> Create Team
                    </button>
                </div>
            </div>
        </form>
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