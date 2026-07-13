<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Department;
use App\Models\User;
use App\Models\TicketReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    /**
     * Display a listing of tickets.
     */
    public function index(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;

        $query = Ticket::forTenant($tenantId)
            ->with(['user', 'department', 'assignedAgent'])
            ->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->status($request->status);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->priority($request->priority);
        }

        // Filter by department
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        // Search by subject, message, or ticket number
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%")
                  ->orWhere('ticket_number', 'like', "%{$search}%");
            });
        }

        $tickets = $query->paginate(15);
        $departments = Department::forTenant($tenantId)->active()->get();
        $statuses = ['open', 'pending', 'resolved', 'closed'];
        $priorities = ['low', 'medium', 'high', 'critical'];

        return view('admin.tickets.index', compact('tickets', 'departments', 'statuses', 'priorities'));
    }

    /**
     * Show the form for creating a new ticket.
     */
    public function create()
    {
        $tenantId = Auth::user()->tenant_id;
        $departments = Department::forTenant($tenantId)->active()->get();
        $priorities = ['low', 'medium', 'high', 'critical'];
        $users = User::when($tenantId, function ($query) use ($tenantId) {
            return $query->where('tenant_id', $tenantId);
        })->get();

        return view('admin.tickets.create', compact('departments', 'priorities', 'users'));
    }

    /**
     * Store a newly created ticket.
     */
    public function store(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;

        $validated = $request->validate([
            'department_id' => ['required', 'exists:departments,id'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'priority' => ['required', Rule::in(['low', 'medium', 'high', 'critical'])],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'category' => ['nullable', 'string', 'max:100'],
        ]);

        $ticket = Ticket::create([
            'tenant_id' => $tenantId,
            'user_id' => Auth::id(),
            'department_id' => $validated['department_id'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'priority' => $validated['priority'],
            'status' => 'open',
            'assigned_to' => $validated['assigned_to'] ?? null,
            'category' => $validated['category'] ?? null,
            'ticket_number' => $this->generateTicketNumber(),
        ]);

        // Log activity
        activity()
            ->performedOn($ticket)
            ->causedBy(Auth::user())
            ->withProperties(['action' => 'created'])
            ->log('Ticket created: ' . $ticket->ticket_number);

        return redirect()->route('admin.tickets.index')
            ->with('success', 'Ticket #' . $ticket->ticket_number . ' created successfully.');
    }

    /**
     * Display the specified ticket.
     */
    public function show(Ticket $ticket)
    {
        $this->authorizeTenant($ticket);
        $ticket->load(['user', 'department', 'assignedAgent', 'replies.user']);

        return view('admin.tickets.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified ticket.
     */
    public function edit(Ticket $ticket)
    {
        $this->authorizeTenant($ticket);

        $tenantId = Auth::user()->tenant_id;
        $departments = Department::forTenant($tenantId)->active()->get();
        $priorities = ['low', 'medium', 'high', 'critical'];
        $statuses = ['open', 'pending', 'resolved', 'closed'];
        $users = User::when($tenantId, function ($query) use ($tenantId) {
            return $query->where('tenant_id', $tenantId);
        })->get();

        return view('admin.tickets.edit', compact('ticket', 'departments', 'priorities', 'statuses', 'users'));
    }

    /**
     * Update the specified ticket.
     */
    public function update(Request $request, Ticket $ticket)
    {
        $this->authorizeTenant($ticket);

        $validated = $request->validate([
            'department_id' => ['required', 'exists:departments,id'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'priority' => ['required', Rule::in(['low', 'medium', 'high', 'critical'])],
            'status' => ['required', Rule::in(['open', 'pending', 'resolved', 'closed'])],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'category' => ['nullable', 'string', 'max:100'],
        ]);

        $oldStatus = $ticket->status;
        $ticket->update($validated);

        // If status changed to resolved, set resolved_at
        if ($oldStatus !== 'resolved' && $ticket->status === 'resolved') {
            $ticket->update(['resolved_at' => now()]);
        }

        // If status changed to closed, set closed_at
        if ($oldStatus !== 'closed' && $ticket->status === 'closed') {
            $ticket->update(['closed_at' => now()]);
        }

        // Log activity
        activity()
            ->performedOn($ticket)
            ->causedBy(Auth::user())
            ->withProperties(['action' => 'updated'])
            ->log('Ticket updated: ' . $ticket->ticket_number);

        return redirect()->route('admin.tickets.index')
            ->with('success', 'Ticket #' . $ticket->ticket_number . ' updated successfully.');
    }

    /**
     * Remove the specified ticket.
     */
    public function destroy(Ticket $ticket)
    {
        $this->authorizeTenant($ticket);

        // Log activity before deletion
        activity()
            ->performedOn($ticket)
            ->causedBy(Auth::user())
            ->withProperties(['action' => 'deleted'])
            ->log('Ticket deleted: ' . $ticket->ticket_number);

        $ticketNumber = $ticket->ticket_number;
        $ticket->delete();

        return redirect()->route('admin.tickets.index')
            ->with('success', 'Ticket #' . $ticketNumber . ' deleted successfully.');
    }

    /**
     * Reply to a ticket.
     */
    public function reply(Request $request, Ticket $ticket)
    {
        $this->authorizeTenant($ticket);

        $request->validate([
            'message' => ['required', 'string'],
            'is_internal' => ['nullable', 'boolean'],
            'attachments' => ['nullable', 'array'],
        ]);

        $reply = TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_internal' => $request->has('is_internal'),
            'is_customer_reply' => false,
            'attachments' => $request->attachments ?? [],
        ]);

        // If status is closed and replying, reopen ticket
        if ($ticket->status === 'closed') {
            $ticket->update(['status' => 'open', 'closed_at' => null]);
        }

        // Update response time if first reply
        if (!$ticket->response_time && $ticket->status !== 'closed') {
            $ticket->update(['response_time' => now()->diffInHours($ticket->created_at)]);
        }

        // Log activity
        activity()
            ->performedOn($ticket)
            ->causedBy(Auth::user())
            ->withProperties(['action' => 'replied'])
            ->log('Reply added to ticket: ' . $ticket->ticket_number);

        return back()->with('success', 'Reply added successfully.');
    }

    /**
     * Assign a ticket to a user.
     */
    public function assign(Request $request, Ticket $ticket)
    {
        $this->authorizeTenant($ticket);

        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $ticket->update(['assigned_to' => $request->user_id]);

        // Log activity
        activity()
            ->performedOn($ticket)
            ->causedBy(Auth::user())
            ->withProperties(['action' => 'assigned'])
            ->log('Ticket assigned to: ' . User::find($request->user_id)->name);

        return back()->with('success', 'Ticket assigned successfully.');
    }

    /**
     * Close a ticket.
     */
    public function close(Ticket $ticket)
    {
        $this->authorizeTenant($ticket);
        
        $ticket->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);

        // Log activity
        activity()
            ->performedOn($ticket)
            ->causedBy(Auth::user())
            ->withProperties(['action' => 'closed'])
            ->log('Ticket closed: ' . $ticket->ticket_number);

        return back()->with('success', 'Ticket closed successfully.');
    }

    /**
     * Reopen a ticket.
     */
    public function reopen(Ticket $ticket)
    {
        $this->authorizeTenant($ticket);
        
        $ticket->update([
            'status' => 'open',
            'closed_at' => null,
        ]);

        // Log activity
        activity()
            ->performedOn($ticket)
            ->causedBy(Auth::user())
            ->withProperties(['action' => 'reopened'])
            ->log('Ticket reopened: ' . $ticket->ticket_number);

        return back()->with('success', 'Ticket reopened successfully.');
    }

    /**
     * Resolve a ticket.
     */
    public function resolve(Ticket $ticket)
    {
        $this->authorizeTenant($ticket);
        
        $ticket->update([
            'status' => 'resolved',
            'resolved_at' => now(),
        ]);

        // Calculate resolution time
        if ($ticket->created_at) {
            $ticket->update(['resolution_time' => now()->diffInHours($ticket->created_at)]);
        }

        // Log activity
        activity()
            ->performedOn($ticket)
            ->causedBy(Auth::user())
            ->withProperties(['action' => 'resolved'])
            ->log('Ticket resolved: ' . $ticket->ticket_number);

        return back()->with('success', 'Ticket resolved successfully.');
    }

    /**
     * Update ticket priority.
     */
    public function priority(Request $request, Ticket $ticket)
    {
        $this->authorizeTenant($ticket);

        $request->validate([
            'priority' => ['required', Rule::in(['low', 'medium', 'high', 'critical'])],
        ]);

        $ticket->update(['priority' => $request->priority]);

        // Log activity
        activity()
            ->performedOn($ticket)
            ->causedBy(Auth::user())
            ->withProperties(['action' => 'priority_changed'])
            ->log('Ticket priority changed to: ' . $request->priority);

        return back()->with('success', 'Ticket priority updated successfully.');
    }

    /**
     * Export tickets to CSV.
     */
    public function export(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;

        $query = Ticket::forTenant($tenantId)
            ->with(['user', 'department', 'assignedAgent']);

        // Apply filters if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $tickets = $query->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=tickets_' . date('Y-m-d') . '.csv',
        ];

        $callback = function () use ($tickets) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, [
                'ID',
                'Ticket Number',
                'Subject',
                'Department',
                'Priority',
                'Status',
                'Created By',
                'Assigned To',
                'Category',
                'Created At',
                'Resolved At',
                'Closed At'
            ]);

            // Add data
            foreach ($tickets as $ticket) {
                fputcsv($file, [
                    $ticket->id,
                    $ticket->ticket_number,
                    $ticket->subject,
                    $ticket->department->name ?? 'N/A',
                    $ticket->priority,
                    $ticket->status,
                    $ticket->user->name ?? 'N/A',
                    $ticket->assignedAgent->name ?? 'Unassigned',
                    $ticket->category ?? 'N/A',
                    $ticket->created_at->format('Y-m-d H:i:s'),
                    $ticket->resolved_at ? $ticket->resolved_at->format('Y-m-d H:i:s') : 'N/A',
                    $ticket->closed_at ? $ticket->closed_at->format('Y-m-d H:i:s') : 'N/A',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show pending tickets.
     */
    public function pending()
    {
        $tenantId = Auth::user()->tenant_id;
        $tickets = Ticket::forTenant($tenantId)
            ->where('status', 'pending')
            ->with(['user', 'department', 'assignedAgent'])
            ->latest()
            ->paginate(15);

        return view('admin.tickets.pending', compact('tickets'));
    }

    /**
     * Show resolved tickets.
     */
    public function resolved()
    {
        $tenantId = Auth::user()->tenant_id;
        $tickets = Ticket::forTenant($tenantId)
            ->where('status', 'resolved')
            ->with(['user', 'department', 'assignedAgent'])
            ->latest()
            ->paginate(15);

        return view('admin.tickets.resolved', compact('tickets'));
    }

    /**
     * Show user's tickets.
     */
    public function myTickets()
    {
        $tenantId = Auth::user()->tenant_id;
        $tickets = Ticket::forTenant($tenantId)
            ->where('user_id', Auth::id())
            ->with(['user', 'department', 'assignedAgent'])
            ->latest()
            ->paginate(15);

        return view('admin.tickets.my-tickets', compact('tickets'));
    }

    /**
     * Show ticket analytics.
     */
    public function analytics()
    {
        $tenantId = Auth::user()->tenant_id;

        $stats = [
            'total' => Ticket::forTenant($tenantId)->count(),
            'open' => Ticket::forTenant($tenantId)->where('status', 'open')->count(),
            'pending' => Ticket::forTenant($tenantId)->where('status', 'pending')->count(),
            'resolved' => Ticket::forTenant($tenantId)->where('status', 'resolved')->count(),
            'closed' => Ticket::forTenant($tenantId)->where('status', 'closed')->count(),
            'avg_response_time' => Ticket::forTenant($tenantId)->whereNotNull('response_time')->avg('response_time'),
            'avg_resolution_time' => Ticket::forTenant($tenantId)->whereNotNull('resolution_time')->avg('resolution_time'),
        ];

        // Get tickets by priority
        $priorityStats = Ticket::forTenant($tenantId)
            ->select('priority', \DB::raw('count(*) as count'))
            ->groupBy('priority')
            ->get();

        // Get tickets by department
        $departmentStats = Ticket::forTenant($tenantId)
            ->select('department_id', \DB::raw('count(*) as count'))
            ->whereNotNull('department_id')
            ->groupBy('department_id')
            ->with('department')
            ->get();

        return view('admin.tickets.analytics', compact('stats', 'priorityStats', 'departmentStats'));
    }

    /**
     * Show ticket history.
     */
    public function history(Ticket $ticket)
    {
        $this->authorizeTenant($ticket);
        $ticket->load(['replies.user']);

        return view('admin.tickets.history', compact('ticket'));
    }

    /**
     * Generate a unique ticket number.
     */
    protected function generateTicketNumber()
    {
        $prefix = 'TKT';
        $year = date('Y');
        $month = date('m');
        
        $lastTicket = Ticket::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastTicket) {
            $lastNumber = (int) substr($lastTicket->ticket_number, -4);
            $number = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $number = '0001';
        }

        return $prefix . '-' . $year . $month . '-' . $number;
    }

    /**
     * Authorize that the ticket belongs to the current tenant.
     */
    protected function authorizeTenant(Ticket $ticket)
    {
        if ($ticket->tenant_id !== Auth::user()->tenant_id) {
            abort(403, 'Unauthorized action.');
        }
    }
}