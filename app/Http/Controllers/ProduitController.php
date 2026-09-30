<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produit;

class ProduitController extends Controller
{
    // ─── Page Fiche Produit ───
    public function show($id)
    {
        $produit = Produit::with('categorie')->findOrFail($id);
        
        // Produits similaires (même catégorie)
        $produits_similaires = Produit::where('id_categorie', $produit->id_categorie)
            ->where('id', '!=', $produit->id)
            ->take(3)
            ->get();

        return view('produit', compact('produit', 'produits_similaires'));
    }
}