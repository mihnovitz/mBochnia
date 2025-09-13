<?php
// app/views/admin/create_user.php

// Check if user is admin
if (!Auth::isAdmin()) {
    header('Location: index.php?action=home');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nowy Użytkownik - mBochnia</title>

    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">  -->

    <link rel="stylesheet" href="assets/css/theme.css">
    <link rel="stylesheet" href="assets/css/auth.css"> <!-- For auth pages -->
    <link rel="stylesheet" href="assets/css/admin.css"> <!-- For admin pages -->



    <style>
        body { background-color: #f8f9fa; }
        .container { background: white; border-radius: 15px; padding: 2rem; margin-top: 2rem; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
        h2 { font-weight: 600; margin-bottom: 1.5rem; color: #0d6efd; }
        .form-label { font-weight: bold; }
        .btn-primary { background-color: #000; border-color: #000; border-radius: 8px; padding: 10px 20px; font-weight: bold; }
        .btn-primary:hover { background-color: #333; border-color: #333; }
    </style>
</head>
<body>
<div class="container my-5">
    <h2>Nowy Klient</h2>

    <?php if (!empty($errorMessage)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong><?php echo $errorMessage; ?></strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form method="post">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">PESEL *</label>
                    <input type="text" class="form-control" name="pesel" value="<?php echo htmlspecialchars($formData['pesel']); ?>"
                           maxlength="11" required pattern="[0-9]{11}" title="PESEL musi składać się z 11 cyfr">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Email *</label>
                    <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($formData['email']); ?>" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Imię *</label>
                    <input type="text" class="form-control" name="imie" value="<?php echo htmlspecialchars($formData['imie']); ?>" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Nazwisko *</label>
                    <input type="text" class="form-control" name="nazwisko" value="<?php echo htmlspecialchars($formData['nazwisko']); ?>" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Data Urodzenia *</label>
                    <input type="date" class="form-control" name="data_urodzenia" value="<?php echo htmlspecialchars($formData['data_urodzenia']); ?>" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Płeć *</label>
                    <select class="form-select" name="plec" required>
                        <option value="">Wybierz płeć</option>
                        <option value="M" <?php echo $formData['plec'] == 'M' ? 'selected' : ''; ?>>Mężczyzna</option>
                        <option value="K" <?php echo $formData['plec'] == 'K' ? 'selected' : ''; ?>>Kobieta</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Saldo *</label>
                    <input type="number" step="0.01" class="form-control" name="saldo"
                           value="<?php echo htmlspecialchars($formData['saldo']); ?>" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Hasło *</label>
                    <input type="password" class="form-control" name="haslo" value="<?php echo htmlspecialchars($formData['haslo']); ?>" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Uprawnienia administratora</label>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="admin" id="admin"
                               value="1" <?php echo $formData['admin'] == 't' ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="admin">
                            Użytkownik jest administratorem
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-sm-6">
                <button type="submit" class="btn btn-primary w-100">Dodaj użytkownika</button>
            </div>
            <div class="col-sm-6">
                <a class="btn btn-outline-secondary w-100" href="index.php?action=admin-users" role="button">Anuluj</a>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>