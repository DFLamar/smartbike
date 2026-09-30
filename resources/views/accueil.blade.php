@extends('layouts.app')

@section('titre', 'Accueil')

@section('contenu')

<!-- ─── HAUT DE PAGE ─── -->
<section class="accueil-hero conteneur">
    <div>
        <h1>Le vélo électrique, pièce par pièce.</h1>
        <p>Vélos de ville et VTT électriques décrits comme des machines : moteur, autonomie, poids. Vous comparez sur les chiffres.</p>
        <a href="{{ route('catalogue') }}" class="btn btn-principal">Voir le catalogue</a>
    </div>

    <div class="accueil-photo">
        <img src="{{ asset('storage/produits/img3.avif') }}" alt="Vélo électrique">
    </div>
</section>

<!-- ─── CATÉGORIES ─── -->
<section class="section conteneur">
    <h2 class="section-titre">Nos catégories</h2>
    <div class="categories-tableau">
        @foreach($categories as $categorie)
            <div class="categorie-col {{ str_contains($categorie->libelle, 'VTT') ? 'vtt' : 'ville' }}">
                <h3>{{ $categorie->libelle }}</h3>
                <p>Découvrez notre sélection de {{ $categorie->libelle }}</p>
                <table class="carac">
                    <tr>
                        <th>Modèles</th>
                        <td>{{ $categorie->produits_count }}</td>
                    </tr>
                    <tr>
                        <th>Autonomie</th>
                        <td>{{ $categorie->produits_min_autonomie }} à {{ $categorie->produits_max_autonomie }} km</td>
                    </tr>
                </table>
                <a href="{{ route('catalogue', ['categorie' => $categorie->id]) }}" class="btn btn-contour">Découvrir</a>
            </div>
        @endforeach
    </div>
</section>

<!-- ─── PRODUITS PHARES ─── -->
<section class="section conteneur">
    <h2 class="section-titre">Nos meilleures ventes</h2>
    <div class="produits-grille">
        @foreach($produits_phares as $produit)
        @php $estVtt = str_contains($produit->categorie->libelle, 'VTT'); @endphp
        <article class="carte-produit {{ $estVtt ? 'vtt' : 'ville' }} {{ $loop->first ? 'carte-large' : '' }}">
            <div class="carte-ref">
                <span class="mono">N°{{ str_pad($produit->id, 2, '0', STR_PAD_LEFT) }}</span>
                <span>{{ $estVtt ? 'VTT' : 'Ville' }}</span>
            </div>
            <div class="carte-photo">
                @if($produit->image)
                    <img src="{{ asset('storage/' . trim($produit->image)) }}" alt="{{ $produit->nom }}">
                @else
                    @include('partials.picto-velo')
                @endif
            </div>
            <div class="carte-infos">
                <h3>{{ $produit->nom }}</h3>
                <table class="carac">
                    <tr>
                        <th>Autonomie</th>
                        <td><mark>{{ $produit->autonomie }} km</mark></td>
                    </tr>
                    <tr>
                        <th>Moteur</th>
                        <td>{{ $produit->puissance }} W</td>
                    </tr>
                    <tr>
                        <th>Poids</th>
                        <td>{{ number_format($produit->poids, 1, ',', '') }} kg</td>
                    </tr>
                </table>
                <div class="carte-pied">
                    <span class="prix">{{ number_format($produit->prix, 2, ',', ' ') }} €</span>
                    <a href="{{ route('produit.show', $produit->id) }}" class="btn btn-principal btn-petit">Voir le produit</a>
                </div>
            </div>
        </article>
        @endforeach
    </div>
</section>

<!-- ─── AVANTAGES ─── -->
<section class="section conteneur">
    <div class="avantages">
        <div class="avantage">
            <h3>Livraison rapide</h3>
            <p>Livraison en 3 à 5 jours ouvrés</p>
        </div>
        <div class="avantage">
            <h3>Paiement sécurisé</h3>
            <p>Transactions 100% sécurisées</p>
        </div>
        <div class="avantage">
            <h3>Service client</h3>
            <p>Disponible 7j/7 pour vous aider</p>
        </div>
    </div>
</section>

@endsection
