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
        .content p { color: #212121; font-size: 14px; line-height: 1.6; }
        .bouton { text-align: center; margin: 30px 0; }
        .bouton a { background: #2E7D32; color: #fff; padding: 14px 28px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 15px; }
        .petit { color: #9E9E9E; font-size: 12px; word-break: break-all; }
        .footer { background: #212121; padding: 20px; text-align: center; }
        .footer p { color: #9E9E9E; font-size: 12px; margin: 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>SmartBike</h1>
            <p>Mot de passe oublié</p>
        </div>
        <div class="content">
            <h2>Bonjour {{ $utilisateur->prenom }},</h2>
            <p>Vous avez demandé à réinitialiser le mot de passe de votre compte SmartBike.
               Cliquez sur le bouton ci-dessous pour en choisir un nouveau.</p>
            <div class="bouton">
                <a href="{{ $lien }}">Choisir un nouveau mot de passe</a>
            </div>
            <p>Ce lien est valable <strong>60 minutes</strong>.
               Si vous n'êtes pas à l'origine de cette demande, ignorez simplement cet email :
               votre mot de passe actuel reste inchangé.</p>
            <p class="petit">Si le bouton ne fonctionne pas, copiez ce lien dans votre navigateur :<br>{{ $lien }}</p>
        </div>
        <div class="footer">
            <p>© 2026 SmartBike</p>
        </div>
    </div>
</body>
</html>
