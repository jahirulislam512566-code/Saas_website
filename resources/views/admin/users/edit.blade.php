{{-- resources/views/admin/users/edit.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Edit User')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <a href="{{ route('admin.users.index') }}" class="text-gray-500 hover:text-gray-700">Users</a>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-700">{{ $user->name }}</span>
        </div>
    </li>
@endsection

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Edit User</h2>
                <p class="text-sm text-gray-500 mt-1">Update user information and permissions</p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                    {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $user->is_active ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                </span>
                <a href="{{ route('admin.users.show', $user) }}" class="text-gray-400 hover:text-blue-600 transition-colors">
                    <i class="fas fa-eye"></i>
                </a>
            </div>
        </div>
        
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="p-6" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Personal Information -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-4">Personal Information</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Change Password -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-4">Change Password</h4>
                    <p class="text-sm text-gray-500 mb-4">Leave blank to keep current password</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                New Password
                            </label>
                            <input type="password" name="password" id="password"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('password') border-red-500 @enderror">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                Confirm Password
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        </div>
                    </div>
                </div>
                
                <!-- Role & Status -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-4">Role & Status</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">
                                Role <span class="text-red-500">*</span>
                            </label>
                            <select name="role" id="role" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('role') border-red-500 @enderror">
                                <option value="">Select a role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role', $user->roles->first()->id ?? '') == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <div class="flex items-center space-x-4 pt-2">
                                <label class="flex items-center">
                                    <input type="radio" name="is_active" value="1" {{ old('is_active', $user->is_active ? '1' : '0') == '1' ? 'checked' : '' }}
                                           class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Active</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="is_active" value="0" {{ old('is_active', $user->is_active ? '1' : '0') == '0' ? 'checked' : '' }}
                                           class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Inactive</span>
                                </label>
                            </div>
                            @error('is_active')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Submit -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                        <i class="fas fa-save mr-2"></i> Update User
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
                    <p class="text-sm font-medium text-gray-900">Delete this user</p>
                    <p class="text-xs text-gray-500">This action cannot be undone. All user data will be permanently removed.</p>
                </div>
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" 
                      onsubmit="return confirm('Are you sure you want to delete user: {{ $user->name }}? This action cannot be undone!')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                        <i class="fas fa-trash mr-2"></i> Delete User
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection