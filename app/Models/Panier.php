<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Panier extends Model
{
    protected $table = 'panier';

    protected $fillable = [
        'id_utilisateur',
        'id_produit',
        'quantite'
    ];

    // Un panier appartient à un utilisateur
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur');
    }

    // Un panier appartient à un produit
    public function produit()
    {
        return $this->belongsTo(Produit::class, 'id_produit');
    }
}