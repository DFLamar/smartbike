@extends('layouts.app')

@section('titre', 'Catalogue')

@section('contenu')

<div class="conteneur">

    <!-- Fil d'Ariane -->
    <div class="fil-ariane">
        <a href="{{ route('accueil') }}">Accueil</a> > Catalogue
    </div>

    <h1 class="titre-page">Catalogue</h1>

    <div class="catalogue">

        <!-- ─── FILTRES ─── -->
        <aside class="filtres">
            <h2>Filtrer</h2>
            <form method="GET" action="{{ route('catalogue') }}">

                <div class="filtre-groupe">
                    <span class="champ-titre">Catégorie</span>
                    @foreach($categories as $categorie)
                    <label class="case">
                        <input type="checkbox" name="categorie" value="{{ $categorie->id }}"
                            {{ request('categorie') == $categorie->id ? 'checked' : '' }}>
                        {{ $categorie->libelle }}
                    </label>
                    @endforeach
                </div>

                <div class="filtre-groupe">
                    <span class="champ-titre">Prix</span>
                    <input type="number" name="prix_min" placeholder="Prix min (€)" aria-label="Prix minimum"
                        value="{{ request('prix_min') }}">
                    <input type="number" name="prix_max" placeholder="Prix max (€)" aria-label="Prix maximum"
                        value="{{ request('prix_max') }}">
                </div>

                <div class="filtre-groupe">
                    <label class="champ-titre" for="autonomie_min">Autonomie minimum</label>
                    <input type="number" name="autonomie_min" id="autonomie_min" placeholder="Ex: 60 km"
                        value="{{ request('autonomie_min') }}">
                </div>

                <button type="submit" class="btn btn-principal">Appliquer les filtres</button>
            </form>
        </aside>

        <!-- ─── PRODUITS ─── -->
        <div>
            <div class="barre-tri">
                <span class="nombre"><strong>{{ $produits->total() }}</strong> vélos électriques</span>
                <form method="GET" action="{{ route('catalogue') }}">
                    <label for="tri">Trier par</label>
                    <select name="tri" id="tri" onchange="this.form.submit()">
                        <option value="nouveaute" {{ request('tri') == 'nouveaute' ? 'selected' : '' }}>
                            Nouveauté
                        </option>
                        <option value="prix_asc" {{ request('tri') == 'prix_asc' ? 'selected' : '' }}>
                            Prix croissant
                        </option>
                        <option value="prix_desc" {{ request('tri') == 'prix_desc' ? 'selected' : '' }}>
                            Prix décroissant
                        </option>
                    </select>
                </form>
            </div>

            <div class="catalogue-grille">
                @forelse($produits as $produit)
                @php $estVtt = str_contains($produit->categorie->libelle, 'VTT'); @endphp
                <article class="carte-produit {{ $estVtt ? 'vtt' : 'ville' }}">
                    <div class="carte-ref">
                        <span class="mono">N°{{ str_pad($produit->id, 2, '0', STR_PAD_LEFT) }}</span>
                        @if($produit->stock > 0)
                            <span>{{ $estVtt ? 'VTT' : 'Ville' }}</span>
                        @else
                            <span class="badge badge-alerte">Rupture</span>
                        @endif
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
                            @if($produit->poids)
                            <tr>
                                <th>Poids</th>
                                <td>{{ number_format($produit->poids, 1, ',', '') }} kg</td>
                            </tr>
                            @endif
                        </table>
                        <div class="carte-pied">
                            <span class="prix">{{ number_format($produit->prix, 2, ',', ' ') }} €</span>
                            <a href="{{ route('produit.show', $produit->id) }}" class="btn btn-principal btn-petit">Voir le produit</a>
                        </div>
                    </div>
                </article>
                @empty
                <p class="catalogue-vide">Aucun vélo trouvé avec ces critères.</p>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="zone-pagination">
                {{ $produits->withQueryString()->links('pagination::default') }}
            </div>
        </div>

    </div>
</div>

@endsection
