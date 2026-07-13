<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SupportController extends Controller
{
    /**
     * Display a listing of support tickets.
     */
    public function index(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;

        $query = Ticket::forTenant($tenantId)->with(['user', 'department', 'assignedAgent']);

        // Filter by status
        if ($request->filled('status')) {
            $query->status($request->status);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->priority($request->priority);
        }

        // Filter by department
        if ($request->filled('department')) {
            $query->where('department_id', $request->department);
        }

        // Search by subject or message
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $tickets = $query->latest()->paginate(15);
        $departments = Department::forTenant($tenantId)->active()->get();
        $statuses = ['open', 'pending', 'resolved', 'closed'];
        $priorities = ['low', 'medium', 'high', 'critical'];

        return view('admin.support.index', compact('tickets', 'departments', 'statuses', 'priorities'));
    }

    /**
     * Show the form for creating a new ticket.
     */
    public function create()
    {
        $tenantId = Auth::user()->tenant_id;
        $departments = Department::forTenant($tenantId)->active()->get();
        $priorities = ['low', 'medium', 'high', 'critical'];
        $users = User::forTenant($tenantId)->get();

        return view('admin.support.create', compact('departments', 'priorities', 'users'));
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
        ]);

        return redirect()->route('admin.support.index')
            ->with('success', 'Ticket created successfully.');
    }

    /**
     * Display the specified ticket.
     */
    public function show(Ticket $ticket)
    {
        $this->authorizeTenant($ticket);
        $ticket->load(['user', 'department', 'assignedAgent', 'replies.user']);

        return view('admin.support.show', compact('ticket'));
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
        $users = User::forTenant($tenantId)->get();

        return view('admin.support.edit', compact('ticket', 'departments', 'priorities', 'statuses', 'users'));
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
        ]);

        $ticket->update($validated);

        return redirect()->route('admin.support.index')
            ->with('success', 'Ticket updated successfully.');
    }

    /**
     * Remove the specified ticket.
     */
    public function destroy(Ticket $ticket)
    {
        $this->authorizeTenant($ticket);
        $ticket->delete();

        return redirect()->route('admin.support.index')
            ->with('success', 'Ticket deleted successfully.');
    }

    /**
     * Reply to a ticket.
     */
    public function reply(Request $request, Ticket $ticket)
    {
        $this->authorizeTenant($ticket);

        $request->validate([
            'message' => ['required', 'string'],
        ]);

        $ticket->replies()->create([
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_internal' => $request->has('is_internal'),
        ]);

        // If status is closed and replying, reopen ticket
        if ($ticket->status === 'closed') {
            $ticket->update(['status' => 'open']);
        }

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

        return back()->with('success', 'Ticket priority updated successfully.');
    }

    /**
     * Export tickets.
     */
    public function export(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;

        $tickets = Ticket::forTenant($tenantId)
            ->with(['user', 'department', 'assignedAgent'])
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=tickets_' . date('Y-m-d') . '.csv',
        ];

        $callback = function () use ($tickets) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Subject', 'Department', 'Priority', 'Status', 'Created By', 'Assigned To', 'Created At']);

            foreach ($tickets as $ticket) {
                fputcsv($file, [
                    $ticket->id,
                    $ticket->subject,
                    $ticket->department->name ?? 'N/A',
                    $ticket->priority,
                    $ticket->status,
                    $ticket->user->name ?? 'N/A',
                    $ticket->assignedAgent->name ?? 'Unassigned',
                    $ticket->created_at->format('Y-m-d H:i:s'),
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

        return view('admin.support.pending', compact('tickets'));
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

        return view('admin.support.resolved', compact('tickets'));
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

        return view('admin.support.my-tickets', compact('tickets'));
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

        return view('admin.support.analytics', compact('stats'));
    }

    /**
     * Show ticket history.
     */
    public function history(Ticket $ticket)
    {
        $this->authorizeTenant($ticket);
        $ticket->load(['replies.user']);

        return view('admin.support.history', compact('ticket'));
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