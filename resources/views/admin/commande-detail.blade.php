
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartBike — Détail Commande</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; display: flex; background: #ECEEF1; }

        /* ─── SIDEBAR ─── */
        .sidebar {
            width: 240px; min-height: 100vh;
            background: #161B26; padding: 25px 0;
            position: fixed; left: 0; top: 0;
        }
        .sidebar-logo { font-size: 20px; font-weight: bold; color: #ECEEF1; padding: 0 20px 5px; }
        .sidebar-subtitle { font-size: 12px; color: #B8BFCA; padding: 0 20px 25px; }
        .sidebar-menu { list-style: none; }
        .sidebar-menu a {
            display: flex; align-items: center; gap: 10px;
            padding: 12px 20px; text-decoration: none;
            color: #B8BFCA; font-size: 14px;
        }
        .sidebar-menu a:hover { background: #4A5263; color: #ECEEF1; }
        .sidebar-menu a.active { background: #2340D9; color: #ECEEF1; }
        .sidebar-menu .deconnexion a { color: #D6362B; }

        /* ─── CONTENU ─── */
        .main-content { margin-left: 240px; padding: 40px; width: calc(100% - 240px); }
        .page-header {
            display: flex; justify-content: space-between;
            align-items: center; margin-bottom: 30px;
        }
        .page-title { font-size: 28px; }
        .btn-retour {
            background: #D5D9DF; color: #4A5263; padding: 10px 20px;
            border-radius: 8px; text-decoration: none; font-size: 14px;
        }
        .btn-retour:hover { background: #B8BFCA; }

        /* ─── CARDS ─── */
        .detail-grid {
            display: grid; grid-template-columns: 1fr 1fr;
            gap: 25px; margin-bottom: 25px;
        }
        .section-card {
            background: #ECEEF1; border-radius: 12px;
            padding: 25px; border: 1px solid #161B26;
        }
        .section-card h2 { font-size: 18px; margin-bottom: 20px; color: #161B26; }
        .info-ligne {
            display: flex; justify-content: space-between;
            padding: 10px 0; border-bottom: 1px solid #B8BFCA;
            font-size: 14px;
        }
        .info-ligne:last-child { border-bottom: none; }
        .info-ligne span:first-child { color: #4A5263; }
        .info-ligne span:last-child { font-weight: bold; }

        /* ─── STATUT ─── */
        .badge-statut {
            display: inline-block; padding: 4px 12px;
            border-radius: 20px; font-size: 12px; font-weight: bold;
        }
        .badge-statut.en_attente { background: #D5D9DF; color: #4A5263; }
        .badge-statut.payee { background: #D5D9DF; color: #2340D9; }
        .badge-statut.expediee { background: #161B26; color: #ECEEF1; }
        .badge-statut.annulee { background: #D5D9DF; color: #D6362B; }

        /* ─── CHANGER STATUT ─── */
        .statut-form {
            display: flex; gap: 10px; align-items: center; margin-top: 15px;
        }
        .statut-form select {
            padding: 8px 12px; border: 1px solid #B8BFCA;
            border-radius: 8px; font-size: 14px; outline: none; flex: 1;
            background: #ECEEF1; color: #161B26;
        }
        .statut-form select:focus { border-color: #2340D9; }
        .btn-statut {
            background: #2340D9; color: #ECEEF1; padding: 8px 16px;
            border: none; border-radius: 8px; font-size: 14px;
            font-weight: bold; cursor: pointer;
        }
        .btn-statut:hover { background: #161B26; }

        /* ─── TABLEAU PRODUITS ─── */
        table { width: 100%; border-collapse: collapse; }
        th {
            background: #D5D9DF; padding: 12px 15px;
            text-align: left; font-size: 13px; color: #4A5263;
            border-bottom: 2px solid #B8BFCA;
        }
        td {
            padding: 12px 15px; font-size: 14px;
            border-bottom: 1px solid #B8BFCA;
        }
        .total-ligne td {
            font-weight: bold; font-size: 16px;
            border-top: 2px solid #B8BFCA; border-bottom: none;
        }
        .total-ligne td:last-child { color: #2340D9; }

        .alert-success {
            background: #D5D9DF; color: #2340D9; padding: 12px 20px;
            border-radius: 8px; margin-bottom: 20px;
            border-left: 4px solid #2340D9;
        }

        /* ─── RESPONSIVE ─── */
        @media (max-width: 900px) {
            body { flex-direction: column; }
            .sidebar {
                position: static; width: 100%; min-height: 0;
                padding: 15px 0 0;
            }
            .sidebar-subtitle { padding-bottom: 12px; }
            .sidebar-menu { display: flex; flex-wrap: wrap; }
            .sidebar-menu a { padding: 10px 16px; }
            .main-content { margin-left: 0; width: 100%; padding: 25px 20px; }
            .section-card { overflow-x: auto; }
            table { min-width: 560px; }
            .detail-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 600px) {
            .page-title { font-size: 22px; }
            .page-header { flex-wrap: wrap; gap: 12px; }
            .section-card { padding: 18px; }
            .statut-form { flex-wrap: wrap; }
            .statut-form select, .btn-statut { width: 100%; }
            .info-ligne { gap: 12px; }
            .info-ligne span:last-child { text-align: right; }
        }
    </style>
</head>
<body>

<!-- ─── SIDEBAR ─── -->
<aside class="sidebar">
    <div class="sidebar-logo">SmartBike</div>
    <div class="sidebar-subtitle">Administration</div>
    <ul class="sidebar-menu">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.produits') }}">Produits</a></li>
        <li><a href="{{ route('admin.commandes') }}" class="active">Commandes</a></li>
        <li class="deconnexion"><a href="#" onclick="event.preventDefault(); document.getElementById('form-deconnexion').submit();">Déconnexion</a><form id="form-deconnexion" method="POST" action="{{ route('deconnexion') }}" style="display:none">@csrf</form></li>
    </ul>
</aside>

<!-- ─── CONTENU ─── -->
<main class="main-content">

    <div class="page-header">
        <h1 class="page-title">
            Commande #SMB-{{ str_pad($commande->id, 4, '0', STR_PAD_LEFT) }}
        </h1>
        <a href="{{ route('admin.commandes') }}" class="btn-retour">
            Retour aux commandes
        </a>
    </div>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="detail-grid">

        <!-- Infos client -->
        <div class="section-card">
            <h2>Informations client</h2>
            <div class="info-ligne">
                <span>Nom</span>
                <span>{{ $commande->utilisateur->prenom }} {{ $commande->utilisateur->nom }}</span>
            </div>
            <div class="info-ligne">
                <span>Email</span>
                <span>{{ $commande->utilisateur->email }}</span>
            </div>
            <div class="info-ligne">
                <span>Téléphone</span>
                <span>{{ $commande->utilisateur->telephone ?? 'Non renseigné' }}</span>
            </div>
            <div class="info-ligne">
                <span>Adresse de livraison</span>
                <span>{{ $commande->adresse_livraison }}</span>
            </div>
        </div>

        <!-- Infos commande -->
        <div class="section-card">
            <h2>Informations commande</h2>
            <div class="info-ligne">
                <span>Date</span>
                <span>{{ $commande->created_at->format('d/m/Y à H:i') }}</span>
            </div>
            <div class="info-ligne">
                <span>Mode de livraison</span>
                <span>{{ $commande->mode_livraison === 'express' ? 'Express' : 'Standard' }}</span>
            </div>
            <div class="info-ligne">
                <span>Statut actuel</span>
                <span>
                    <span class="badge-statut {{ $commande->statut }}">
                        @if($commande->statut === 'en_attente') En attente
                        @elseif($commande->statut === 'payee') Payée
                        @elseif($commande->statut === 'expediee') Expédiée
                        @else Annulée
                        @endif
                    </span>
                </span>
            </div>

            <!-- Changer statut -->
            <form method="POST"
                  action="{{ route('admin.commandes.statut', $commande->id) }}"
                  class="statut-form">
                @csrf
                <select name="statut">
                    <option value="en_attente" {{ $commande->statut === 'en_attente' ? 'selected' : '' }}>
                        En attente
                    </option>
                    <option value="payee" {{ $commande->statut === 'payee' ? 'selected' : '' }}>
                        Payée
                    </option>
                    <option value="expediee" {{ $commande->statut === 'expediee' ? 'selected' : '' }}>
                        Expédiée
                    </option>
                    <option value="annulee" {{ $commande->statut === 'annulee' ? 'selected' : '' }}>
                        Annulée
                    </option>
                </select>
                <button type="submit" class="btn-statut">Mettre à jour</button>
            </form>
        </div>
    </div>

    <!-- Produits commandés -->
    <div class="section-card">
        <h2>Produits commandés</h2>
        <table>
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Prix unitaire</th>
                    <th>Quantité</th>
                    <th>Sous-total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($commande->lignesCommande as $ligne)
                <tr>
                    <td><strong>{{ $ligne->produit->nom }}</strong></td>
                    <td>{{ number_format($ligne->prix_unitaire, 2, ',', ' ') }} €</td>
                    <td>{{ $ligne->quantite }}</td>
                    <td>{{ number_format($ligne->quantite * $ligne->prix_unitaire, 2, ',', ' ') }} €</td>
                </tr>
                @endforeach
                <tr class="total-ligne">
                    <td colspan="3">Total TTC</td>
                    <td>{{ number_format($commande->total, 2, ',', ' ') }} €</td>
                </tr>
            </tbody>
        </table>
    </div>

</main>

</body>
</html> 