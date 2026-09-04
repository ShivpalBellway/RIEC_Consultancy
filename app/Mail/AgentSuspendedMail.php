<?php

namespace App\Mail;

use App\Models\Agent;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AgentSuspendedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Agent $agent, public string $reason) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Agent Account Has Been Suspended – REIAC Global',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.agent_suspended',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
