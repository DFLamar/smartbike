@extends('layouts.app')

@section('titre', 'Mon Compte')

@section('styles')
<style>
    .page-compte { padding: 40px 80px; }
    .page-compte h1 { font-size: 32px; margin-bottom: 30px; }

    .compte-container {
        display: grid;
        grid-template-columns: 250px 1fr;
        gap: 40px;
    }

    /* ─── MENU LATÉRAL ─── */
    .menu-lateral {
        background: var(--teinte);
        border-radius: 12px;
        padding: 25px;
        height: fit-content;
    }
    .menu-lateral h3 {
        font-size: 16px; margin-bottom: 20px;
        color: var(--encre);
    }
    .menu-lateral a {
        display: flex; align-items: center; gap: 10px;
        padding: 10px 15px; border-radius: 8px;
        text-decoration: none; font-size: 14px;
        color: var(--encre-douce); margin-bottom: 5px;
    }
    .menu-lateral a:hover { background: var(--fond); color: var(--cobalt); }
    .menu-lateral a.active { background: var(--fond); color: var(--cobalt); font-weight: bold; }
    .menu-lateral a.deconnexion { color: var(--alerte); }
    .menu-lateral a.deconnexion:hover { background: var(--fond); }

    /* ─── ZONE PRINCIPALE ─── */
    .zone-principale {}

    /* ─── PROFIL ─── */
    .section-card {
        background: var(--fond); border-radius: 12px;
        padding: 30px; border: 1px solid var(--encre);
        margin-bottom: 30px;
    }
    .section-card h2 { font-size: 20px; margin-bottom: 25px; }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .form-grid .full { grid-column: span 2; }
    .form-groupe { display: flex; flex-direction: column; gap: 6px; }
    .form-groupe label { font-size: 14px; font-weight: bold; color: var(--encre); }
    .form-groupe input {
        padding: 12px; border: 1px solid var(--trait);
        border-radius: 8px; font-size: 14px; outline: none;
        background: var(--fond); color: var(--encre);
    }
    .form-groupe input:focus { border-color: var(--cobalt); }
    .btn-enregistrer {
        background: var(--cobalt); color: var(--fond); padding: 12px 30px;
        border: none; border-radius: 8px; font-size: 15px;
        font-weight: bold; cursor: pointer; margin-top: 10px;
    }
    .btn-enregistrer:hover { background: var(--encre); }

    /* ─── COMMANDES ─── */
    .tableau-commandes { width: 100%; border-collapse: collapse; }
    .tableau-commandes th {
        background: var(--teinte); padding: 12px 15px;
        text-align: left; font-size: 13px; color: var(--encre-douce);
        border-bottom: 2px solid var(--trait);
    }
    .tableau-commandes td {
        padding: 15px; font-size: 14px;
        border-bottom: 1px solid var(--trait);
    }
    .tableau-commandes tr:hover td { background: var(--teinte); }
    .badge-statut {
        display: inline-block; padding: 4px 12px;
        border-radius: 20px; font-size: 12px; font-weight: bold;
    }
    .badge-statut.en_attente { background: var(--teinte); color: var(--encre-douce); }
    .badge-statut.payee { background: var(--teinte); color: var(--cobalt); }
    .badge-statut.expediee { background: var(--encre); color: var(--fond); }
    .badge-statut.annulee { background: var(--teinte); color: var(--alerte); }
    .lien-detail { color: var(--cobalt); text-decoration: none; font-size: 13px; }
    .lien-detail:hover { text-decoration: underline; }
    .aucune-commande {
        text-align: center; padding: 40px;
        color: var(--encre-douce); font-size: 15px;
    }

    /* ─── RESPONSIVE ─── */
    @media (max-width: 900px) {
        .page-compte { padding: 30px var(--marge-cote, 20px); }
        .compte-container { grid-template-columns: 1fr; gap: 24px; }
        .menu-lateral { padding: 15px; }
        .menu-lateral h3 { display: none; }
        .menu-lateral nav { display: flex; flex-wrap: wrap; gap: 5px; }
        .menu-lateral a { margin-bottom: 0; }
    }
    @media (max-width: 700px) {
        .page-compte h1 { font-size: 26px; margin-bottom: 20px; }
        .section-card { padding: 20px; }
        .form-grid { grid-template-columns: 1fr; }
        .form-grid .full { grid-column: auto; }
        .btn-enregistrer { width: 100%; }
        .tableau-commandes td { padding: 6px 14px; border-bottom: 0; }
        .tableau-commandes tr:hover td { background: none; }
    }
