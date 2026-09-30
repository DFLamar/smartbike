<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartBike — Gestion Commandes</title>
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
        .page-title { font-size: 28px; margin-bottom: 30px; }

        /* ─── TABLEAU ─── */
        .section-card {
            background: #ECEEF1; border-radius: 12px;
            padding: 25px; border: 1px solid #161B26;
        }
        table { width: 100%; border-collapse: collapse; }
        th {
            background: #D5D9DF; padding: 12px 15px;
            text-align: left; font-size: 13px; color: #4A5263;
            border-bottom: 2px solid #B8BFCA;
        }
        td {
            padding: 12px 15px; font-size: 14px;
            border-bottom: 1px solid #B8BFCA; vertical-align: middle;
        }
        tr:hover td { background: #D5D9DF; }
        .badge-statut {
            display: inline-block; padding: 4px 12px;
            border-radius: 20px; font-size: 12px; font-weight: bold;
        }
        .badge-statut.en_attente { background: #D5D9DF; color: #4A5263; }
        .badge-statut.payee { background: #D5D9DF; color: #2340D9; }
        .badge-statut.expediee { background: #161B26; color: #ECEEF1; }
        .badge-statut.annulee { background: #D5D9DF; color: #D6362B; }
        .btn-detail {
            background: #D5D9DF; color: #2340D9; padding: 6px 12px;
            border-radius: 6px; text-decoration: none; font-size: 13px;
        }
        .btn-detail:hover { background: #2340D9; color: #ECEEF1; }
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
        }
        @media (max-width: 600px) {
            .page-title { font-size: 22px; }
            .page-header { flex-wrap: wrap; gap: 12px; }
            .section-card { padding: 18px; }
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
    <h1 class="page-title">Gestion des commandes</h1>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="section-card">
        <table>
            <thead>
                <tr>
                    <th>Numéro</th>
                    <th>Client</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Livraison</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($commandes as $commande)
                <tr>
                    <td><strong>#SMB-{{ str_pad($commande->id, 4, '0', STR_PAD_LEFT) }}</strong></td>
                    <td>{{ $commande->utilisateur->prenom }} {{ $commande->utilisateur->nom }}</td>
                    <td>{{ $commande->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ number_format($commande->total, 2, ',', ' ') }} €</td>
                    <td>{{ $commande->mode_livraison === 'express' ? 'Express' : 'Standard' }}</td>
                    <td>
                        <span class="badge-statut {{ $commande->statut }}">
                            @if($commande->statut === 'en_attente') En attente
                            @elseif($commande->statut === 'payee') Payée
                            @elseif($commande->statut === 'expediee') Expédiée
                            @else Annulée
                            @endif
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.commandes.detail', $commande->id) }}"
                           class="btn-detail">Détail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; color:#4A5263; padding:30px;">
                        Aucune commande pour le moment.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</main>

</body>
</html>