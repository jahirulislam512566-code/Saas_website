<?php

namespace App\Actions\Ticket;

use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Notifications\TicketCreatedNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class CreateTicketAction
{
    /**
     * Create a new support ticket
     *
     * @param array $data
     * @return Ticket
     */
    public function execute(array $data): Ticket
    {
        return DB::transaction(function () use ($data) {
            $ticket = Ticket::create([
                'ticket_number' => $this->generateTicketNumber(),
                'subject' => $data['subject'],
                'description' => $data['description'],
                'category_id' => $data['category_id'] ?? null,
                'priority' => $data['priority'] ?? 'medium', // low, medium, high, urgent
                'status' => 'open',
                'customer_id' => $data['customer_id'] ?? auth()->id(),
                'assigned_to' => $data['assigned_to'] ?? null,
                'created_by' => auth()->id(),
            ]);

            // Attach files if provided
            if (isset($data['attachments']) && is_array($data['attachments'])) {
                $this->attachFiles($ticket, $data['attachments']);
            }

            // Send notification
            if ($data['notify_customer'] ?? true) {
                Notification::send($ticket->customer, new TicketCreatedNotification($ticket));
            }

            // Auto-assign based on category
            if (!$data['assigned_to'] && ($data['auto_assign'] ?? true)) {
                $this->autoAssign($ticket);
            }

            Log::info('Support ticket created', [
                'ticket_id' => $ticket->id,
                'ticket_number' => $ticket->ticket_number,
                'subject' => $ticket->subject,
                'priority' => $ticket->priority,
                'customer_id' => $ticket->customer_id,
                'created_by' => auth()->id()
            ]);

            return $ticket->load(['category', 'customer', 'assignedTo']);
        });
    }

    private function generateTicketNumber(): string
    {
        $prefix = 'TCK';
        $date = date('Ymd');
        $lastTicket = Ticket::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();

        $number = $lastTicket ? intval(substr($lastTicket->ticket_number, -4)) + 1 : 1;
        
        return sprintf('%s-%s-%04d', $prefix, $date, $number);
    }

    private function attachFiles(Ticket $ticket, array $files): void
    {
        foreach ($files as $file) {
            // Upload and attach file logic
            // $ticket->attachments()->create(['file_path' => $path]);
        }
    }

    private function autoAssign(Ticket $ticket): void
    {
        // Find available agent for category
        $agent = $this->findAvailableAgent($ticket->category_id);
        
        if ($agent) {
            $ticket->update([
                'assigned_to' => $agent->id,
                'assigned_at' => now(),
            ]);

            Log::info('Ticket auto-assigned', [
                'ticket_id' => $ticket->id,
                'agent_id' => $agent->id
            ]);
        }
    }

    private function findAvailableAgent($categoryId)
    {
        // Logic to find available agent
        return null;
    }
}