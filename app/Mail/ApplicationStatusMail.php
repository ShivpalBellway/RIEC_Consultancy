<?php

namespace App\Mail;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public Application $application;
    public string $statusLabel;

    /**
     * Create a new message instance.
     */
    public function __construct(Application $application, string $statusLabel)
    {
        $this->application  = $application;
        $this->statusLabel  = $statusLabel;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Application Status Update — ' . $this->statusLabel,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.application_status',
        );
    }

    /**
     * No file attachments for status emails.
     */
    public function attachments(): array
    {
        return [];
    }
}
