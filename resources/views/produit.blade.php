@extends('layouts.app')

@section('titre', $produit->nom)

@section('contenu')

@php
    $estVtt = str_contains($produit->categorie->libelle, 'VTT');

    // Toutes les photos renseignées du produit (image, image2, image3, image4)
    $photos = collect([$produit->image, $produit->image2, $produit->image3, $produit->image4])
        ->filter()
        ->map(fn ($chemin) => asset('storage/' . trim($chemin)))
        ->values();
@endphp

<div class="conteneur">

    <!-- Fil d'Ariane -->
    <div class="fil-ariane">
        <a href="{{ route('accueil') }}">Accueil</a> >
        <a href="{{ route('catalogue') }}">Catalogue</a> >
        {{ $produit->nom }}
    </div>

    <div class="fiche">

        <!-- ─── PHOTOS ─── -->
        <div class="galerie">
            <div class="galerie-principale">
                @if($photos->isNotEmpty())
                    <img src="{{ $photos->first() }}" alt="{{ $produit->nom }}" id="photo-principale">
                @else
                    @include('partials.picto-velo')
                @endif
            </div>

            @if($photos->count() > 1)
            <div class="miniatures">
                @foreach($photos as $photo)
                    <button type="button" class="miniature {{ $loop->first ? 'active' : '' }}"
                            data-src="{{ $photo }}" aria-label="Voir la photo {{ $loop->iteration }}">
                        <img src="{{ $photo }}" alt="">
                    </button>
                @endforeach
            </div>
            @endif
        </div>

        <!-- ─── INFOS ─── -->
        <div class="fiche-infos">
            <div class="fiche-ref">
                <span>Réf. <span class="mono">N°{{ str_pad($produit->id, 2, '0', STR_PAD_LEFT) }}</span></span>
                <span class="cat-etiquette {{ $estVtt ? 'vtt' : 'ville' }}">{{ $produit->categorie->libelle }}</span>
            </div>

            <h1>{{ $produit->nom }}</h1>

            <div class="fiche-prix-stock">
                <span class="prix">{{ number_format($produit->prix, 2, ',', ' ') }} €</span>
                @if($produit->stock > 0)
                    <span class="badge badge-ok">En stock ({{ $produit->stock }} disponibles)</span>
                @else
                    <span class="badge badge-alerte">Rupture de stock</span>
                @endif
            </div>

            <!-- Caractéristiques -->
            <table class="carac">
                <tr>
                    <th>Autonomie</th>
                    <td><mark>{{ $produit->autonomie }} km</mark></td>
                </tr>
                <tr>
                    <th>Puissance moteur</th>
                    <td>{{ $produit->puissance }} W</td>
                </tr>
                @if($produit->poids)
                <tr>
                    <th>Poids</th>
                    <td>{{ number_format($produit->poids, 1, ',', '') }} kg</td>
                </tr>
                @endif
                <tr>
                    <th>Catégorie</th>
                    <td class="texte">{{ $produit->categorie->libelle }}</td>
                </tr>
            </table>

            <!-- Bouton panier -->
            @if($produit->stock > 0)
                @if(session('utilisateur_id'))
                    <form method="POST" action="{{ route('panier.ajouter') }}">
                        @csrf
                        <input type="hidden" name="id_produit" value="{{ $produit->id }}">
                        <input type="hidden" name="quantite" value="1">
                        <button type="submit" class="btn btn-principal btn-large">Ajouter au panier</button>
                    </form>
                @else
                    <a href="{{ route('connexion') }}" class="btn btn-principal btn-large">
                        Connectez-vous pour acheter
                    </a>
                @endif
            @else
                <button class="btn btn-large" disabled>Rupture de stock</button>
            @endif
        </div>
    </div>

    <!-- Description -->
    <section class="fiche-description">
        <h2 class="section-titre">Description</h2>
        <p>{{ $produit->description ?? 'Aucune description disponible.' }}</p>
    </section>

</div>

@if($photos->count() > 1)
<script>
    // Un clic sur une miniature l'affiche en grand
    document.querySelectorAll('.miniature').forEach(function (bouton) {
        bouton.addEventListener('click', function () {
            document.getElementById('photo-principale').src = bouton.dataset.src;
            document.querySelectorAll('.miniature').forEach(function (b) { b.classList.remove('active'); });
            bouton.classList.add('active');
        });
    });
</script>
@endif

@endsection
