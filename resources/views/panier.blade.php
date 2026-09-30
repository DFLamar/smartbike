@extends('layouts.app')

@section('titre', 'Mon Panier')

@section('contenu')
<div class="conteneur">
    <h1 class="titre-page">Mon panier</h1>

    @if($articles->isEmpty())
        <div class="zone-vide">
            <p>Votre panier est vide.</p>
            <a href="{{ route('catalogue') }}" class="btn btn-principal">Voir le catalogue</a>
        </div>
    @else
        <div class="panier">

            <!-- ─── ARTICLES ─── -->
            <div class="tableau-defile">
                <table class="tableau tableau-cartes">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th class="num">Prix</th>
                            <th>Quantité</th>
                            <th class="num">Sous-total</th>
                            <th><span class="visuellement-cache">Supprimer</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($articles as $article)
                        <tr>
                            <!-- Produit -->
                            <td class="cellule-titre">
                                <div class="article">
                                    <div class="article-photo">
                                        @if($article->produit->image)
                                            <img src="{{ asset('storage/' . trim($article->produit->image)) }}"
                                                 alt="{{ $article->produit->nom }}">
                                        @else
                                            @include('partials.picto-velo')
                                        @endif
                                    </div>
                                    <a href="{{ route('produit.show', $article->produit->id) }}" class="article-nom">{{ $article->produit->nom }}</a>
                                </div>
                            </td>

                            <!-- Prix -->
                            <td class="num" data-label="Prix">
                                {{ number_format($article->produit->prix, 2, ',', ' ') }} €
                            </td>

                            <!-- Quantité -->
                            <td data-label="Quantité">
                                <div class="quantite">
                                    <form method="POST" action="{{ route('panier.modifier') }}" style="display:contents">
                                        @csrf
                                        <input type="hidden" name="id_article" value="{{ $article->id }}">
                                        <input type="hidden" name="quantite" value="{{ $article->quantite - 1 }}">
                                        <button type="submit" class="btn-qte" aria-label="Retirer un exemplaire"
                                            {{ $article->quantite <= 1 ? 'disabled' : '' }}>−</button>
                                    </form>
                                    <span class="quantite-val">{{ $article->quantite }}</span>
                                    <form method="POST" action="{{ route('panier.modifier') }}" style="display:contents">
                                        @csrf
                                        <input type="hidden" name="id_article" value="{{ $article->id }}">
                                        <input type="hidden" name="quantite" value="{{ $article->quantite + 1 }}">
                                        <button type="submit" class="btn-qte" aria-label="Ajouter un exemplaire"
                                            {{ $article->quantite >= $article->produit->stock ? 'disabled' : '' }}>+</button>
                                    </form>
                                </div>
                            </td>

                            <!-- Sous-total -->
                            <td class="num" data-label="Sous-total">
                                {{ number_format($article->quantite * $article->produit->prix, 2, ',', ' ') }} €
                            </td>

                            <!-- Supprimer -->
                            <td>
                                <form method="POST" action="{{ route('panier.supprimer') }}">
                                    @csrf
                                    <input type="hidden" name="id_article" value="{{ $article->id }}">
                                    <button type="submit" class="lien-supprimer">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- ─── RÉCAPITULATIF ─── -->
            <aside class="recap">
                <h2>Récapitulatif</h2>
                <div class="recap-lignes">
                    <div class="recap-ligne">
                        <span>Sous-total</span>
                        <span class="montant">{{ number_format($total, 2, ',', ' ') }} €</span>
                    </div>
                    <div class="recap-ligne">
                        <span>Livraison</span>
                        <span>Gratuite</span>
                    </div>
                </div>
                <div class="recap-total">
                    <span>Total TTC</span>
                    <span class="montant">{{ number_format($total, 2, ',', ' ') }} €</span>
                </div>
                <div class="recap-actions">
                    <a href="{{ route('commande') }}" class="btn btn-principal btn-large">
                        Passer la commande
                    </a>
                    <a href="{{ route('catalogue') }}" class="lien-simple">
                        Continuer mes achats
                    </a>
                </div>
            </aside>

        </div>
    @endif
</div>
@endsection
