<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Panier;
use App\Models\Produit;

class PanierController extends Controller
{
    // ─── Afficher le panier ───
    public function index()
    {
        $articles = Panier::with('produit')
            ->where('id_utilisateur', session('utilisateur_id'))
            ->get();

        $total = $articles->sum(function($article) {
            return $article->quantite * $article->produit->prix;
        });

        return view('panier', compact('articles', 'total'));
    }

    // ─── Ajouter au panier ───
    public function ajouter(Request $request)
    {
        $request->validate([
            'id_produit' => 'required|exists:produits,id',
            'quantite'   => 'required|integer|min:1',
        ]);

        $produit = Produit::findOrFail($request->id_produit);

        // Vérifie si le produit est déjà dans le panier
        $article = Panier::where('id_utilisateur', session('utilisateur_id'))
            ->where('id_produit', $request->id_produit)
            ->first();

        // Ne jamais dépasser le stock disponible
        $quantiteVoulue = ($article ? $article->quantite : 0) + $request->quantite;

        if ($produit->stock <= 0) {
            return back()->with('error', $produit->nom . ' est en rupture de stock.');
        }

        if ($quantiteVoulue > $produit->stock) {
            return back()->with('error', 'Stock insuffisant : il ne reste que ' . $produit->stock
                . ' exemplaire(s) de ' . $produit->nom . '.');
        }

        if ($article) {
            // Augmente la quantité
            $article->update([
                'quantite' => $article->quantite + $request->quantite
            ]);
        } else {
            // Ajoute le produit
            Panier::create([
                'id_utilisateur' => session('utilisateur_id'),
                'id_produit'     => $request->id_produit,
                'quantite'       => $request->quantite,
            ]);
        }

        return redirect()->route('panier')
            ->with('success', $produit->nom . ' ajouté au panier !');
    }

    // ─── Modifier quantité ───
    public function modifier(Request $request)
    {
        $request->validate([
            'id_article' => 'required|exists:panier,id',
            'quantite'   => 'required|integer|min:1',
        ]);

        $article = Panier::where('id', $request->id_article)
            ->where('id_utilisateur', session('utilisateur_id'))
            ->firstOrFail();

        // Ne jamais dépasser le stock disponible
        if ($request->quantite > $article->produit->stock) {
            return redirect()->route('panier')->with('error', 'Stock insuffisant : il ne reste que '
                . $article->produit->stock . ' exemplaire(s) de ' . $article->produit->nom . '.');
        }

        $article->update(['quantite' => $request->quantite]);

        return redirect()->route('panier')
            ->with('success', 'Quantité mise à jour !');
    }

    // ─── Supprimer du panier ───
    public function supprimer(Request $request)
    {
        $request->validate([
            'id_article' => 'required|exists:panier,id',
        ]);

        $article = Panier::where('id', $request->id_article)
            ->where('id_utilisateur', session('utilisateur_id'))
            ->firstOrFail();

        $article->delete();

        return redirect()->route('panier')
            ->with('success', 'Article supprimé du panier !');
    }
}