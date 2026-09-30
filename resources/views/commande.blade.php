@extends('layouts.app')

@section('titre', 'Passer la commande')

@section('contenu')
<div class="conteneur">
    <h1 class="titre-page">Passer la commande</h1>

    <!-- Étapes -->
    <ol class="etapes">
        <li class="actif"><span class="etape-num">1</span>Livraison</li>
        <li><span class="etape-num">2</span>Paiement</li>
        <li><span class="etape-num">3</span>Confirmation</li>
    </ol>

    <form method="POST" action="{{ route('commande.store') }}">
        @csrf
        <div class="commande">

            <!-- ─── FORMULAIRE ─── -->
            <div class="commande-formulaire">
                <section class="bloc">
                    <h2>Adresse de livraison</h2>
                    <div class="bloc-corps form-grille">
                        <div class="champ">
                            <label for="prenom">Prénom</label>
                            <input type="text" name="prenom" id="prenom"
                                value="{{ session('utilisateur_nom') }}"
                                placeholder="Votre prénom" required>
                        </div>
                        <div class="champ">
                            <label for="nom">Nom</label>
                            <input type="text" name="nom" id="nom"
                                placeholder="Votre nom" required>
                        </div>
                        <div class="champ large">
                            <label for="adresse_livraison">Adresse</label>
                            <input type="text" name="adresse_livraison" id="adresse_livraison"
                                value="{{ old('adresse_livraison') }}"
                                placeholder="Numéro et rue" required>
                            @error('adresse_livraison')
                                <div class="champ-erreur">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="champ">
                            <label for="ville">Ville</label>
                            <input type="text" name="ville" id="ville"
                                placeholder="Votre ville" required>
                        </div>
                        <div class="champ">
                            <label for="code_postal">Code postal</label>
                            <input type="text" name="code_postal" id="code_postal"
                                placeholder="Ex: 75001" required>
                        </div>
                        <div class="champ large">
                            <label for="telephone">Téléphone</label>
                            <input type="text" name="telephone" id="telephone"
                                placeholder="Ex: 0612345678" required>
                        </div>
                    </div>
                </section>

                <!-- Mode de livraison -->
                <section class="bloc">
                    <h2>Mode de livraison</h2>
                    <div class="bloc-corps">
                        <div class="options-livraison">
                            <label class="option-livraison">
                                <input type="radio" name="mode_livraison"
                                    value="standard" checked>
                                <span>
                                    <strong>Livraison standard</strong>
                                    <span class="delai">3 à 5 jours ouvrés</span>
                                </span>
                                <span>Gratuite</span>
                            </label>
                            <label class="option-livraison">
                                <input type="radio" name="mode_livraison"
                                    value="express">
                                <span>
                                    <strong>Livraison express</strong>
                                    <span class="delai">24 à 48h</span>
                                </span>
                                <span class="montant">9,99 €</span>
                            </label>
                        </div>
                        @error('mode_livraison')
                            <div class="champ-erreur">{{ $message }}</div>
                        @enderror
                    </div>
                </section>
            </div>

            <!-- ─── RÉCAPITULATIF ─── -->
            <aside class="recap">
                <h2>Votre commande</h2>
                <div class="recap-lignes">
                    @foreach($articles as $article)
                    <div class="recap-ligne">
                        <span>
                            {{ $article->produit->nom }}
                            <span class="quantite-x">×{{ $article->quantite }}</span>
                        </span>
                        <span class="montant">
                            {{ number_format($article->quantite * $article->produit->prix, 2, ',', ' ') }} €
                        </span>
                    </div>
                    @endforeach
                </div>
                <div class="recap-total">
                    <span>Total TTC</span>
                    <span class="montant">{{ number_format($total, 2, ',', ' ') }} €</span>
                </div>
                <div class="recap-actions">
                    <button type="submit" class="btn btn-principal btn-large">
                        Payer la commande
                    </button>
                </div>
            </aside>

        </div>
    </form>
</div>
@endsection
