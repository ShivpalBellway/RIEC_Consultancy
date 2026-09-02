<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AgentStudentUpdateMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $agentName;
    public string $actionType;   // status_updated | document_verified | document_rejected | university_assigned
    public string $actionTitle;
    public string $studentName;
    public string $message;
    public array  $details;
    public string $portalLink;

    public function __construct(
        string $agentName,
        string $actionType,
        string $actionTitle,
        string $studentName,
        string $message,
        string $portalLink,
        array  $details = []
    ) {
        $this->agentName   = $agentName;
        $this->actionType  = $actionType;
        $this->actionTitle = $actionTitle;
        $this->studentName = $studentName;
        $this->message     = $message;
        $this->portalLink  = $portalLink;
        $this->details     = $details;
    }

    public function envelope(): Envelope
    {
        $subjectPrefixes = [
            'status_updated'       => '📋 Application Status Update',
            'document_verified'    => '✅ Document Verified',
            'document_rejected'    => '❌ Document Review Required',
            'university_assigned'  => '🏛️ University Assigned',
        ];

        $prefix = $subjectPrefixes[$this->actionType] ?? '📩 Portal Update';

        return new Envelope(
            subject: "{$prefix} – {$this->studentName} | REIAC Global"
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.agent_student_update',
        );
    }
}
