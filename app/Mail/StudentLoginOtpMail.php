<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StudentLoginOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $studentName,
        public string $otp,
        public int $expiryMinutes,
        public string $purpose = 'login',
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->purpose === 'registration'
                ? 'Verify Your Student Email - REIAC'
                : 'Your Student Login OTP - REIAC'
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.student-login-otp');
    }

    public function attachments(): array
    {
        return [];
    }
}
