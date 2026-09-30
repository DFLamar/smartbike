<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;

class ContactController extends Controller
{
    // ─── Afficher page contact ───
    public function index()
    {
        return view('contact.index');
    }

    // ─── Traiter le formulaire ───
    public function envoyer(Request $request)
    {
        $request->validate([
            'nom'     => 'required|string|max:100',
            'email'   => 'required|email',
            'sujet'   => 'required|string|max:150',
            'message' => 'required|string|min:10',
        ]);

        // Envoyer l'email à l'admin
        Mail::to(env('MAIL_USERNAME'))->send(new ContactMail(
            $request->nom,
            $request->email,
            $request->sujet,
            $request->message
        ));

        return redirect()->route('contact')
            ->with('success', 'Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.');
    }
}