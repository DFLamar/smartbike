<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReinitialisationMotDePasseMail extends Mailable
{
    use Queueable, SerializesModels;

    public $utilisateur;
    public $lien;

    public function __construct($utilisateur, $token)
    {
        $this->utilisateur = $utilisateur;
        $this->lien        = route('mdp.reinitialiser', [
            'token' => $token,
            'email' => $utilisateur->email,
        ]);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Réinitialisation de votre mot de passe — SmartBike',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reinitialisation',
        );
    }
}
