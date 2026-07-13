{{-- resources/views/admin/users/index.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Users Management')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-slate-400 mx-2 text-sm"></i>
            <span class="text-slate-500">Users</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Users</h1>
            <p class="text-sm text-slate-500 mt-1">Manage all users in your application</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.users.export') }}" class="inline-flex items-center px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors text-sm font-medium">
                <i class="fas fa-download mr-2"></i> Export
            </a>
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                <i class="fas fa-plus mr-2"></i> Add User
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">Total Users</p>
                    <p class="text-xl font-bold text-slate-900">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">Active</p>
                    <p class="text-xl font-bold text-emerald-600">{{ $stats['active'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">Inactive</p>
                    <p class="text-xl font-bold text-slate-600">{{ $stats['inactive'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center">
                    <i class="fas fa-user-slash"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">New This Month</p>
                    <p class="text-xl font-bold text-blue-600">{{ $stats['new_this_month'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-calendar-plus"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm p-4">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Search</label>
                <input type="text" name="search" placeholder="Name or email..." value="{{ request('search') }}" 
                       class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Role</label>
                <select name="role" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    <option value="">All Roles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                <select name="status" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Sort By</label>
                <select name="sort" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Date Joined</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                    <option value="email" {{ request('sort') == 'email' ? 'selected' : '' }}>Email</option>
                </select>
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                    <i class="fas fa-search mr-2"></i> Filter
                </button>
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors text-sm font-medium">
                    <i class="fas fa-times mr-2"></i> Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Joined</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <x-admin.avatar :src="$user->avatar" :name="$user->name" size="sm" />
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-slate-900">{{ $user->name }}</p>
                                        <p class="text-xs text-slate-500">ID: #{{ $user->id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $user->role == 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-indigo-100 text-indigo-800' }}">
                                    {{ ucfirst($user->role ?? 'User') }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $user->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-800' }}">
                                    <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $user->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500">
                                <div>{{ $user->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-slate-400">{{ $user->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-1">
                                    <a href="{{ route('admin.users.show', $user) }}" 
                                       class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" 
                                       title="View">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user) }}" 
                                       class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" 
                                       title="Edit">
                                        <i class="fas fa-pen text-sm"></i>
                                    </a>
                                    <button onclick="toggleUserStatus('{{ $user->id }}', '{{ $user->name }}')" 
                                            class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" 
                                            title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}">
                                        <i class="fas {{ $user->is_active ? 'fa-pause' : 'fa-play' }} text-sm"></i>
                                    </button>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" 
                                          onsubmit="return confirm('Are you sure you want to delete user: {{ $user->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                <i class="fas fa-users text-4xl text-slate-300 mb-3 block"></i>
                                <p class="text-lg font-medium">No users found</p>
                                <p class="text-sm mt-1">Try adjusting your filters or create a new user</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-slate-200 flex flex-wrap items-center justify-between gap-2">
            <div class="text-sm text-slate-500">
                Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} results
            </div>
            <div>
                {{ $users->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Toggle Form -->
<form id="toggle-form" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
</form>

@push('scripts')
<script>
    function toggleUserStatus(userId, userName) {
        const action = document.querySelector(`input[value="${userId}"]`)?.closest('tr')?.querySelector('.fa-pause') ? 'deactivate' : 'activate';
        if (confirm(`Are you sure you want to ${action} user: ${userName}?`)) {
            const form = document.getElementById('toggle-form');
            form.action = `/admin/users/${userId}/toggle-status`;
            form.submit();
        }
    }
</script>
@endpush
@endsection