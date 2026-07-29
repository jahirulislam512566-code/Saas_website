{{-- app/Mail/JobApplicationMail.php --}}
<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class JobApplicationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Job Application - ' . $this->data['position'],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.job-application',
            with: ['data' => $this->data],
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromPath(storage_path('app/public/' . $this->data['resume_path']))
                ->as('resume.pdf')
                ->withMime('application/pdf'),
        ];
    }
}