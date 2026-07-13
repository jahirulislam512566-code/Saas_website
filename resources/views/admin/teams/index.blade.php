{{-- resources/views/admin/teams/index.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Teams Management')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">Teams</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Teams</h1>
            <p class="text-sm text-gray-500 mt-1">Manage all teams in your organization</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.teams.export') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-download mr-2"></i> Export
            </a>
            <a href="{{ route('admin.teams.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-plus mr-2"></i> Add Team
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Teams</p>
                    <p class="text-xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Active Teams</p>
                    <p class="text-xl font-bold text-green-600">{{ $stats['active'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Members</p>
                    <p class="text-xl font-bold text-blue-600">{{ $stats['total_members'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-user-plus"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Pending Invitations</p>
                    <p class="text-xl font-bold text-yellow-600">{{ $stats['pending_invitations'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center">
                    <i class="fas fa-envelope"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm p-4">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" placeholder="Search teams..." value="{{ request('search') }}" 
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                <select name="sort" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                    <option value="members_count" {{ request('sort') == 'members_count' ? 'selected' : '' }}>Members</option>
                    <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Date Created</option>
                </select>
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    <i class="fas fa-search mr-2"></i> Filter
                </button>
                <a href="{{ route('admin.teams.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-times mr-2"></i> Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Teams Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($teams as $team)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow group">
                <!-- Team Header -->
                <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center text-white text-xl font-bold">
                                {{ substr($team->name, 0, 2) }}
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $team->name }}</h3>
                                <p class="text-xs text-gray-500">{{ $team->slug }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $team->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $team->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
                
                <!-- Team Info -->
                <div class="p-4">
                    <p class="text-sm text-gray-600 line-clamp-2">{{ $team->description ?? 'No description' }}</p>
                    
                    <div class="mt-4 flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-1 text-sm text-gray-500">
                                <i class="fas fa-users"></i>
                                <span>{{ $team->members_count }} members</span>
                            </div>
                            <div class="flex items-center space-x-1 text-sm text-gray-500">
                                <i class="fas fa-user"></i>
                                <span>{{ $team->owner->name ?? 'Unknown' }}</span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-1">
                            <a href="{{ route('admin.teams.show', $team) }}" 
                               class="p-1.5 text-gray-400 hover:text-blue-600 transition-colors" title="View">
                                <i class="fas fa-eye text-sm"></i>
                            </a>
                            <a href="{{ route('admin.teams.edit', $team) }}" 
                               class="p-1.5 text-gray-400 hover:text-primary-600 transition-colors" title="Edit">
                                <i class="fas fa-pen text-sm"></i>
                            </a>
                            <button onclick="toggleTeam('{{ $team->id }}')" 
                                    class="p-1.5 text-gray-400 hover:text-yellow-600 transition-colors" 
                                    title="{{ $team->is_active ? 'Deactivate' : 'Activate' }}">
                                <i class="fas {{ $team->is_active ? 'fa-pause' : 'fa-play' }} text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="text-center py-12 bg-white rounded-xl shadow-sm">
                    <i class="fas fa-users text-4xl text-gray-300 mb-3 block"></i>
                    <p class="text-lg font-medium text-gray-900">No teams found</p>
                    <p class="text-sm text-gray-500 mt-1">Create your first team to get started</p>
                    <a href="{{ route('admin.teams.create') }}" class="inline-flex items-center mt-4 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i> Create Team
                    </a>
                </div>
            </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    <div class="flex items-center justify-between">
        @if($teams instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="text-sm text-gray-500">
                Showing {{ $teams->firstItem() ?? 0 }} to {{ $teams->lastItem() ?? 0 }} of {{ $teams->total() }} results
            </div>
            <div>
                {{ $teams->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-sm text-gray-500">
                Showing {{ $teams->count() }} results
            </div>
        @endif
    </div>
</div>

<!-- Toggle Form -->
<form id="toggle-form" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
</form>

@push('scripts')
<script>
    function toggleTeam(teamId) {
        if (confirm('Are you sure you want to toggle this team status?')) {
            const form = document.getElementById('toggle-form');
            form.action = `/admin/teams/${teamId}/toggle`;
            form.submit();
        }
    }
</script>
@endpush

@push('styles')
<style>
    .card-hover {
        transition: all 0.2s ease;
    }
    .card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush
@endsection