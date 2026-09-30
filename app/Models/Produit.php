<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    protected $table = 'produits';

    protected $fillable = [
        'id_categorie',
        'nom',
        'description',
        'prix',
        'autonomie',
        'puissance',
        'poids',
        'stock',
        'image',
        'image2',
        'image3',
        'image4'
    ];

    // Un produit appartient à une catégorie
    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'id_categorie');
    }

    // Un produit peut être dans plusieurs lignes de commande
    public function lignesCommande()
    {
        return $this->hasMany(LigneCommande::class, 'id_produit');
    }

    // Un produit peut être dans plusieurs paniers
    public function paniers()
    {
        return $this->hasMany(Panier::class, 'id_produit');
    }

    // Vérifie si le produit est disponible
    public function estDisponible()
    {
        return $this->stock > 0;
    }
}