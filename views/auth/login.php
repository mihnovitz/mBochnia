<?php
// app/views/auth/login.php

// Check if user is already logged in (redirect if they are)
if (Auth::check()) {
    header('Location: index.php?action=dashboard');
    exit;
}

$error = $error ?? '';
$email = $email ?? '';
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logowanie - mBochnia</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/theme.css">
    <link rel="stylesheet" href="assets/css/auth.css">
    <style>
        .auth-container {
            background: linear-gradient(135deg, #ff5151 0%, #e04040 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .auth-card {
            background: white;
            border-radius: 15px;
            padding: 2.5rem;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 450px;
        }
        .auth-logo {
            text-align: center;
            color: #ff5151;
            font-weight: bold;
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
        }
        .auth-title {
            text-align: center;
            color: #333;
            margin-bottom: 2rem;
            font-weight: 600;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            font-weight: 600;
            color: #555;
            margin-bottom: 0.5rem;
        }
        .form-control {
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 16px;
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: #ff5151;
            box-shadow: 0 0 0 3px rgba(255, 81, 81, 0.2);
        }
        .btn-auth {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s;
        }
        .btn-login {
            background: #ff5151;
            color: white;
            border: none;
        }
        .btn-login:hover {
            background: #e04040;
            transform: translateY(-2px);
        }
        .btn-register {
            background: #2980b9;
            color: white;
            border: none;
        }
        .btn-register:hover {
            background: #1f6390;
            transform: translateY(-2px);
        }
        .auth-links {
            text-align: center;
            margin-top: 1.5rem;
        }
        .auth-link {
            color: #666;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s;
        }
        .auth-link:hover {
            color: #ff5151;
        }
    </style>
</head>
<body>
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-logo">mBochnia</div>
        <h2 class="auth-title">Logowanie</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><?php echo htmlspecialchars($error); ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form method="post" action="index.php?action=login">
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email"
                       value="<?php echo htmlspecialchars($email); ?>"
                       placeholder="Wpisz swój email" required>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Hasło</label>
                <input type="password" class="form-control" id="password" name="password"
                       placeholder="Wpisz swoje hasło" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-auth btn-login">Zaloguj się</button>
            </div>
        </form>

        <div class="auth-links">
            <p>Nie masz konta?
                <a href="index.php?action=register" class="auth-link">Zarejestruj się</a>
            </p>
            <p>
                <a href="index.php?action=home" class="auth-link">← Powrót do strony głównej</a>
            </p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Simple form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value.trim();

        if (!email || !password) {
            e.preventDefault();
            alert('Proszę wypełnić wszystkie pola');
            return false;
        }

        if (!email.includes('@')) {
            e.preventDefault();
            alert('Proszę podać poprawny adres email');
            return false;
        }

        return true;
    });

    // Close alert when clicked
    document.querySelectorAll('.alert .btn-close').forEach(button => {
        button.addEventListener('click', function() {
            this.closest('.alert').style.display = 'none';
        });
    });

    // Auto-hide alert after 5 seconds
    const alert = document.querySelector('.alert');
    if (alert) {
        setTimeout(() => {
            alert.style.display = 'none';
        }, 5000);
    }
</script>
</body>
</html>