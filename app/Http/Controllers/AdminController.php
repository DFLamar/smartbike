<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produit;
use App\Models\Categorie;
use App\Models\Commande;
use App\Models\Utilisateur;

class AdminController extends Controller
{
    // ─── Dashboard ───
    public function dashboard()
    {
        $nb_produits          = Produit::count();
        $nb_commandes_attente = Commande::where('statut', 'en_attente')->count();
        $chiffre_affaires     = Commande::whereIn('statut', ['payee', 'expediee'])->sum('total');
        $dernieres_commandes  = Commande::with('utilisateur')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'nb_produits',
            'nb_commandes_attente',
            'chiffre_affaires',
            'dernieres_commandes'
        ));
    }

    // ─── Liste produits ───
    public function produits()
    {
        $produits = Produit::with('categorie')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.produits', compact('produits'));
    }

    // ─── Formulaire ajout produit ───
    public function ajouterProduit()
    {
        $categories = Categorie::all();
        return view('admin.produit-form', compact('categories'));
    }

    // ─── Sauvegarder produit ───
    public function storeProduit(Request $request)
    {
        $request->validate([
            'nom'          => 'required|string|max:150',
            'id_categorie' => 'required|exists:categories,id',
            'description'  => 'nullable|string',
            'prix'         => 'required|numeric|min:0',
            'autonomie'    => 'required|integer|min:0',
            'puissance'    => 'required|integer|min:0',
            'poids'        => 'nullable|numeric',
            'stock'        => 'required|integer|min:0',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'image2'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'image3'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'image4'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        Produit::create([
            'nom'          => $request->nom,
            'id_categorie' => $request->id_categorie,
            'description'  => $request->description,
            'prix'         => $request->prix,
            'autonomie'    => $request->autonomie,
            'puissance'    => $request->puissance,
            'poids'        => $request->poids,
            'stock'        => $request->stock,
        ] + $this->imagesProduit($request));

        return redirect()->route('admin.produits')
            ->with('success', 'Produit ajouté avec succès !');
    }

    // ─── Formulaire modification produit ───
    public function modifierProduit($id)
    {
        $produit    = Produit::findOrFail($id);
        $categories = Categorie::all();
        return view('admin.produit-form', compact('produit', 'categories'));
    }

    // ─── Mettre à jour produit ───
    public function updateProduit(Request $request, $id)
    {
        $request->validate([
            'nom'          => 'required|string|max:150',
            'id_categorie' => 'required|exists:categories,id',
            'description'  => 'nullable|string',
            'prix'         => 'required|numeric|min:0',
            'autonomie'    => 'required|integer|min:0',
            'puissance'    => 'required|integer|min:0',
            'poids'        => 'nullable|numeric',
            'stock'        => 'required|integer|min:0',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'image2'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'image3'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'image4'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $produit = Produit::findOrFail($id);

        $produit->update([
            'nom'          => $request->nom,
            'id_categorie' => $request->id_categorie,
            'description'  => $request->description,
            'prix'         => $request->prix,
            'autonomie'    => $request->autonomie,
            'puissance'    => $request->puissance,
            'poids'        => $request->poids,
            'stock'        => $request->stock,
        ] + $this->imagesProduit($request, $produit));

        return redirect()->route('admin.produits')
            ->with('success', 'Produit modifié avec succès !');
    }

    // ─── Images du produit (jusqu'à 4 : image, image2, image3, image4) ───
    private function imagesProduit(Request $request, ?Produit $produit = null)
    {
        $images = [];

        foreach (['image', 'image2', 'image3', 'image4'] as $champ) {
            $chemin = $produit->$champ ?? null;

            // Suppression demandée depuis le formulaire de modification
            if ($request->boolean('supprimer_' . $champ)) {
                $chemin = null;
            }

            if ($request->hasFile($champ)) {
                $chemin = $request->file($champ)->store('produits', 'public');
            }

            $images[$champ] = $chemin;
        }

        return $images;
    }

    // ─── Supprimer produit ───
    public function supprimerProduit($id)
    {
        $produit = Produit::findOrFail($id);
        $produit->delete();

        return redirect()->route('admin.produits')
            ->with('success', 'Produit supprimé avec succès !');
    }

    // ─── Liste commandes ───
    public function commandes()
    {
        $commandes = Commande::with('utilisateur')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.commandes', compact('commandes'));
    }

    // ─── Détail commande ───
    public function detailCommande($id)
    {
        $commande = Commande::with('lignesCommande.produit', 'utilisateur')
            ->findOrFail($id);

        return view('admin.commande-detail', compact('commande'));
    }

    // ─── Changer statut commande ───
    public function updateStatut(Request $request, $id)
    {
        $request->validate([
            'statut' => 'required|in:en_attente,payee,expediee,annulee',
        ]);

        $commande = Commande::findOrFail($id);
        $commande->update(['statut' => $request->statut]);

        return redirect()->route('admin.commandes')
            ->with('success', 'Statut mis à jour !');
    }
}