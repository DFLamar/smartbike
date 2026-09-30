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
        .content h2 { color: #2E7D32; font-size: 18px; margin-bottom: 20px; }
        .info-ligne { display: flex; padding: 12px 0; border-bottom: 1px solid #F5F5F5; }
        .info-label { font-weight: bold; color: #555; width: 120px; flex-shrink: 0; font-size: 14px; }
        .info-valeur { color: #212121; font-size: 14px; }
        .message-box { background: #F5F5F5; border-radius: 8px; padding: 20px; margin-top: 20px; }
        .message-box h3 { color: #2E7D32; margin: 0 0 10px; font-size: 15px; }
        .message-box p { color: #212121; font-size: 14px; line-height: 1.6; margin: 0; }
        .footer { background: #212121; padding: 20px; text-align: center; }
        .footer p { color: #9E9E9E; font-size: 12px; margin: 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>SmartBike</h1>
            <p>Nouveau message de contact</p>
        </div>
        <div class="content">
            <h2>Nouveau message reçu</h2>
            <div class="info-ligne">
                <span class="info-label">Nom</span>
                <span class="info-valeur">{{ $nom }}</span>
            </div>
            <div class="info-ligne">
                <span class="info-label">Email</span>
                <span class="info-valeur">{{ $email }}</span>
            </div>
            <div class="info-ligne">
                <span class="info-label">Sujet</span>
                <span class="info-valeur">{{ $sujet }}</span>
            </div>
            <div class="message-box">
                <h3>Message :</h3>
                <p>{{ $message }}</p>
            </div>
        </div>
        <div class="footer">
            <p>© 2026 SmartBike — Ce message a été envoyé depuis le formulaire de contact</p>
        </div>
    </div>
</body>
</html>