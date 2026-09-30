<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    protected $table = 'categories';
    
    protected $fillable = [
        'libelle'
    ];

    // Une catégorie a plusieurs produits
    public function produits()
    {
        return $this->hasMany(Produit::class, 'id_categorie');
    }
}