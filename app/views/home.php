<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to mBochnia</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<header>
    <h1>Welcome to mBochnia</h1>
</header>

<main>
    <section class="intro">
        <p>
            Stay connected with your city. Read the latest updates, announcements, and important 
            information directly from Bochnia officials.
        </p>
    </section>

    <div class="container center">
        <nav class="auth-links">
            <div class="action-buttons">
                <a href="/feed" class="btn btn-light">View City Feed</a>
            </div>
            <div class="action-buttons" style="margin-top: 15px;">
                <a href="/login" class="btn btn-primary">Login</a>
                <a href="/register" class="btn btn-secondary">Create Account</a>
            </div>
        </nav>
    </div>

    <section class="note center">
        <p class="text-muted">
            You can browse the feed without logging in, but some features require an account.
        </p>
    </section>
</main>

<footer>
    <p>&copy; <?= date('Y') ?> mBochnia. All rights reserved.</p>
</footer>

</body>
</html>

