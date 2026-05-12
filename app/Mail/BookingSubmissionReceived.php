<?php

namespace App\Mail;

use App\Models\BookingSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingSubmissionReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public BookingSubmission $submission) {}

    public function envelope(): Envelope
    {
        $replyTo = $this->replyToAddress();

        return new Envelope(
            replyTo: $replyTo ? [$replyTo] : [],
            subject: 'Nuova richiesta prenotazione '.$this->submission->reference,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-submission-received',
        );
    }

    /**
     * @return array<int, mixed>
     */
    public function attachments(): array
    {
        return [];
    }

    private function replyToAddress(): ?Address
    {
        $email = $this->submission->fieldValue('email');

        if (! $email || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return null;
        }

        $name = $this->submission->fieldValue('nome') ?: null;

        return new Address($email, $name);
    }
}
