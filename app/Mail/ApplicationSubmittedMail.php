<?php

namespace App\Mail;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class ApplicationSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $attachmentsArray;

    /**
     * Create a new message instance.
     */
    public function __construct(Application $application, array $attachmentsArray = [])
    {
        $this->application = $application;
        $this->attachmentsArray = $attachmentsArray; // each item: ['path' => '...', 'name' => '...', 'mime' => '...']
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Application Submitted: ' . $this->application->name . ' - ' . ($this->application->program?->name ?? ''),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.application_submitted',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];
        foreach ($this->attachmentsArray as $file) {
            if (isset($file['path']) && file_exists($file['path'])) {
                $attachments[] = Attachment::fromPath($file['path'])
                    ->as($file['name'] ?? basename($file['path']))
                    ->withMime($file['mime'] ?? null);
            }
        }
        return $attachments;
    }
}
