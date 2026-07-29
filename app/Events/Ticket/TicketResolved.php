<?php

namespace App\Events\Ticket;

use App\Models\Ticket;
use App\Events\Event;

class TicketResolved extends Event
{
    public Ticket $ticket;
    public string $resolution;

    public function __construct(Ticket $ticket, string $resolution = '')
    {
        parent::__construct();
        $this->ticket = $ticket;
        $this->resolution = $resolution;
    }

    public function eventName(): string
    {
        return 'ticket.resolved';
    }

    public function payload(): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'subject' => $this->ticket->subject,
            'customer_id' => $this->ticket->customer_id,
            'resolution' => $this->resolution,
            'resolved_at' => $this->occurred_at->toDateTimeString(),
        ];
    }
}