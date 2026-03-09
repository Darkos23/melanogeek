<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hors ligne — MelanoGeek</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            min-height: 100vh;
            background: #0F0A06;
            color: #F0E8D8;
            font-family: system-ui, sans-serif;
            display: flex; align-items: center; justify-content: center;
            text-align: center; padding: 24px;
        }
        .icon { font-size: 4rem; margin-bottom: 20px; }
        h1 { font-size: 1.5rem; font-weight: 800; margin-bottom: 10px; }
        p { color: #9A8A78; font-size: .95rem; line-height: 1.6; max-width: 300px; margin: 0 auto 24px; }
        .retry-btn {
            background: #C8522A; color: white; border: none;
            padding: 12px 28px; border-radius: 100px;
            font-size: .9rem; font-weight: 700; cursor: pointer;
        }
    </style>
</head>
<body>
    <div>
        <div class="icon">📡</div>
        <h1>Pas de connexion</h1>
        <p>Tu es hors ligne. Vérifie ta connexion et réessaie.</p>
        <button class="retry-btn" onclick="window.location.reload()">Réessayer</button>
    </div>
</body>
</html>
