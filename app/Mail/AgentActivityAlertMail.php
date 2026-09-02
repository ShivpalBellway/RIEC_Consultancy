<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AgentActivityAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $agentName,
        public string $actionTitle,
        public string $description,
        public array $details = []
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "[Agent Portal Alert] {$this->actionTitle} by {$this->agentName}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.agent_activity_alert',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
