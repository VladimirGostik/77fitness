<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Reservation;
use App\Models\Client;
use App\Models\Trainer;

class ReservationSuccessful extends Mailable
{
    use Queueable, SerializesModels;
    public $reservation;

    /**
     * Vytvorenie novej inštancie správy.
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function build()
    {
        return $this->subject('Potvrdenie rezervácie') // Nastavenie predmetu emailu
                    ->view('emails.reservation-success'); // Nastavenie pohľadu pre email
    }

    /**
     * Získanie obálky správy.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Rezervácia úspešná', // Predmet správy
        );
    }

    /**
     * Získanie definície obsahu správy.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reservation-success', // Pohľad pre obsah správy
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
