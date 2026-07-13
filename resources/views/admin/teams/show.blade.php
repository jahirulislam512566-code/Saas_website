{{-- resources/views/admin/teams/show.blade.php --}}
@extends('admin.layouts.admin')

@section('title', $team->name)

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
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center text-white text-2xl font-bold">
                {{ substr($team->name, 0, 2) }}
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $team->name }}</h1>
                <div class="flex items-center space-x-3 mt-1">
                    <span class="text-sm text-gray-500">Slug: {{ $team->slug }}</span>
                    <span class="text-gray-300">|</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        {{ $team->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $team->is_active ? 'Active' : 'Inactive' }}
                    </span>
                    <span class="text-xs text-gray-400">
                        {{ $team->members_count }} members
                    </span>
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.teams.members', $team) }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-user-plus mr-2"></i> Manage Members
            </a>
            <a href="{{ route('admin.teams.edit', $team) }}" 
               class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-pen mr-2"></i> Edit
            </a>
            <a href="{{ route('admin.teams.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
        </div>
    </div>

    <!-- Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Description -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-sm font-medium text-gray-900 mb-3">Description</h3>
                @if($team->description)
                    <p class="text-gray-600">{{ $team->description }}</p>
                @else
                    <p class="text-gray-400 italic">No description provided</p>
                @endif
            </div>

            <!-- Members -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-sm font-medium text-gray-900">Members ({{ $team->members_count }})</h3>
                    <a href="{{ route('admin.teams.members', $team) }}" class="text-xs text-primary-600 hover:text-primary-700">
                        Manage Members
                    </a>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($team->members()->limit(10)->get() as $member)
                        <div class="px-6 py-3 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <x-admin.avatar :src="$member->avatar" :name="$member->name" size="sm" />
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $member->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $member->email }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $member->pivot->role == 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ ucfirst($member->pivot->role) }}
                                    </span>
                                    @if($team->owner_id == $member->id)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Owner
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-4 text-center text-gray-500">No members in this team</div>
                    @endforelse
                    @if($team->members_count > 10)
                        <div class="px-6 py-3 text-center text-xs text-gray-400">
                            + {{ $team->members_count - 10 }} more members
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-sm font-medium text-gray-900 mb-4">Team Information</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-xs text-gray-500">ID</dt>
                        <dd class="text-sm text-gray-900">#{{ $team->id }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Name</dt>
                        <dd class="text-sm text-gray-900">{{ $team->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Slug</dt>
                        <dd class="text-sm text-gray-900">{{ $team->slug }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Owner</dt>
                        <dd class="text-sm text-gray-900">{{ $team->owner->name ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Members</dt>
                        <dd class="text-sm text-gray-900">{{ $team->members_count }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Max Members</dt>
                        <dd class="text-sm text-gray-900">{{ $team->max_members ?? 'Unlimited' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Status</dt>
                        <dd class="text-sm text-gray-900">{{ $team->is_active ? 'Active' : 'Inactive' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Created</dt>
                        <dd class="text-sm text-gray-900">{{ $team->created_at->format('M d, Y H:i:s') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Last Updated</dt>
                        <dd class="text-sm text-gray-900">{{ $team->updated_at->format('M d, Y H:i:s') }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-sm font-medium text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.teams.members', $team) }}" 
                       class="block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-user-plus mr-2"></i> Add Member
                    </a>
                    <a href="{{ route('admin.teams.invitations', $team) }}" 
                       class="block w-full text-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                        <i class="fas fa-envelope mr-2"></i> Invite Members
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection