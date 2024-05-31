<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationEdited extends Mailable
{
    use Queueable, SerializesModels;

    public $reservation;

    /**
     * Vytvorenie novej inštancie správy.
     *
     * @param $reservation Inštancia rezervácie
     * @return void
     */
    public function __construct($reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Získanie obálky správy.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Rezervácia upravená', // Predmet správy
        );
    }

    /**
     * Získanie definície obsahu správy.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reservation-edit', // Pohľad pre obsah správy
        );
    }

    /**
     * Vytvorenie správy.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Rezervácia upravená')->view('emails.reservation_edited'); // Nastavenie predmetu a pohľadu pre email
    }
}
