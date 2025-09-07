<?php
session_start();
include 'config.php';

// Jeśli użytkownik jest już zalogowany, przekieruj go
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $haslo = $_POST['haslo'];

    if (empty($email) || empty($haslo)) {
        $error = "Proszę wypełnić wszystkie pola";
    } else {
        // Sprawdzenie użytkownika
        $stmt = $pdo->prepare("SELECT * FROM account_doc WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($haslo, $user['haslo'])) {
            // Ustawienie sesji
            $_SESSION['user_id'] = $user['pesel'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['imie'] . ' ' . $user['nazwisko'];
            $_SESSION['is_admin'] = $user['admin'];

            header('Location: index.php');
            exit;
        } else {
            $error = "Nieprawidłowy email lub hasło";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logowanie - mBochnia</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #ff5151;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
        }
        .login-container {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
        }
        .logo-text {
            color: #ff5151;
            font-weight: bold;
            font-size: 2rem;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .form-control {
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 1rem;
        }
        .form-control:focus {
            border-color: #ff5151;
            box-shadow: 0 0 0 0.2rem rgba(255, 81, 81, 0.25);
        }
        .btn-primary {
            background-color: #000;
            border-color: #000;
            border-radius: 8px;
            padding: 12px;
            font-weight: bold;
        }
        .btn-primary:hover {
            background-color: #333;
            border-color: #333;
        }
        .alert {
            border-radius: 8px;
        }
    </style>
</head>
<body>
<div class="login-container">
    <div class="logo-text">mBochnia</div>
    <h2 class="text-center mb-4">Logowanie</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email"
                   value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Hasło</label>
            <input type="password" class="form-control" name="haslo" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Zaloguj się</button>
    </form>

    <div class="text-center mt-3">
        <p>Nie masz konta? <a href="register.php" style="color: #ff5151; font-weight: bold;">Zarejestruj się</a></p>
    </div>
</div>
</body>
</html>