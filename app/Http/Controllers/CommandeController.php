<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commande;
use App\Models\LigneCommande;
use App\Models\Panier;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Mail;
use App\Mail\CommandeConfirmationMail;
use Stripe\StripeClient;
use Stripe\Exception\ApiErrorException;

class CommandeController extends Controller
{
    // ─── Afficher tunnel de commande ───
    public function index()
    {
        $articles = Panier::with('produit')
            ->where('id_utilisateur', session('utilisateur_id'))
            ->get();

        if ($articles->isEmpty()) {
            return redirect()->route('panier')
                ->with('error', 'Votre panier est vide.');
        }

        $total = $articles->sum(function($article) {
            return $article->quantite * $article->produit->prix;
        });

        return view('commande', compact('articles', 'total'));
    }

    // ─── Valider la commande ───
    public function store(Request $request)
    {
        $request->validate([
            'adresse_livraison' => 'required|string',
            'mode_livraison'    => 'required|in:standard,express',
        ]);

        $articles = Panier::with('produit')
            ->where('id_utilisateur', session('utilisateur_id'))
            ->get();

        if ($articles->isEmpty()) {
            return redirect()->route('panier')
                ->with('error', 'Votre panier est vide.');
        }

        // Revérifier le stock avant de lancer le paiement
        foreach ($articles as $article) {
            if ($article->quantite > $article->produit->stock) {
                return redirect()->route('panier')->with('error', 'Stock insuffisant pour '
                    . $article->produit->nom . ' (' . $article->produit->stock
                    . ' disponible(s)). Modifiez la quantité avant de commander.');
            }
        }

        // Calcul du total
        $total = $articles->sum(function($article) {
            return $article->quantite * $article->produit->prix;
        });

        // Frais de livraison express
        if ($request->mode_livraison === 'express') {
            $total += 9.99;
        }

        // Créer la commande
        $commande = Commande::create([
            'id_utilisateur'    => session('utilisateur_id'),
            'statut'            => 'en_attente',
            'total'             => $total,
            'adresse_livraison' => $request->adresse_livraison,
            'mode_livraison'    => $request->mode_livraison,
        ]);

        // Créer les lignes de commande
        foreach ($articles as $article) {
            LigneCommande::create([
                'id_commande'   => $commande->id,
                'id_produit'    => $article->id_produit,
                'quantite'      => $article->quantite,
                'prix_unitaire' => $article->produit->prix,
            ]);
        }

        // Articles envoyés à Stripe (montants en centimes)
        $lignesStripe = [];
        foreach ($articles as $article) {
            $lignesStripe[] = [
                'price_data' => [
                    'currency'     => 'eur',
                    'product_data' => ['name' => $article->produit->nom],
                    'unit_amount'  => (int) round($article->produit->prix * 100),
                ],
                'quantity' => $article->quantite,
            ];
        }

        if ($request->mode_livraison === 'express') {
            $lignesStripe[] = [
                'price_data' => [
                    'currency'     => 'eur',
                    'product_data' => ['name' => 'Livraison express'],
                    'unit_amount'  => 999,
                ],
                'quantity' => 1,
            ];
        }

        $utilisateur = Utilisateur::find(session('utilisateur_id'));

        // Créer la session de paiement Stripe
        try {
            $stripe = new StripeClient(config('services.stripe.secret'));
            $sessionStripe = $stripe->checkout->sessions->create([
                'mode'                => 'payment',
                'line_items'          => $lignesStripe,
                'customer_email'      => $utilisateur->email,
                'client_reference_id' => $commande->id,
                // {CHECKOUT_SESSION_ID} est remplacé par Stripe lui-même
                'success_url' => route('commande.succes') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'  => route('commande.annuler', $commande->id),
            ]);
        } catch (ApiErrorException $e) {
            $commande->update(['statut' => 'annulee']);
            return redirect()->route('commande')
                ->with('error', "Le paiement n'a pas pu être lancé. Veuillez réessayer.");
        }

        // Envoyer le client sur la page de paiement Stripe
        return redirect()->away($sessionStripe->url);
    }

    // ─── Retour de Stripe après paiement ───
    public function succes(Request $request)
    {
        try {
            $stripe = new StripeClient(config('services.stripe.secret'));
            $sessionStripe = $stripe->checkout->sessions->retrieve($request->session_id);
        } catch (ApiErrorException $e) {
            return redirect()->route('panier')->with('error', 'Paiement introuvable.');
        }

        // La commande doit appartenir à l'utilisateur connecté
        $commande = Commande::where('id', $sessionStripe->client_reference_id)
            ->where('id_utilisateur', session('utilisateur_id'))
            ->firstOrFail();

        if ($sessionStripe->payment_status !== 'paid') {
            return redirect()->route('commande')
                ->with('error', "Le paiement n'a pas été validé.");
        }

        // On passe la commande en 'payee' une seule fois.
        // Si la page est rechargée, elle est déjà 'payee' : $modifiee vaut 0 et on ne refait rien.
        $modifiee = Commande::where('id', $commande->id)
            ->whereIn('statut', ['en_attente', 'annulee'])
            ->update(['statut' => 'payee']);

        if ($modifiee) {
            $commande->load('lignesCommande.produit');

            // Diminuer le stock
            foreach ($commande->lignesCommande as $ligne) {
                $ligne->produit->decrement('stock', $ligne->quantite);
            }

            // Vider le panier
            Panier::where('id_utilisateur', session('utilisateur_id'))->delete();

            // Envoyer email de confirmation au client
            try {
                Mail::to($commande->utilisateur->email)->send(
                    new CommandeConfirmationMail($commande)
                );
            } catch (\Exception $e) {
                // Si l'email échoue, la commande est quand même payée
            }
        }

        return redirect()->route('commande.confirmation', $commande->id)
            ->with('success', 'Paiement accepté ! Un email de confirmation vous a été envoyé.');
    }

    // ─── Paiement annulé sur Stripe ───
    public function annuler($id)
    {
        Commande::where('id', $id)
            ->where('id_utilisateur', session('utilisateur_id'))
            ->where('statut', 'en_attente')
            ->update(['statut' => 'annulee']);

        return redirect()->route('commande')
            ->with('error', "Paiement annulé. Votre commande n'a pas été validée, votre panier est conservé.");
    }

    // ─── Page confirmation ───
    public function confirmation($id)
    {
        $commande = Commande::with('lignesCommande.produit')
            ->where('id', $id)
            ->where('id_utilisateur', session('utilisateur_id'))
            ->firstOrFail();

        return view('confirmation', compact('commande'));
    }
}