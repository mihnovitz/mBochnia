<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Failed — mBochnia</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<header class="container center">
    <h1>Login Failed</h1>
</header>

<main class="container center">
    <div class="card">
        <h2>Invalid Credentials</h2>
        <p>The email or password you entered is incorrect. Please try again.</p>

        <div class="action-buttons">
            <a href="/login" class="btn btn-primary">Back to Login</a>
            <a href="/register" class="btn btn-secondary">Create Account</a>
        </div>
    </div>
</main>

<footer class="footer container">
    <p>&copy; <?= date('Y') ?> mBochnia</p>
</footer>

</body>
</html>
