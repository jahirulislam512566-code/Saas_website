<?php

namespace App\Actions\Ticket;

use App\Models\Ticket;
use App\Models\TicketReply;
use App\Notifications\TicketReplyNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class ReplyTicketAction
{
    /**
     * Reply to a ticket
     *
     * @param Ticket $ticket
     * @param array $data
     * @return TicketReply
     * @throws \Exception
     */
    public function execute(Ticket $ticket, array $data): TicketReply
    {
        return DB::transaction(function () use ($ticket, $data) {
            // Validate ticket is open
            if ($ticket->status === 'closed') {
                throw new \Exception('Cannot reply to a closed ticket.');
            }

            // Create reply
            $reply = TicketReply::create([
                'ticket_id' => $ticket->id,
                'user_id' => auth()->id(),
                'message' => $data['message'],
                'is_internal' => $data['is_internal'] ?? false,
                'attachments' => $data['attachments'] ?? [],
            ]);

            // Update ticket status
            $oldStatus = $ticket->status;
            
            if ($ticket->status === 'open' && ($data['is_internal'] ?? false)) {
                // Internal note, don't change status
            } elseif ($ticket->status === 'waiting') {
                $ticket->update([
                    'status' => 'open',
                    'updated_by' => auth()->id(),
                ]);
            }

            // Send notification
            if (!($data['is_internal'] ?? false)) {
                $this->sendNotification($ticket, $reply);
            }

            Log::info('Ticket reply added', [
                'ticket_id' => $ticket->id,
                'ticket_number' => $ticket->ticket_number,
                'reply_id' => $reply->id,
                'is_internal' => $reply->is_internal,
                'user_id' => auth()->id()
            ]);

            return $reply;
        });
    }

    private function sendNotification(Ticket $ticket, TicketReply $reply): void
    {
        $recipients = [];
        
        if ($ticket->customer_id) {
            $recipients[] = $ticket->customer;
        }
        
        if ($ticket->assigned_to) {
            $recipients[] = $ticket->assignedTo;
        }

        if (!empty($recipients)) {
            Notification::send($recipients, new TicketReplyNotification($ticket, $reply));
        }
    }
}