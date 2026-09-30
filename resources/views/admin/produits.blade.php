<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartBike — Gestion Produits</title>
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
        .btn-ajouter {
            background: #2340D9; color: #ECEEF1; padding: 12px 24px;
            border-radius: 8px; text-decoration: none;
            font-weight: bold; font-size: 14px;
        }
        .btn-ajouter:hover { background: #161B26; }

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
        .produit-photo {
            width: 55px; height: 55px; border-radius: 8px;
            background: #D5D9DF; display: flex; align-items: center;
            justify-content: center; font-size: 12px; color: #2340D9;
            overflow: hidden;
        }
        .produit-photo img { width: 100%; height: 100%; object-fit: cover; }
        .badge-stock {
            display: inline-block; padding: 4px 10px;
            border-radius: 20px; font-size: 12px; font-weight: bold;
        }
        .badge-stock.ok { background: #D5D9DF; color: #2340D9; }
        .badge-stock.low { background: #F5C518; color: #161B26; }
        .badge-stock.out { background: #D5D9DF; color: #D6362B; }
        .actions { display: flex; gap: 8px; }
        .btn-modifier {
            background: #D5D9DF; color: #2340D9; padding: 6px 12px;
            border-radius: 6px; text-decoration: none; font-size: 13px;
        }
        .btn-modifier:hover { background: #2340D9; color: #ECEEF1; }
        .btn-supprimer {
            background: #D5D9DF; color: #D6362B; padding: 6px 12px;
            border-radius: 6px; text-decoration: none; font-size: 13px;
            border: none; cursor: pointer;
        }
        .btn-supprimer:hover { background: #D6362B; color: #ECEEF1; }

        /* ─── MESSAGES ─── */
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
        <li><a href="{{ route('admin.produits') }}" class="active">Produits</a></li>
        <li><a href="{{ route('admin.commandes') }}">Commandes</a></li>
        <li class="deconnexion"><a href="#" onclick="event.preventDefault(); document.getElementById('form-deconnexion').submit();">Déconnexion</a><form id="form-deconnexion" method="POST" action="{{ route('deconnexion') }}" style="display:none">@csrf</form></li>
    </ul>
</aside>

<!-- ─── CONTENU ─── -->
<main class="main-content">

    <div class="page-header">
        <h1 class="page-title">Gestion des produits</h1>
        <a href="{{ route('admin.produits.ajouter') }}" class="btn-ajouter">
            + Ajouter un produit
        </a>
    </div>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="section-card">
        <table>
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Nom</th>
                    <th>Catégorie</th>
                    <th>Prix</th>
                    <th>Autonomie</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>            </thead>
            <tbody>
                @forelse($produits as $produit)
                <tr>
                    <td>
                        <div class="produit-photo">
                            @if($produit->image)
                                <img src="{{ asset('storage/' . trim($produit->image)) }}" alt="{{ $produit->nom }}">
                            @else
                                Aucune
                            @endif
                        </div>
                    </td>
                    <td><strong>{{ $produit->nom }}</strong></td>
                    <td>{{ $produit->categorie->libelle ?? '—' }}</td>
                    <td>{{ number_format($produit->prix, 2, ',', ' ') }} €</td>
                    <td>{{ $produit->autonomie }} km</td>
                    <td>
                        @if($produit->stock == 0)
                            <span class="badge-stock out">Rupture</span>
                        @elseif($produit->stock <= 5)
                            <span class="badge-stock low">{{ $produit->stock }}</span>
                        @else
                            <span class="badge-stock ok">{{ $produit->stock }}</span>
                        @endif
                    </td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('admin.produits.modifier', $produit->id) }}" class="btn-modifier">Modifier</a>
                            <form method="POST" action="{{ route('admin.produits.supprimer', $produit->id) }}"
                                  onsubmit="return confirm('Supprimer le produit « {{ addslashes($produit->nom) }} » ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-supprimer">Supprimer</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; color:#4A5263; padding:30px;">
                        Aucun produit pour le moment.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</main>

</body>
</html>
