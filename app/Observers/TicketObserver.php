<?php

namespace App\Observers;

use App\Models\Ticket;
use App\Enums\TicketStatus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TicketObserver extends BaseObserver
{
    /**
     * Handle the Ticket "creating" event.
     */
    public function creating(Ticket $ticket): void
    {
        // Generate ticket number
        if (empty($ticket->ticket_number)) {
            $ticket->ticket_number = $this->generateTicketNumber();
        }

        // Set default status
        if (empty($ticket->status)) {
            $ticket->status = TicketStatus::OPEN->value;
        }

        // Set default priority
        if (empty($ticket->priority)) {
            $ticket->priority = 'medium';
        }

        $this->log('creating', $ticket);
    }

    /**
     * Handle the Ticket "created" event.
     */
    public function created(Ticket $ticket): void
    {
        Cache::forget('tickets_count');
        Cache::forget('tickets_list');
        
        $this->createAuditLog($ticket, 'created');

        $this->log('created', $ticket);
    }

    /**
     * Handle the Ticket "updating" event.
     */
    public function updating(Ticket $ticket): void
    {
        // Track status changes
        if ($ticket->isDirty('status')) {
            $oldStatus = $ticket->getOriginal('status');
            $newStatus = $ticket->status;
            
            $this->validateStatusTransition($oldStatus, $newStatus);
            $this->logStatusChange($ticket, $oldStatus, $newStatus);
        }

        // Track priority changes
        if ($ticket->isDirty('priority')) {
            $oldPriority = $ticket->getOriginal('priority');
            $newPriority = $ticket->priority;
            
            $this->logPriorityChange($ticket, $oldPriority, $newPriority);
        }

        $this->log('updating', $ticket);
    }

    /**
     * Handle the Ticket "updated" event.
     */
    public function updated(Ticket $ticket): void
    {
        Cache::forget("ticket_{$ticket->id}");
        Cache::forget("ticket_{$ticket->ticket_number}");
        Cache::forget('tickets_list');
        
        $this->createAuditLog($ticket, 'updated', $ticket->getChanges());

        $this->log('updated', $ticket);
    }

    /**
     * Handle the Ticket "deleting" event.
     */
    public function deleting(Ticket $ticket): void
    {
        // Check if ticket can be deleted
        if ($ticket->status !== TicketStatus::CLOSED->value) {
            throw new \Exception('Cannot delete an open ticket. Please close it first.');
        }

        $this->log('deleting', $ticket);
    }

    /**
     * Handle the Ticket "deleted" event.
     */
    public function deleted(Ticket $ticket): void
    {
        Cache::forget("ticket_{$ticket->id}");
        Cache::forget('tickets_count');
        Cache::forget('tickets_list');

        $this->log('deleted', $ticket);
    }

    /**
     * Handle the Ticket "restored" event.
     */
    public function restored(Ticket $ticket): void
    {
        Cache::forget("ticket_{$ticket->id}");
        Cache::forget('tickets_list');

        $this->log('restored', $ticket);
    }

    /**
     * Handle the Ticket "forceDeleted" event.
     */
    public function forceDeleted(Ticket $ticket): void
    {
        Cache::forget("ticket_{$ticket->id}");
        Cache::forget('tickets_count');
        Cache::forget('tickets_list');

        Log::warning('Ticket permanently deleted', [
            'ticket_id' => $ticket->id,
            'ticket_number' => $ticket->ticket_number,
            'deleted_by' => $this->getUserId(),
        ]);
    }

    /**
     * Generate unique ticket number.
     */
    protected function generateTicketNumber(): string
    {
        $prefix = 'TCK';
        $date = date('Ymd');
        $lastTicket = Ticket::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();

        $number = $lastTicket ? intval(substr($lastTicket->ticket_number, -4)) + 1 : 1;
        
        return sprintf('%s-%s-%04d', $prefix, $date, $number);
    }

    /**
     * Validate status transition.
     */
    protected function validateStatusTransition(string $oldStatus, string $newStatus): void
    {
        $allowedTransitions = [
            'open' => ['in_progress', 'waiting', 'closed'],
            'in_progress' => ['waiting', 'resolved', 'closed'],
            'waiting' => ['open', 'in_progress', 'closed'],
            'on_hold' => ['open', 'in_progress', 'closed'],
            'resolved' => ['closed', 'reopened'],
            'closed' => ['reopened'],
            'escalated' => ['open', 'in_progress', 'closed'],
            'reopened' => ['open', 'in_progress', 'resolved'],
        ];

        if (!in_array($newStatus, $allowedTransitions[$oldStatus] ?? [])) {
            throw new \Exception("Invalid status transition from {$oldStatus} to {$newStatus}");
        }
    }

    /**
     * Log status change.
     */
    protected function logStatusChange(Ticket $ticket, string $oldStatus, string $newStatus): void
    {
        Log::info('Ticket status changed', [
            'ticket_id' => $ticket->id,
            'ticket_number' => $ticket->ticket_number,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'changed_by' => $this->getUserId(),
        ]);
    }

    /**
     * Log priority change.
     */
    protected function logPriorityChange(Ticket $ticket, string $oldPriority, string $newPriority): void
    {
        Log::info('Ticket priority changed', [
            'ticket_id' => $ticket->id,
            'ticket_number' => $ticket->ticket_number,
            'old_priority' => $oldPriority,
            'new_priority' => $newPriority,
            'changed_by' => $this->getUserId(),
        ]);
    }

    /**
     * Create audit log.
     */
    protected function createAuditLog(Ticket $ticket, string $action, array $changes = []): void
    {
        // Create audit log entry if you have an AuditLog model
        // \App\Models\AuditLog::create([
        //     'model_type' => get_class($ticket),
        //     'model_id' => $ticket->id,
        //     'action' => $action,
        //     'changes' => $changes,
        //     'user_id' => $this->getUserId(),
        //     'ip_address' => $this->getIpAddress(),
        // ]);
    }
}