<?php
// app/views/admin/users.php

// Check if user is admin (extra security)
if (!Auth::isAdmin()) {
    header('Location: index.php?action=home');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administracyjny - mBochnia</title>
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">  -->

    <link rel="stylesheet" href="assets/css/theme.css">
    <link rel="stylesheet" href="assets/css/auth.css"> <!-- For auth pages -->
    <link rel="stylesheet" href="assets/css/admin.css"> <!-- For admin pages -->
    <link rel="stylesheet" href="assets/css/theme.css">
    <style>
        body { background-color: #ff5151; font-family: Arial, sans-serif; padding-top: 20px; min-height: 100vh; }
        .container { background: white; border-radius: 15px; padding: 2rem; box-shadow: 0 10px 25px rgba(0,0,0,0.2); margin-bottom: 2rem; }
        .logo-text { color: #ff5151; font-weight: bold; font-size: 2rem; text-align: center; margin-bottom: 1.5rem; }
        .btn-primary { background-color: #000; border-color: #000; border-radius: 8px; padding: 10px 20px; font-weight: bold; }
        .btn-primary:hover { background-color: #333; border-color: #333; }
        .btn-danger { border-radius: 8px; }
        .btn-outline-primary { border-radius: 8px; border-color: #ff5151; color: #ff5151; }
        .btn-outline-primary:hover { background-color: #ff5151; border-color: #ff5151; color: white; }
        .navbar { background-color: #000; border-radius: 10px; margin-bottom: 2rem; padding: 1rem; }
        .navbar-text { color: white !important; }
        th { background-color: #ff5151; color: white; font-weight: bold; position: sticky; top: 0; z-index: 10; }
        tr:hover { background-color: #f5f5f5; }
        .table-container { overflow-x: auto; max-height: 600px; border: 1px solid #ddd; border-radius: 8px; }
        .table thead th { background-color: #ff5151; color: white; border-bottom: 2px solid #ddd; }
    </style>
</head>
<body>
<!-- Nawigacja -->
<div class="container">
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <span class="navbar-text me-auto"><strong>mBochnia</strong> - Panel Administracyjny</span>
            <div class="d-flex">
                <a class="btn btn-primary" href="index.php?action=create-user" role="button">Nowy Klient</a>
                <a class="btn btn-outline-primary me-2" href="index.php?action=home">Strona Główna</a>
                <a class="btn btn-primary" href="index.php?action=logout">Wyloguj</a>
            </div>
        </div>
    </nav>

    <div class="logo-text">mBochnia</div>
    <h2 class="text-center mb-4">Lista Klientów</h2>

    <!-- Display success/error messages -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a class="btn btn-primary" href="index.php?action=create-user" role="button">Nowy Klient</a>
    </div>

    <div class="table-container">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>PESEL</th>
                <th>Imię</th>
                <th>Nazwisko</th>
                <th>Data Urodzenia</th>
                <th>Płeć</th>
                <th>Saldo</th>
                <th>Admin</th>
                <th>Akcje</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['pesel']); ?></td>
                    <td><?php echo htmlspecialchars($user['imie']); ?></td>
                    <td><?php echo htmlspecialchars($user['nazwisko']); ?></td>
                    <td><?php echo htmlspecialchars($user['formatted_dob']); ?></td>
                    <td><?php echo htmlspecialchars($user['plec']); ?></td>
                    <td><?php echo htmlspecialchars($user['formatted_saldo']); ?></td>
                    <td><?php echo htmlspecialchars($user['admin_display']); ?></td>
                    <td>
                        <a class='btn btn-primary btn-sm' href='index.php?action=edit-user&id=<?php echo $user['pesel']; ?>'>Edytuj</a>
                        <a class='btn btn-danger btn-sm' href='index.php?action=delete-user&id=<?php echo $user['pesel']; ?>&csrf=<?php echo $_SESSION['csrf_token'] ?? ''; ?>'
                           onclick='return confirm("Czy na pewno chcesz usunąć to konto?")'>Usuń</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>