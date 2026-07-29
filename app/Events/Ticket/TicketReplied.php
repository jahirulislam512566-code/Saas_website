<?php

namespace App\Events\Ticket;

use App\Models\Ticket;
use App\Models\TicketReply;
use App\Events\Event;

class TicketReplied extends Event
{
    public Ticket $ticket;
    public TicketReply $reply;
    public bool $isInternal;

    public function __construct(Ticket $ticket, TicketReply $reply, bool $isInternal = false)
    {
        parent::__construct();
        $this->ticket = $ticket;
        $this->reply = $reply;
        $this->isInternal = $isInternal;
    }

    public function eventName(): string
    {
        return 'ticket.replied';
    }

    public function payload(): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'reply_id' => $this->reply->id,
            'reply_by' => $this->reply->user_id,
            'is_internal' => $this->isInternal,
            'replied_at' => $this->occurred_at->toDateTimeString(),
        ];
    }
}