<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #F5F5F5; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 12px; overflow: hidden; }
        .header { background: #2E7D32; padding: 30px; text-align: center; }
        .header h1 { color: #fff; margin: 0; font-size: 24px; }
        .header p { color: #C8E6C9; margin: 5px 0 0; font-size: 14px; }
        .content { padding: 30px; }
        .numero-commande {
            background: #E8F5E9; border-radius: 8px; padding: 15px;
            text-align: center; margin-bottom: 25px;
        }
        .numero-commande p { color: #555; font-size: 14px; margin: 0 0 5px; }
        .numero-commande h2 { color: #2E7D32; font-size: 22px; margin: 0; }
        .section-title { color: #2E7D32; font-size: 16px; font-weight: bold; margin: 25px 0 10px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #2E7D32; color: #fff; padding: 10px; text-align: left; font-size: 13px; }
        td { padding: 10px; font-size: 13px; border-bottom: 1px solid #F5F5F5; color: #212121; }
        tr:nth-child(even) td { background: #FAFAFA; }
        .total-row td { font-weight: bold; font-size: 15px; background: #E8F5E9; color: #2E7D32; }
        .info-box {
            background: #F5F5F5; border-radius: 8px;
            padding: 15px; margin-top: 20px;
        }
        .info-box p { margin: 5px 0; font-size: 13px; color: #555; }
        .info-box strong { color: #212121; }
        .badge {
            display: inline-block; padding: 4px 12px;
            border-radius: 20px; font-size: 12px; font-weight: bold;
            background: #FFF3E0; color: #FF9800;
        }
        .footer { background: #212121; padding: 20px; text-align: center; margin-top: 30px; }
        .footer p { color: #9E9E9E; font-size: 12px; margin: 3px 0; }
        .btn {
            display: inline-block; background: #2E7D32; color: #fff;
            padding: 12px 30px; border-radius: 8px; text-decoration: none;
            font-weight: bold; font-size: 14px; margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">

        <!-- Header -->
        <div class="header">
            <h1>SmartBike</h1>
            <p>Confirmation de votre commande</p>
        </div>

        <div class="content">

            <!-- Numéro commande -->
            <div class="numero-commande">
                <p>Merci pour votre commande !</p>
                <h2>#SMB-{{ str_pad($commande->id, 4, '0', STR_PAD_LEFT) }}</h2>
                <span class="badge">En attente de traitement</span>
            </div>

            <!-- Produits -->
            <div class="section-title">Produits commandés</div>
            <table>
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Quantité</th>
                        <th>Prix unitaire</th>
                        <th>Sous-total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($commande->lignesCommande as $ligne)
                    <tr>
                        <td>{{ $ligne->produit->nom }}</td>
                        <td>{{ $ligne->quantite }}</td>
                        <td>{{ number_format($ligne->prix_unitaire, 2, ',', ' ') }} €</td>
                        <td>{{ number_format($ligne->quantite * $ligne->prix_unitaire, 2, ',', ' ') }} €</td>
                    </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="3">Total TTC</td>
                        <td>{{ number_format($commande->total, 2, ',', ' ') }} €</td>
                    </tr>
                </tbody>
            </table>

            <!-- Infos livraison -->
            <div class="section-title">Informations de livraison</div>
            <div class="info-box">
                <p><strong>Adresse :</strong> {{ $commande->adresse_livraison }}</p>
                <p><strong>Mode :</strong>
                    {{ $commande->mode_livraison === 'express' ? 'Express (24-48h)' : 'Standard (3-5 jours)' }}
                </p>
                <p><strong>Date de commande :</strong> {{ $commande->created_at->format('d/m/Y à H:i') }}</p>
            </div>

            <div style="text-align: center;">
                <p style="color: #555; font-size: 13px; margin-top: 20px;">
                    Vous recevrez un email dès l'expédition de votre commande.
                </p>
            </div>

        </div>

        <!-- Footer -->
        <div class="footer">
            <p>© 2025 SmartBike — Tous droits réservés</p>
            <p>Des questions ? Contactez-nous via notre formulaire de contact</p>
        </div>

    </div>
</body>
</html>