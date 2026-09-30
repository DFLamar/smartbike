<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Utilisateur extends Authenticatable
{
    use Notifiable;

    protected $table = 'utilisateurs';

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'mot_de_passe',
        'role',
        'adresse',
        'telephone'
    ];

    // Cacher ces champs dans les réponses JSON
    protected $hidden = [
        'mot_de_passe'
    ];

    // Un utilisateur a plusieurs commandes
    public function commandes()
    {
        return $this->hasMany(Commande::class, 'id_utilisateur');
    }

    // Un utilisateur a plusieurs paniers
    public function paniers()
    {
        return $this->hasMany(Panier::class, 'id_utilisateur');
    }

    // Vérifie si l'utilisateur est admin
    public function estAdmin()
    {
        return $this->role === 'admin';
    }
}