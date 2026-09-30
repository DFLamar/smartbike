@extends('layouts.app')

@section('titre', 'Commande confirmée')

@section('contenu')
<div class="conteneur">
    <div class="confirmation">

        <!-- Message succès -->
        <div class="confirmation-entete">
            <h1>Commande confirmée !</h1>
            <p>Merci pour votre achat. Votre commande a bien été enregistrée.</p>
            <p>Vous recevrez une confirmation dès l'expédition.</p>
            <div class="numero">
                Numéro de commande : <span class="mono">#SMB-{{ str_pad($commande->id, 4, '0', STR_PAD_LEFT) }}</span>
            </div>
        </div>

        <!-- Détail commande -->
        <section>
            <h2 class="section-titre">Détail de votre commande</h2>
            <div class="tableau-defile">
                <table class="tableau tableau-cartes">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th class="num">Quantité</th>
                            <th class="num">Prix unitaire</th>
                            <th class="num">Sous-total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($commande->lignesCommande as $ligne)
                        <tr>
                            <td class="cellule-titre">{{ $ligne->produit->nom }}</td>
                            <td class="num" data-label="Quantité">{{ $ligne->quantite }}</td>
                            <td class="num" data-label="Prix unitaire">{{ number_format($ligne->prix_unitaire, 2, ',', ' ') }} €</td>
                            <td class="num" data-label="Sous-total">{{ number_format($ligne->quantite * $ligne->prix_unitaire, 2, ',', ' ') }} €</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3">Total TTC</td>
                            <td class="num">{{ number_format($commande->total, 2, ',', ' ') }} €</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </section>

        <!-- Livraison -->
        <section>
            <h2 class="section-titre">Livraison</h2>
            <table class="carac">
                <tr>
                    <th>Adresse de livraison</th>
                    <td class="texte">{{ $commande->adresse_livraison }}</td>
                </tr>
                <tr>
                    <th>Mode de livraison</th>
                    <td class="texte">{{ $commande->mode_livraison === 'express' ? 'Express' : 'Standard' }}</td>
                </tr>
                <tr>
                    <th>Statut</th>
                    <td class="texte">@include('partials.statut', ['statut' => $commande->statut])</td>
                </tr>
            </table>
        </section>

        <!-- Boutons -->
        <div class="actions">
            <a href="{{ route('mon.compte') }}" class="btn btn-principal">
                Voir mes commandes
            </a>
            <a href="{{ route('catalogue') }}" class="btn btn-contour">
                Continuer mes achats
            </a>
        </div>

    </div>
</div>
@endsection
