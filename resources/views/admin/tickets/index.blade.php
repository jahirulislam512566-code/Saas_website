@extends('admin.layouts.admin')

@section('title', 'Tickets')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">Tickets</span>
        </div>
    </li>
@endsection

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Support Tickets</h1>
                <p class="text-sm text-gray-500 mt-1">Manage all support tickets</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.tickets.create') }}" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i> New Ticket
                </a>
                <a href="{{ route('admin.tickets.export') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-download mr-2"></i> Export
                </a>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-blue-500">
            <p class="text-xs text-gray-500 uppercase tracking-wider">Total</p>
            <p class="text-2xl font-bold text-gray-900">{{ $tickets->total() }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-orange-500">
            <p class="text-xs text-gray-500 uppercase tracking-wider">Open</p>
            <p class="text-2xl font-bold text-gray-900">{{ $tickets->where('status', 'open')->count() }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-yellow-500">
            <p class="text-xs text-gray-500 uppercase tracking-wider">Pending</p>
            <p class="text-2xl font-bold text-gray-900">{{ $tickets->where('status', 'pending')->count() }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-green-500">
            <p class="text-xs text-gray-500 uppercase tracking-wider">Resolved</p>
            <p class="text-2xl font-bold text-gray-900">{{ $tickets->where('status', 'resolved')->count() }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-gray-500">
            <p class="text-xs text-gray-500 uppercase tracking-wider">Closed</p>
            <p class="text-2xl font-bold text-gray-900">{{ $tickets->where('status', 'closed')->count() }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label for="status" class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="priority" class="block text-xs font-medium text-gray-700 mb-1">Priority</label>
                <select name="priority" id="priority" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">All Priorities</option>
                    @foreach($priorities as $priority)
                        <option value="{{ $priority }}" {{ request('priority') == $priority ? 'selected' : '' }}>
                            {{ ucfirst($priority) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="department_id" class="block text-xs font-medium text-gray-700 mb-1">Department</label>
                <select name="department_id" id="department_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">All Departments</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="search" class="block text-xs font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" id="search" 
                       value="{{ request('search') }}"
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                       placeholder="Search tickets...">
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors w-full">
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
                <a href="{{ route('admin.tickets.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors w-full text-center">
                    <i class="fas fa-times mr-2"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Tickets Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ticket</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned To</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($tickets as $ticket)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900">{{ $ticket->ticket_number }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ Str::limit($ticket->subject, 40) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-500">{{ $ticket->department->name ?? 'N/A' }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $ticket->priority_badge ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $ticket->status_badge ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($ticket->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-500">{{ $ticket->user->name ?? 'N/A' }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-500">{{ $ticket->assignedAgent->name ?? 'Unassigned' }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-500">{{ $ticket->created_at->diffForHumans() }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.tickets.show', $ticket) }}" class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.tickets.edit', $ticket) }}" class="text-indigo-600 hover:text-indigo-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.tickets.destroy', $ticket) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Are you sure you want to delete this ticket?')" class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-inbox text-gray-300 text-4xl mb-3 block"></i>
                                No tickets found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200">
            {{ $tickets->links() }}
        </div>
    </div>
</div>
@endsection