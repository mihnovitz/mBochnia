<?php if (!empty($errors)): ?>
    <div class="error-messages">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>mBochnia — Zarejestruj się</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

    <main class="container">
        <h1 class="center">Utwórz konto</h1>

        <form action="/register" method="POST" class="form">
            <div class="form-group">
                <label for="first_name">Imie:</label>
                <input type="text" id="first_name" name="first_name" required>
            </div>

            <div class="form-group">
                <label for="last_name">Nazwisko:</label>
                <input type="text" id="last_name" name="last_name" required>
            </div>

            <div class="form-group">
                <label for="address">Adres zamieszkania:</label>
                <input type="text" id="address" name="address" required>
            </div>

            <div class="form-group">
                <label for="phone">Telefon:</label>
                <input type="text" id="phone" name="phone" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">hasło:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-actions center">
                <button type="submit" class="btn btn-primary">zarejestruj się</button>
            </div>
        </form>
    </main>

</body>
</html>

