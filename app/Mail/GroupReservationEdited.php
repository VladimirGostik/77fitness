<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\GroupReservation;

class GroupReservationEdited extends Mailable
{
    use Queueable, SerializesModels;

    public $groupReservation; // Zmena názvu premennej na jednotné číslo

    /**
     * Vytvorenie novej inštancie správy.
     */
    public function __construct(GroupReservation $groupReservation)
    {
        $this->groupReservation = $groupReservation; // Zmena názvu premennej na jednotné číslo
    }

    public function build()
    {
        return $this->subject('Group Reservation Edited') // Nastavenie predmetu emailu
                    ->view('emails.group_reservation_edited'); // Nastavenie pohľadu pre email
    }

    /**
     * Získanie obálky správy.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Group Reservation Edited', // Predmet správy
        );
    }

    /**
     * Získanie definície obsahu správy.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.group_reservation_edited', // Pohľad pre obsah správy
        );
    }

    /**
     * Získanie príloh pre správu.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return []; // Žiadne prílohy
    }
}
