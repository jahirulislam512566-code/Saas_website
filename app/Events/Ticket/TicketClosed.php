<?php

namespace App\Events\Ticket;

use App\Models\Ticket;
use App\Events\Event;

class TicketClosed extends Event
{
    public Ticket $ticket;

    public function __construct(Ticket $ticket)
    {
        parent::__construct();
        $this->ticket = $ticket;
    }

    public function eventName(): string
    {
        return 'ticket.closed';
    }

    public function payload(): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'subject' => $this->ticket->subject,
            'customer_id' => $this->ticket->customer_id,
            'closed_at' => $this->occurred_at->toDateTimeString(),
        ];
    }
}