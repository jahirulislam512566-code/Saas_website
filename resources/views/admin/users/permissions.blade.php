<!-- admin/users/permissions.blade.php -->
@extends('layout.app')

@section('title', 'User Permissions')
@section('page-title', 'User Permissions')
@section('page-subtitle', 'Manage permissions for {{ $user->name }}')

@section('content')
<div x-data="permissionManager()" x-init="init()">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Permissions Form -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white">Permissions</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->name }} ({{ ucfirst($user->role) }})</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button @click="selectAll()" 
                                    class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                                Select All
                            </button>
                            <button @click="deselectAll()" 
                                    class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:underline">
                                Deselect All
                            </button>
                        </div>
                    </div>

                    <form @submit.prevent="savePermissions()" class="space-y-6">
                        @foreach($permissionGroups as $group => $permissions)
                            <div>
                                <h5 class="font-medium text-gray-900 dark:text-white capitalize mb-3">{{ str_replace('_', ' ', $group) }}</h5>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                    @foreach($permissions as $permission)
                                        <label class="flex items-center gap-2 p-2 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors cursor-pointer">
                                            <input type="checkbox" 
                                                   x-model="permissions['{{ $permission->name }}']" 
                                                   class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 focus:ring-indigo-500">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ ucfirst(str_replace('_', ' ', $permission->name)) }}</span>
                                            <span class="ml-auto text-xs text-gray-500 dark:text-gray-400">{{ $permission->description }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        <div class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-800">
                            <button type="submit" 
                                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-sm transition-all">
                                <i class="fas fa-save mr-1.5"></i>Save Permissions
                            </button>
                            <a href="{{ route('admin.users.show', $user) }}" 
                               class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors">
                                Cancel
                            </a>
                            <button type="button" @click="resetToDefault()" 
                                    class="px-4 py-2 text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors ml-auto">
                                <i class="fas fa-undo mr-1.5"></i>Reset to Default
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- User Info -->
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=4f46e5&color=fff&size=64' }}" 
                             alt="{{ $user->name }}" 
                             class="w-12 h-12 rounded-full">
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $user->name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                            <span class="text-xs px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="space-y-2 text-sm border-t border-gray-200 dark:border-gray-800 pt-4">
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Permissions Count</span>
                            <span class="text-gray-900 dark:text-white" x-text="grantedCount + '/' + totalCount"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Last Updated</span>
                            <span class="text-gray-900 dark:text-white">{{ $user->permissions_updated_at ? $user->permissions_updated_at->format('M d, Y') : 'Never' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Permission Summary -->
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-6">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Summary</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Granted</span>
                            <span class="text-sm font-medium text-green-600 dark:text-green-400" x-text="grantedCount"></span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full transition-all" 
                                 :style="'width: ' + (grantedCount / totalCount * 100) + '%'"></div>
                        </div>
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Denied</span>
                            <span class="text-sm font-medium text-red-600 dark:text-red-400" x-text="deniedCount"></span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-red-500 h-2 rounded-full transition-all" 
                                 :style="'width: ' + (deniedCount / totalCount * 100) + '%'"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Role Templates -->
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-6">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Role Templates</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Apply predefined permission sets</p>
                    
                    <div class="space-y-2">
                        <button @click="applyTemplate('admin')" 
                                class="w-full flex items-center gap-2 px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors">
                            <i class="fas fa-crown text-red-500"></i>
                            <span>Admin (Full Access)</span>
                        </button>
                        <button @click="applyTemplate('manager')" 
                                class="w-full flex items-center gap-2 px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors">
                            <i class="fas fa-user-tie text-purple-500"></i>
                            <span>Manager (Management Access)</span>
                        </button>
                        <button @click="applyTemplate('support')" 
                                class="w-full flex items-center gap-2 px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors">
                            <i class="fas fa-headset text-blue-500"></i>
                            <span>Support (Customer Support Access)</span>
                        </button>
                        <button @click="applyTemplate('developer')" 
                                class="w-full flex items-center gap-2 px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors">
                            <i class="fas fa-code text-green-500"></i>
                            <span>Developer (Technical Access)</span>
                        </button>
                        <button @click="applyTemplate('editor')" 
                                class="w-full flex items-center gap-2 px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors">
                            <i class="fas fa-edit text-gray-500"></i>
                            <span>Editor (Content Access)</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function permissionManager() {
    return {
        permissions: @json($permissions->pluck('granted', 'name')->all()),
        allPermissions: @json($permissions->pluck('name')->all()),
        userId: {{ $user->id }},
        
        get grantedCount() {
            return Object.values(this.permissions).filter(v => v).length;
        },
        
        get deniedCount() {
            return Object.values(this.permissions).filter(v => !v).length;
        },
        
        get totalCount() {
            return Object.keys(this.permissions).length;
        },
        
        selectAll() {
            Object.keys(this.permissions).forEach(key => {
                this.permissions[key] = true;
            });
        },
        
        deselectAll() {
            Object.keys(this.permissions).forEach(key => {
                this.permissions[key] = false;
            });
        },
        
        savePermissions() {
            fetch(`{{ route('admin.users.permissions.update', $user) }}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ permissions: this.permissions })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Permissions saved successfully!');
                }
            })
            .catch(error => console.error('Error saving permissions:', error));
        },
        
        applyTemplate(role) {
            const templates = {
                admin: {
                    'view_dashboard': true,
                    'manage_users': true,
                    'manage_settings': true,
                    'manage_content': true,
                    'manage_payments': true,
                    'view_reports': true,
                    'manage_integrations': true,
                    'manage_websites': true,
                    'manage_crm': true,
                    'manage_support': true,
                    'view_analytics': true
                },
                manager: {
                    'view_dashboard': true,
                    'manage_users': false,
                    'manage_settings': false,
                    'manage_content': true,
                    'manage_payments': true,
                    'view_reports': true,
                    'manage_integrations': false,
                    'manage_websites': true,
                    'manage_crm': true,
                    'manage_support': false,
                    'view_analytics': true
                },
                support: {
                    'view_dashboard': true,
                    'manage_users': false,
                    'manage_settings': false,
                    'manage_content': false,
                    'manage_payments': false,
                    'view_reports': false,
                    'manage_integrations': false,
                    'manage_websites': false,
                    'manage_crm': true,
                    'manage_support': true,
                    'view_analytics': false
                },
                developer: {
                    'view_dashboard': true,
                    'manage_users': false,
                    'manage_settings': true,
                    'manage_content': false,
                    'manage_payments': false,
                    'view_reports': false,
                    'manage_integrations': true,
                    'manage_websites': true,
                    'manage_crm': false,
                    'manage_support': false,
                    'view_analytics': false
                },
                editor: {
                    'view_dashboard': true,
                    'manage_users': false,
                    'manage_settings': false,
                    'manage_content': true,
                    'manage_payments': false,
                    'view_reports': false,
                    'manage_integrations': false,
                    'manage_websites': true,
                    'manage_crm': false,
                    'manage_support': false,
                    'view_analytics': false
                }
            };
            
            const template = templates[role];
            if (template) {
                Object.keys(this.permissions).forEach(key => {
                    this.permissions[key] = template[key] || false;
                });
            }
        },
        
        resetToDefault() {
            if (confirm('Reset permissions to default for this role?')) {
                fetch(`{{ route('admin.users.permissions.reset', $user) }}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.permissions = data.permissions;
                    }
                })
                .catch(error => console.error('Error resetting permissions:', error));
            }
        }
    }
}
</script>
@endpush