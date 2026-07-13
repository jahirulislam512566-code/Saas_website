@extends('layouts.admin')

@section('title', 'Support Tickets')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Support Tickets</h3>
            <a href="{{ route('admin.support.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> New Ticket
            </a>
        </div>
        <div class="card-body">
            <!-- Filters -->
            <form method="GET" class="row g-3 mb-4">
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="priority" class="form-select">
                        <option value="">All Priorities</option>
                        @foreach($priorities as $priority)
                            <option value="{{ $priority }}" {{ request('priority') == $priority ? 'selected' : '' }}>
                                {{ ucfirst($priority) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="department" class="form-select">
                        <option value="">All Departments</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ request('department') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-secondary">Search</button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Subject</th>
                            <th>Department</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Created By</th>
                            <th>Assigned To</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $ticket)
                        <tr>
                            <td>#{{ $ticket->id }}</td>
                            <td>{{ Str::limit($ticket->subject, 40) }}</td>
                            <td>{{ $ticket->department->name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-{{ $ticket->priority_color }}">
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $ticket->status_color }}">
                                    {{ ucfirst($ticket->status) }}
                                </span>
                            </td>
                            <td>{{ $ticket->user->name ?? 'N/A' }}</td>
                            <td>{{ $ticket->assignedAgent->name ?? 'Unassigned' }}</td>
                            <td>{{ $ticket->created_at->diffForHumans() }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.support.show', $ticket) }}" class="btn btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.support.edit', $ticket) }}" class="btn btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.support.destroy', $ticket) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">No tickets found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $tickets->links() }}
        </div>
    </div>
</div>
@endsection