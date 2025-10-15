<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nie udało się zalogować — mBochnia</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<header class="container center">
    <h1>Nie udało się zalogować</h1>
</header>

<main class="container center">
    <div class="card">
        <h2>Niepoprawne dane</h2>
        <p>Wprowadzony e-mail lub hasło jest nieprawidłowe. Spróbuj ponownie</p>

        <div class="action-buttons">
            <a href="/login" class="btn btn-primary">Wróć do logowania</a>
            <a href="/register" class="btn btn-secondary">Utwórz konto</a>
        </div>
    </div>
</main>

<footer class="footer container">
    <p>&copy; <?= date('Y') ?> mBochnia</p>
</footer>

</body>
</html>
