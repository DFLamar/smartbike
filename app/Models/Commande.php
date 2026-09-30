<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    protected $table = 'commandes';

    protected $fillable = [
        'id_utilisateur',
        'statut',
        'total',
        'adresse_livraison',
        'mode_livraison'
    ];

    // Une commande appartient à un utilisateur
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur');
    }

    // Une commande a plusieurs lignes de commande
    public function lignesCommande()
    {
        return $this->hasMany(LigneCommande::class, 'id_commande');
    }

    // Calcule le total de la commande
    public function calculerTotal()
    {
        return $this->lignesCommande->sum(function($ligne) {
            return $ligne->quantite * $ligne->prix_unitaire;
        });
    }
}