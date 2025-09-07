<?php
session_start();
// Poprawne include config.php
include 'config.php';

$errors = array();
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Pobranie i walidacja danych
    $pesel = trim($_POST['pesel']);
    $imie = trim($_POST['imie']);
    $nazwisko = trim($_POST['nazwisko']);
    $data_urodzenia = trim($_POST['data_urodzenia']);
    $plec = trim($_POST['plec']);
    $email = trim($_POST['email']);
    $haslo = $_POST['haslo'];
    $potwierdz_haslo = $_POST['potwierdz_haslo'];

    // Walidacja
    if (empty($pesel) || strlen($pesel) != 11 || !is_numeric($pesel)) {
        $errors[] = "PESEL musi mieć 11 cyfr";
    }

    if (empty($imie)) $errors[] = "Imię jest wymagane";
    if (empty($nazwisko)) $errors[] = "Nazwisko jest wymagane";

    if (!strtotime($data_urodzenia)) {
        $errors[] = "Nieprawidłowa data urodzenia";
    }

    if (!in_array(strtoupper($plec), array('M', 'K'))) {
        $errors[] = "Płeć musi być M lub K";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Nieprawidłowy adres email";
    }

    if (strlen($haslo) < 6) {
        $errors[] = "Hasło musi mieć co najmniej 6 znaków";
    }

    if ($haslo !== $potwierdz_haslo) {
        $errors[] = "Hasła nie są identyczne";
    }

    // Sprawdzenie czy email lub PESEL już istnieją
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM account_doc WHERE email = ? OR pesel = ?");
        $stmt->execute(array($email, $pesel));
        if ($stmt->fetchColumn() > 0) {
            $errors[] = "Użytkownik z tym emailem lub PESEL-em już istnieje";
        }
    }

    // Rejestracja użytkownika
    if (empty($errors)) {
        $hashed_password = password_hash($haslo, PASSWORD_DEFAULT);
        $saldo = 0.00; // Domyślne saldo

        try {
            $stmt = $pdo->prepare("INSERT INTO account_doc (pesel, imie, nazwisko, data_urodzenia, plec, saldo, admin, haslo, email) 
                                  VALUES (?, ?, ?, ?, ?, ?, false, ?, ?)");
            $stmt->execute(array($pesel, $imie, $nazwisko, $data_urodzenia, strtoupper($plec), $saldo, $hashed_password, $email));

            $success = "Rejestracja przebiegła pomyślnie! Możesz się teraz zalogować.";
            // Czyszczenie formularza
            $_POST = array();

        } catch (PDOException $e) {
            $errors[] = "Błąd podczas rejestracji: " . $e->getMessage();
        }
    }
}

// Funkcja pomocnicza dla starszych wersji PHP
function getPostValue($field) {
    return isset($_POST[$field]) ? htmlspecialchars($_POST[$field]) : '';
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rejestracja - mBochnia</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #ff5151;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .register-container {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 500px;
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
        .form-label {
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>
<div class="register-container">
    <div class="logo-text">mBochnia</div>
    <h2 class="text-center mb-4">Utwórz konto</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <p class="mb-1"><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success">
            <?php echo $success; ?>
            <div class="mt-2">
                <a href="login.php" class="btn btn-success btn-sm">Zaloguj się</a>
            </div>
        </div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label class="form-label">PESEL *</label>
            <input type="text" class="form-control" name="pesel"
                   value="<?php echo getPostValue('pesel'); ?>" required maxlength="11">
        </div>

        <div class="mb-3">
            <label class="form-label">Imię *</label>
            <input type="text" class="form-control" name="imie"
                   value="<?php echo getPostValue('imie'); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nazwisko *</label>
            <input type="text" class="form-control" name="nazwisko"
                   value="<?php echo getPostValue('nazwisko'); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Data urodzenia *</label>
            <input type="date" class="form-control" name="data_urodzenia"
                   value="<?php echo getPostValue('data_urodzenia'); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Płeć *</label>
            <select class="form-select" name="plec" required>
                <option value="">Wybierz płeć</option>
                <option value="M" <?php echo (isset($_POST['plec']) && $_POST['plec'] == 'M') ? 'selected' : ''; ?>>Mężczyzna</option>
                <option value="K" <?php echo (isset($_POST['plec']) && $_POST['plec'] == 'K') ? 'selected' : ''; ?>>Kobieta</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Email *</label>
            <input type="email" class="form-control" name="email"
                   value="<?php echo getPostValue('email'); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Hasło *</label>
            <input type="password" class="form-control" name="haslo" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Potwierdź hasło *</label>
            <input type="password" class="form-control" name="potwierdz_haslo" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Zarejestruj się</button>
    </form>

    <div class="text-center mt-3">
        <p>Masz już konto? <a href="login.php" style="color: #ff5151; font-weight: bold;">Zaloguj się</a></p>
    </div>
</div>
</body>
</html>