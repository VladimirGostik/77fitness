<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserNotificationEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $subject;
    public $content;

    /**
     * Vytvorenie novej inštancie správy.
     */
    public function __construct($subject, $content)
    {
        $this->subject = $subject;
        $this->content = $content;
    }

    public function build()
    {
        return $this->subject($this->subject) // Nastavenie predmetu emailu
                    ->view('emails.user_notification'); // Nastavenie pohľadu pre email
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
