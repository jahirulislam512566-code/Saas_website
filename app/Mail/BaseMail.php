<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

abstract class BaseMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->getSubject(),
            from: $this->getFromAddress(),
            replyTo: $this->getReplyToAddress(),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: $this->getView(),
            with: $this->getViewData(),
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return $this->getAttachments();
    }

    /**
     * Get email subject.
     */
    abstract protected function getSubject(): string;

    /**
     * Get email view.
     */
    abstract protected function getView(): string;

    /**
     * Get view data.
     */
    protected function getViewData(): array
    {
        return [];
    }

    /**
     * Get from address.
     */
    protected function getFromAddress(): array
    {
        return [
            'address' => config('mail.from.address'),
            'name' => config('mail.from.name'),
        ];
    }

    /**
     * Get reply-to address.
     */
    protected function getReplyToAddress(): ?array
    {
        return null;
    }

    /**
     * Get attachments.
     */
    protected function getAttachments(): array
    {
        return [];
    }
}