</style>
@endsection

@section('contenu')
<div class="page-compte">
    <h1>Mon compte</h1>

    <div class="compte-container">

        <!-- ─── MENU LATÉRAL ─── -->
        <aside class="menu-lateral">
            <h3>Navigation</h3>
            <nav>
            <a href="{{ route('mon.compte') }}" class="active">Mon profil</a>
            <a href="{{ route('mon.compte') }}#commandes">Mes commandes</a>
            <a href="{{ route('panier') }}">Mon panier</a>
            <a href="#" onclick="event.preventDefault(); document.getElementById('form-deconnexion-compte').submit();" class="deconnexion">Déconnexion</a><form id="form-deconnexion-compte" method="POST" action="{{ route('deconnexion') }}" style="display:none">@csrf</form>
            </nav>
        </aside>

        <!-- ─── ZONE PRINCIPALE ─── -->
        <div class="zone-principale">

            <!-- Profil -->
            <div class="section-card">
                <h2>Mon profil</h2>
                <form method="POST" action="{{ route('mon.compte.modifier') }}">
                    @csrf
                    <div class="form-grid">
                        <div class="form-groupe">
                            <label>Prénom</label>
                            <input type="text" name="prenom"
                                value="{{ $utilisateur->prenom }}" required>
                        </div>
                        <div class="form-groupe">
                            <label>Nom</label>
                            <input type="text" name="nom"
                                value="{{ $utilisateur->nom }}" required>
                        </div>
                        <div class="form-groupe full">
                            <label>Email</label>
                            <input type="email" value="{{ $utilisateur->email }}"
                                disabled style="background: var(--teinte); color: var(--encre-douce);">
                        </div>
                        <div class="form-groupe full">
                            <label>Adresse</label>
                            <input type="text" name="adresse"
                                value="{{ $utilisateur->adresse }}"
                                placeholder="Votre adresse">
                        </div>
                        <div class="form-groupe">
                            <label>Téléphone</label>
                            <input type="text" name="telephone"
                                value="{{ $utilisateur->telephone }}"
                                placeholder="Ex: 0612345678">
                        </div>
                    </div>
                    <button type="submit" class="btn-enregistrer">
                        Enregistrer les modifications
                    </button>
                </form>
            </div>

            <!-- Commandes -->
            <div class="section-card" id="commandes">
                <h2>Mes commandes</h2>

                @if($commandes->isEmpty())
                    <div class="aucune-commande">
                        Vous n'avez pas encore passé de commande.
                        <br><br>
                        <a href="{{ route('catalogue') }}"
                           style="color: var(--cobalt); font-weight:bold;">
                            Voir le catalogue
                        </a>
                    </div>
                @else
                    <table class="tableau-commandes tableau-cartes">
                        <thead>
                            <tr>
                                <th>Numéro</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Livraison</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($commandes as $commande)
                            <tr>
                                <td data-label="Numéro">
                                    <strong>#SMB-{{ str_pad($commande->id, 4, '0', STR_PAD_LEFT) }}</strong>
                                </td>
                                <td data-label="Date">{{ $commande->created_at->format('d/m/Y') }}</td>
                                <td data-label="Total">{{ number_format($commande->total, 2, ',', ' ') }} €</td>
                                <td data-label="Livraison">
                                    {{ $commande->mode_livraison === 'express' ? 'Express' : 'Standard' }}
                                </td>
                                <td data-label="Statut">
                                    <span class="badge-statut {{ $commande->statut }}">
                                        @if($commande->statut === 'en_attente') En attente
                                        @elseif($commande->statut === 'payee') Payée
                                        @elseif($commande->statut === 'expediee') Expédiée
                                        @else Annulée
                                        @endif
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection