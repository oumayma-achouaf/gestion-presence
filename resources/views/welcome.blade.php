<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestion des Absences</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light min-vh-100 d-flex align-items-center justify-content-center">
    <main class="bg-white border rounded-3 p-4 shadow-sm text-center" style="max-width: 420px;">
        <div class="text-secondary small fw-bold text-uppercase mb-2">Gestion des Absences</div>
        <h1 class="h4 fw-bold mb-3">Connexion au portail</h1>
        <p class="text-secondary mb-4">Accédez à votre espace avec votre code PIN.</p>
        <a class="btn btn-primary w-100" href="{{ route('pin.show') }}">Accéder à l’application</a>
    </main>
</body>
</html>
