{{-- resources/views/admin/roles/permissions.blade.php --}}
@extends('admin.layouts.admin')

@section('title', $role->display_name . ' - Permissions')

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
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">Permissions</span>
        </div>
    </li>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Manage Permissions</h2>
                <p class="text-sm text-gray-500 mt-1">Assign permissions to role: {{ $role->display_name }}</p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-500">{{ $role->permissions()->count() }} permissions assigned</span>
            </div>
        </div>
        
        <form action="{{ route('admin.roles.update-permissions', $role) }}" method="POST" class="p-6">
            @csrf
            
            <div class="space-y-4">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-4">
                        <button type="button" onclick="selectAll()" class="text-sm text-primary-600 hover:text-primary-700">
                            Select All
                        </button>
                        <button type="button" onclick="deselectAll()" class="text-sm text-gray-500 hover:text-gray-700">
                            Deselect All
                        </button>
                    </div>
                    <div>
                        <span id="selectedCount" class="text-sm text-gray-500">0 selected</span>
                    </div>
                </div>

                @forelse($permissions as $group => $perms)
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <div class="px-4 py-2 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                            <h5 class="text-sm font-medium text-gray-700 capitalize">{{ $group }}</h5>
                            <button type="button" onclick="toggleGroup(this)" class="text-xs text-primary-600 hover:text-primary-700">
                                Select All
                            </button>
                        </div>
                        <div class="px-4 py-3 grid grid-cols-2 md:grid-cols-3 gap-2">
                            @foreach($perms as $permission)
                                <label class="flex items-center space-x-2 permission-item">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                           class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded permission-checkbox"
                                           data-group="{{ $group }}"
                                           {{ $role->permissions->contains($permission->id) ? 'checked' : '' }}
                                           onchange="updateSelectedCount()">
                                    <span class="text-sm text-gray-700">{{ $permission->display_name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No permissions available</p>
                @endforelse
                
                @error('permissions')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end space-x-3 pt-6 mt-6 border-t border-gray-200">
                <a href="{{ route('admin.roles.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    <i class="fas fa-save mr-2"></i> Update Permissions
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function updateSelectedCount() {
        const count = document.querySelectorAll('.permission-checkbox:checked').length;
        document.getElementById('selectedCount').textContent = count + ' selected';
    }

    function selectAll() {
        document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = true);
        updateSelectedCount();
    }

    function deselectAll() {
        document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = false);
        updateSelectedCount();
    }

    function toggleGroup(btn) {
        const group = btn.closest('.border').querySelectorAll('.permission-checkbox');
        const allChecked = Array.from(group).every(cb => cb.checked);
        group.forEach(cb => cb.checked = !allChecked);
        updateSelectedCount();
        btn.textContent = allChecked ? 'Select All' : 'Deselect All';
    }

    // Initialize count
    document.addEventListener('DOMContentLoaded', updateSelectedCount);
</script>
@endpush
@endsection