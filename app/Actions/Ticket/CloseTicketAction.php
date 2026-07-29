<?php

namespace App\Actions\Ticket;

use App\Models\Ticket;
use App\Notifications\TicketClosedNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class CloseTicketAction
{
    /**
     * Close a ticket
     *
     * @param Ticket $ticket
     * @param array $data
     * @return Ticket
     * @throws \Exception
     */
    public function execute(Ticket $ticket, array $data = []): Ticket
    {
        return DB::transaction(function () use ($ticket, $data) {
            // Validate ticket can be closed
            if ($ticket->status === 'closed') {
                throw new \Exception('Ticket is already closed.');
            }

            $oldStatus = $ticket->status;

            // Update ticket
            $ticket->update([
                'status' => 'closed',
                'closed_at' => now(),
                'resolution_notes' => $data['notes'] ?? null,
                'closed_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            // Send notification
            if ($data['notify_customer'] ?? true) {
                Notification::send($ticket->customer, new TicketClosedNotification($ticket));
            }

            Log::info('Ticket closed', [
                'ticket_id' => $ticket->id,
                'ticket_number' => $ticket->ticket_number,
                'old_status' => $oldStatus,
                'new_status' => 'closed',
                'closed_by' => auth()->id()
            ]);

            return $ticket->fresh();
        });
    }
}