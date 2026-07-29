<?php

namespace App\Notifications\Ticket;

use App\Models\Ticket;
use App\Models\TicketReply;
use App\Notifications\BaseNotification;
use Illuminate\Notifications\Messages\MailMessage;

class TicketReplyNotification extends BaseNotification
{
    public Ticket $ticket;
    public TicketReply $reply;

    public function __construct(Ticket $ticket, TicketReply $reply)
    {
        $this->ticket = $ticket;
        $this->reply = $reply;
    }

    protected function buildMailMessage($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("New Reply - Ticket #{$this->ticket->ticket_number}")
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A new reply has been added to your ticket.')
            ->line('**Ticket:** ' . $this->ticket->subject)
            ->line('**Reply:**')
            ->line(new HtmlString(nl2br(e($this->reply->message))))
            ->action('View Ticket', route('customer.tickets.show', $this->ticket))
            ->line('You can reply directly to this email to respond.');
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => "New Reply - Ticket #{$this->ticket->ticket_number}",
            'message' => 'A new reply has been added to your ticket: ' . $this->ticket->subject,
            'type' => 'ticket_reply',
            'icon' => 'fa-reply',
            'color' => 'info',
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'reply_id' => $this->reply->id,
            'reply_message' => $this->reply->message,
            'action_url' => route('customer.tickets.show', $this->ticket),
            'action_text' => 'View Ticket',
        ];
    }
}