{{-- resources/views/admin/teams/members.blade.php --}}
@extends('admin.layouts.admin')

@section('title', $team->name . ' - Members')

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
            <a href="{{ route('admin.teams.show', $team) }}" class="text-gray-500 hover:text-gray-700">{{ $team->name }}</a>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">Members</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $team->name }} - Members</h1>
            <p class="text-sm text-gray-500 mt-1">Manage team members and their roles</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.teams.invitations', $team) }}" 
               class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                <i class="fas fa-envelope mr-2"></i> Invite Members
            </a>
            <button onclick="addMember()" 
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-user-plus mr-2"></i> Add Member
            </button>
            <a href="{{ route('admin.teams.show', $team) }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
        </div>
    </div>

    <!-- Members Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($members as $member)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <x-admin.avatar :src="$member->avatar" :name="$member->name" size="sm" />
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">{{ $member->name }}</p>
                                        @if($team->owner_id == $member->id)
                                            <span class="text-xs text-yellow-600 font-medium">Team Owner</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $member->email }}</td>
                            <td class="px-6 py-4">
                                @if($team->owner_id != $member->id)
                                    <form action="{{ route('admin.teams.members.update', [$team, $member]) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <select name="role" onchange="this.form.submit()" 
                                                class="rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                                            <option value="admin" {{ $member->pivot->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="editor" {{ $member->pivot->role == 'editor' ? 'selected' : '' }}>Editor</option>
                                            <option value="member" {{ $member->pivot->role == 'member' ? 'selected' : '' }}>Member</option>
                                        </select>
                                    </form>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Owner
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $member->pivot->joined_at ? $member->pivot->joined_at->format('M d, Y') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    @if($team->owner_id != $member->id)
                                        <form action="{{ route('admin.teams.members.remove', [$team, $member]) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Remove {{ $member->name }} from this team?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors" title="Remove">
                                                <i class="fas fa-user-minus"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-users text-4xl text-gray-300 mb-3 block"></i>
                                <p class="text-lg font-medium">No members in this team</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $members->links() }}
        </div>
    </div>
</div>

<!-- Add Member Modal -->
<div x-data="{ show: false }" 
     x-show="show"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto" 
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" @click="show = false">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form action="{{ route('admin.teams.members.add', $team) }}" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Add Member</h3>
                            <div class="mt-4">
                                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    Select User <span class="text-red-500">*</span>
                                </label>
                                <select name="user_id" id="user_id" required
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    <option value="">Select a user</option>
                                    @foreach($availableUsers as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-4">
                                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">
                                    Role <span class="text-red-500">*</span>
                                </label>
                                <select name="role" id="role" required
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    <option value="admin">Admin</option>
                                    <option value="editor">Editor</option>
                                    <option value="member" selected>Member</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Add Member
                    </button>
                    <button type="button" @click="show = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function addMember() {
        document.querySelector('[x-data]').__x.$data.show = true;
    }
</script>
@endpush
@endsection