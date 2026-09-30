<?php

namespace Database\Seeders;

use App\Models\Categorie;
use App\Models\Produit;
use App\Models\Utilisateur;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Remplit la base avec des données de démonstration.
     * firstOrCreate : si la ligne existe déjà, elle n'est ni dupliquée ni modifiée.
     */
    public function run(): void
    {
        // ─── Catégories ───
        $ville  = Categorie::firstOrCreate(['libelle' => 'Vélo électrique de ville']);
        $vtt    = Categorie::firstOrCreate(['libelle' => 'VTT électrique']);
        $pliant = Categorie::firstOrCreate(['libelle' => 'Vélo pliant électrique']);
        $cargo  = Categorie::firstOrCreate(['libelle' => 'Vélo cargo électrique']);

        // ─── Comptes de test ───
        Utilisateur::firstOrCreate(['email' => 'admin@smartbike.fr'], [
            'nom'          => 'Admin',
            'prenom'       => 'SmartBike',
            'mot_de_passe' => Hash::make('Admin123!'),
            'role'         => 'admin',
        ]);

        Utilisateur::firstOrCreate(['email' => 'client@smartbike.fr'], [
            'nom'          => 'Martin',
            'prenom'       => 'Julie',
            'mot_de_passe' => Hash::make('Client123!'),
            'role'         => 'client',
            'adresse'      => '12 rue de la République, 69002 Lyon',
            'telephone'    => '0612345678',
        ]);

        // ─── Vélos ───
        $velos = [
            [
                'id_categorie' => $ville->id,
                'nom'          => 'CityRider Pro',
                'description'  => 'Vélo de ville haut de gamme avec moteur central silencieux, cadre en aluminium et éclairage LED intégré. Idéal pour les trajets quotidiens domicile-travail.',
                'prix'         => 1899.00, 'autonomie' => 90, 'puissance' => 250, 'poids' => 23.5, 'stock' => 8,
                'image'        => 'produits/CityRiderPro1.jpg',  'image2' => 'produits/CityRiderPro2.webp',
                'image3'       => 'produits/CityRiderPro3.webp', 'image4' => 'produits/CityRiderPro4.webp',
            ],
            [
                'id_categorie' => $ville->id,
                'nom'          => 'UrbanGlide 500',
                'description'  => 'Vélo urbain confortable avec cadre ouvert, porte-bagages et garde-boue. Sa batterie amovible se recharge facilement à la maison ou au bureau.',
                'prix'         => 1499.00, 'autonomie' => 70, 'puissance' => 250, 'poids' => 22.0, 'stock' => 10,
                'image'        => 'produits/UrbanGlide1.jpg', 'image2' => 'produits/UrbanGlide2.jpg',
                'image3'       => 'produits/UrbanGlide3.jpg', 'image4' => 'produits/UrbanGlide4.jpg',
            ],
            [
                'id_categorie' => $ville->id,
                'nom'          => 'MetroBoost Elite',
                'description'  => 'Grande autonomie et transmission à courroie sans entretien. Écran connecté pour suivre vitesse, distance et niveau de batterie.',
                'prix'         => 2299.00, 'autonomie' => 120, 'puissance' => 250, 'poids' => 24.8, 'stock' => 6,
                'image'        => 'produits/MetroBoost1.jpg', 'image2' => 'produits/MetroBoost2.jpg',
                'image3'       => 'produits/MetroBoost3.jpg', 'image4' => 'produits/MetroBoost4.jpg',
            ],
            [
                'id_categorie' => $ville->id,
                'nom'          => 'EcoCity Light',
                'description'  => 'Vélo électrique léger et abordable, parfait pour découvrir l\'assistance électrique en ville. Facile à porter dans les escaliers.',
                'prix'         => 1199.00, 'autonomie' => 60, 'puissance' => 250, 'poids' => 18.9, 'stock' => 12,
                'image'        => 'produits/EcoCity1.jpg', 'image2' => 'produits/EcoCity2.jpg',
                'image3'       => 'produits/EcoCity3.jpg', 'image4' => 'produits/EcoCity4.jpg',
            ],
            [
                'id_categorie' => $vtt->id,
                'nom'          => 'TrailMaster 750',
                'description'  => 'VTT tout suspendu avec batterie 750 Wh pour les longues sorties en montagne. Freins à disque hydrauliques 4 pistons.',
                'prix'         => 3490.00, 'autonomie' => 110, 'puissance' => 250, 'poids' => 24.2, 'stock' => 5,
                'image'        => 'produits/TrailMaster1.webp', 'image2' => 'produits/TrailMaster2.jpg',
                'image3'       => 'produits/TrailMaster3.webp', 'image4' => 'produits/TrailMaster4.jpg',
            ],
            [
                'id_categorie' => $vtt->id,
                'nom'          => 'MountainBeast Pro',
                'description'  => 'VTT enduro puissant et robuste, conçu pour les terrains techniques. Suspension 160 mm et pneus larges pour une adhérence maximale.',
                'prix'         => 4290.00, 'autonomie' => 130, 'puissance' => 250, 'poids' => 25.4, 'stock' => 4,
                'image'        => 'produits/MountainBeast1.jpg', 'image2' => 'produits/MountainBeast2.jpg',
                'image3'       => 'produits/MountainBeast3.jpg', 'image4' => 'produits/MountainBeast4.jpg',
            ],
            [
                'id_categorie' => $vtt->id,
                'nom'          => 'ForestRider 500',
                'description'  => 'VTT semi-rigide polyvalent, à l\'aise sur les chemins forestiers comme sur la route. Un bon premier VTT électrique.',
                'prix'         => 2690.00, 'autonomie' => 90, 'puissance' => 250, 'poids' => 23.9, 'stock' => 7,
            ],
            [
                'id_categorie' => $vtt->id,
                'nom'          => 'XTrail Adventure',
                'description'  => 'VTT de randonnée équipé pour l\'aventure : porte-bagages, béquille et éclairage. Parfait pour le bikepacking.',
                'prix'         => 3190.00, 'autonomie' => 100, 'puissance' => 250, 'poids' => 24.6, 'stock' => 5,
            ],
            [
                'id_categorie' => $pliant->id,
                'nom'          => 'FoldGo Compact',
                'description'  => 'Vélo pliant en quelques secondes, idéal pour combiner vélo et transports en commun. Se range facilement sous un bureau.',
                'prix'         => 1090.00, 'autonomie' => 45, 'puissance' => 250, 'poids' => 16.5, 'stock' => 9,
            ],
            [
                'id_categorie' => $pliant->id,
                'nom'          => 'PocketRide 20',
                'description'  => 'Pliant à roues de 20 pouces avec 7 vitesses et batterie intégrée au cadre. Stable et confortable malgré sa petite taille.',
                'prix'         => 1390.00, 'autonomie' => 60, 'puissance' => 250, 'poids' => 17.8, 'stock' => 6,
            ],
            [
                'id_categorie' => $cargo->id,
                'nom'          => 'CargoFamily Long',
                'description'  => 'Vélo cargo longtail pouvant transporter deux enfants ou jusqu\'à 60 kg de courses. Double béquille pour une stabilité parfaite.',
                'prix'         => 3990.00, 'autonomie' => 80, 'puissance' => 250, 'poids' => 34.0, 'stock' => 3,
            ],
            [
                'id_categorie' => $cargo->id,
                'nom'          => 'BoxRunner Duo',
                'description'  => 'Biporteur avec caisse avant pour les familles et les livraisons professionnelles. Moteur puissant pour les charges lourdes.',
                'prix'         => 4590.00, 'autonomie' => 70, 'puissance' => 250, 'poids' => 42.5, 'stock' => 2,
            ],
        ];

        // unguarded : image2 à image4 ne sont pas dans $fillable du modèle Produit
        Produit::unguarded(function () use ($velos) {
            foreach ($velos as $velo) {
                Produit::firstOrCreate(['nom' => $velo['nom']], $velo);
            }
        });
    }
}
