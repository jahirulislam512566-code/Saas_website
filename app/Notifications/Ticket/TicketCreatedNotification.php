<?php

namespace App\Notifications\Ticket;

use App\Models\Ticket;
use App\Notifications\BaseNotification;
use Illuminate\Notifications\Messages\MailMessage;

class TicketCreatedNotification extends BaseNotification
{
    public Ticket $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    protected function buildMailMessage($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Support Ticket #{$this->ticket->ticket_number}")
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A new support ticket has been created.')
            ->line('**Subject:** ' . $this->ticket->subject)
            ->line('**Priority:** ' . ucfirst($this->ticket->priority))
            ->line('**Status:** ' . ucfirst($this->ticket->status))
            ->action('View Ticket', route('customer.tickets.show', $this->ticket))
            ->line('Our team will respond to you as soon as possible.');
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => "Ticket #{$this->ticket->ticket_number}",
            'message' => $this->ticket->subject,
            'type' => 'ticket_created',
            'icon' => 'fa-ticket-alt',
            'color' => 'info',
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'priority' => $this->ticket->priority,
            'status' => $this->ticket->status,
            'action_url' => route('customer.tickets.show', $this->ticket),
            'action_text' => 'View Ticket',
        ];
    }
}