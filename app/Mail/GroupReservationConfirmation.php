<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\GroupReservation;


class GroupReservationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $groupReservation; // Change variable name to singular

    /**
     * Create a new message instance.
     */
    public function __construct(GroupReservation $groupReservation)
    {
        $this->groupReservation = $groupReservation; // Change variable name to singular
    }

    public function build()
    {
        return $this->subject('Group Reservation successful')
                    ->view('emails.group_reservation_confirmation');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Group Reservation Confirmation',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.group_reservation_confirmation',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
