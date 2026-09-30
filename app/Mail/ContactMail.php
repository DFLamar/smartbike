<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    public $nom;
    public $email;
    public $sujet;
    public $message;

    public function __construct($nom, $email, $sujet, $message)
    {
        $this->nom     = $nom;
        $this->email   = $email;
        $this->sujet   = $sujet;
        $this->message = $message;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nouveau message de contact — SmartBike',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact',
        );
    }
}