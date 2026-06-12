<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;

class RegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function envelope(): Envelope
    {
        $fromAddress = env('MAIL_FROM_ADDRESS', 'default@example.com');
        $fromName = env('MAIL_FROM_NAME', 'Default Name');

        return new Envelope(
            from: new Address($fromAddress, $fromName),
            subject: 'Welcome Mail - Your registration has been created successfully.',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.registration',
            with: [
                'data' => $this->data,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
