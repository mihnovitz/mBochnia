<?php /** @var array $user */ ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Account — mBochnia</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

    <header class="header">
        <h1>My Account</h1>
        <div class="nav-buttons">
            <a href="/feed" class="btn btn-light">Feed</a>
            <a href="/logout" class="btn btn-light">Logout</a>
        </div>
    </header>

    <main class="container">
        <h2>Account Details</h2>

        <?php if (isset($_GET['updated'])): ?>
            <p class="success-message center">✅ Account updated successfully!</p>
        <?php endif; ?>

        <form method="POST" action="/account/update" class="form">
            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required>
            </div>

            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required>
            </div>

            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?= htmlspecialchars($user['address'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>
            </div>

            <div class="form-group">
                <label for="password">New Password (optional):</label>
                <input type="password" id="password" name="password">
            </div>

            <div class="form-actions center">
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> mBochnia</p>
    </footer>

</body>
</html>

