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
    <title>mBochnia — Login</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

    <main class="container">
        <h1 class="center">Log in</h1>

        <form action="/login" method="POST" class="form">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-actions center">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
        </form>
    </main>

</body>
</html>

