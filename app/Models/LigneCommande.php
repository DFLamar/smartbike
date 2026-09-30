<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LigneCommande extends Model
{
    protected $table = 'lignecommande';

    protected $fillable = [
        'id_commande',
        'id_produit',
        'quantite',
        'prix_unitaire'
    ];

    // Une ligne appartient à une commande
    public function commande()
    {
        return $this->belongsTo(Commande::class, 'id_commande');
    }

    // Une ligne appartient à un produit
    public function produit()
    {
        return $this->belongsTo(Produit::class, 'id_produit');
    }

    // Calcule le sous-total de la ligne
    public function calculerSousTotal()
    {
        return $this->quantite * $this->prix_unitaire;
    }
}