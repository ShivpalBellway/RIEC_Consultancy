<?php

namespace App\Mail;

use App\Models\Agent;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AgentAccountApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Agent $agent) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Agent Account Has Been Approved – REIAC Global',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.agent_account_approved',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
