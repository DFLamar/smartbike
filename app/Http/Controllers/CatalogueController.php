<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produit;
use App\Models\Categorie;

class CatalogueController extends Controller
{
    // ─── Page Accueil ───
    public function accueil()
    {
        $produits_phares = Produit::with('categorie')
            ->where('stock', '>', 0)
            ->take(3)
            ->get();

        // Nombre de modèles et autonomie min/max de chaque catégorie
        $categories = Categorie::withCount('produits')
            ->withMin('produits', 'autonomie')
            ->withMax('produits', 'autonomie')
            ->get();

        return view('accueil', compact('produits_phares', 'categories'));
    }

    // ─── Page Catalogue ───
    public function index(Request $request)
    {
        $query = Produit::with('categorie');

        // Filtre par catégorie
        if ($request->has('categorie') && $request->categorie != '') {
            $query->where('id_categorie', $request->categorie);
        }

        // Filtre par prix
        if ($request->has('prix_min') && $request->prix_min != '') {
            $query->where('prix', '>=', $request->prix_min);
        }
        if ($request->has('prix_max') && $request->prix_max != '') {
            $query->where('prix', '<=', $request->prix_max);
        }

        // Filtre par autonomie
        if ($request->has('autonomie_min') && $request->autonomie_min != '') {
            $query->where('autonomie', '>=', $request->autonomie_min);
        }

        // Tri
        if ($request->has('tri')) {
            switch ($request->tri) {
                case 'prix_asc':
                    $query->orderBy('prix', 'asc');
                    break;
                case 'prix_desc':
                    $query->orderBy('prix', 'desc');
                    break;
                case 'nouveaute':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        }

        $produits   = $query->paginate(6);
        $categories = Categorie::all();

        return view('catalogue', compact('produits', 'categories'));
    }
}