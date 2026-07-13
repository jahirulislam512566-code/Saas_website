@extends('admin.layouts.admin')

@section('title', 'Ticket #' . $ticket->ticket_number)

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <a href="{{ route('admin.tickets.index') }}" class="text-gray-500 hover:text-gray-700">Tickets</a>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">#{{ $ticket->ticket_number }}</span>
        </div>
    </li>
@endsection

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Ticket Header -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">
                        Ticket #{{ $ticket->ticket_number }}
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">{{ $ticket->subject }}</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $ticket->status_badge }}">
                        {{ ucfirst($ticket->status) }}
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $ticket->priority_badge }}">
                        {{ ucfirst($ticket->priority) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-xs text-gray-500">Created By</p>
                    <p class="text-sm font-medium text-gray-900">{{ $ticket->user->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Department</p>
                    <p class="text-sm font-medium text-gray-900">{{ $ticket->department->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Assigned To</p>
                    <p class="text-sm font-medium text-gray-900">{{ $ticket->assignedAgent->name ?? 'Unassigned' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Created</p>
                    <p class="text-sm font-medium text-gray-900">{{ $ticket->created_at->format('M d, Y g:i A') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Ticket Message -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-sm font-medium text-gray-900">Ticket Message</h3>
        </div>
        <div class="p-6">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                        <span class="text-sm font-medium text-gray-600">
                            {{ substr($ticket->user->name ?? 'S', 0, 2) }}
                        </span>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="flex items-center space-x-2">
                        <p class="text-sm font-medium text-gray-900">{{ $ticket->user->name ?? 'System' }}</p>
                        <span class="text-xs text-gray-500">{{ $ticket->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="mt-2 prose prose-sm max-w-none">
                        {!! nl2br(e($ticket->message)) !!}
                    </div>
                    @if($ticket->attachments)
                        <div class="mt-3 flex items-center space-x-2">
                            <i class="fas fa-paperclip text-gray-400"></i>
                            <span class="text-sm text-gray-500">{{ count($ticket->attachments) }} attachment(s)</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Replies -->
    @if($ticket->replies->count() > 0)
        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-sm font-medium text-gray-900">
                    Replies ({{ $ticket->replies->count() }})
                </h3>
            </div>
            <div class="p-6 space-y-6">
                @foreach($ticket->replies as $reply)
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                                <span class="text-sm font-medium text-gray-600">
                                    {{ substr($reply->user->name ?? 'S', 0, 2) }}
                                </span>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center space-x-2">
                                <p class="text-sm font-medium text-gray-900">{{ $reply->user->name ?? 'System' }}</p>
                                <span class="text-xs text-gray-500">{{ $reply->created_at->diffForHumans() }}</span>
                                @if($reply->is_internal)
                                    <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded-full">Internal</span>
                                @endif
                            </div>
                            <div class="mt-2 prose prose-sm max-w-none">
                                {!! nl2br(e($reply->message)) !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Reply Form -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-sm font-medium text-gray-900">Add Reply</h3>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.tickets.reply', $ticket) }}" method="POST">
                @csrf
                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                    <textarea name="message" id="message" rows="4"
                              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                              placeholder="Type your reply here..."
                              required></textarea>
                </div>
                <div class="mt-4 flex items-center justify-between">
                    <div>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_internal" value="1" 
                                   class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <span class="ml-2 text-sm text-gray-700">Internal Note</span>
                        </label>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('admin.tickets.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            Back
                        </a>
                        <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                            <i class="fas fa-reply mr-2"></i> Reply
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection