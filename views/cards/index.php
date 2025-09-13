<?php
// app/views/cards/index.php

require_once __DIR__ . '/../views/partials/modals/mka_modal.php';
require_once __DIR__ . '/../views/partials/modals/rpk_modal.php';
require_once __DIR__ . '/../views/partials/modals/res_modal.php';
require_once __DIR__ . '/../views/partials/modals/edit_modals.php';
require_once __DIR__ . '/../views/partials/modals/qr_modals.php';
$currentUser = Auth::user();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dokumenty - mBochnia</title>
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">  -->

    <link rel="stylesheet" href="assets/css/theme.css">
    <link rel="stylesheet" href="assets/css/auth.css"> <!-- For auth pages -->
    <link rel="stylesheet" href="assets/css/admin.css"> <!-- For admin pages -->
    <style>
        .card-item { border: 2px solid #ddd; border-radius: 10px; padding: 1.2rem; margin-bottom: 1rem; background: #f9f9f9; }
        .card-status { font-weight: bold; padding: 4px 8px; border-radius: 5px; display: inline-block; margin-bottom: 8px; font-size: 0.9rem; }
        .status-active { background-color: #28a745; color: white; }
        .status-none { background-color: #6c757d; color: white; }
        .add-card-btn { background-color: #000; color: white; border: none; border-radius: 8px; padding: 8px 16px; font-weight: bold; margin-top: 8px; }
        .card-details { background: white; padding: 1rem; border-radius: 8px; margin-top: 1rem; border: 1px solid #eee; }
        .card-number { font-family: monospace; font-size: 1.1rem; letter-spacing: 2px; background: #f8f9fa; padding: 8px; border-radius: 5px; border: 1px dashed #ccc; }
        .qr-code { width: 150px; height: 150px; border: 2px solid #ddd; border-radius: 10px; padding: 10px; background: white; }
        .card-actions { margin-top: 1rem; display: flex; gap: 10px; flex-wrap: wrap; }
        .btn-sm { padding: 5px 10px; font-size: 0.8rem; }
    </style>
</head>
<body>

<div class="main-container">
    <nav class="navbar">
        <div class="container-fluid">
            <span class="navbar-text me-auto"><strong>mBochnia</strong> - Panel Użytkownika</span>
            <div class="d-flex">
                <a class="btn btn-outline-primary me-2" href="index.php?action=announcements">Ogłoszenia</a>
                <a class="btn btn-outline-primary me-2" href="index.php?action=applications">Wnioski</a>
                <a class="btn btn-outline-primary me-2" href="index.php?action=home">Strona Główna</a>
                <a class="btn btn-primary" href="index.php?action=logout">Wyloguj</a>
            </div>
        </div>
    </nav>

    <div class="logo-text">mBochnia</div>

    <div class="content-wrapper">
        <div class="sidebar">
            <div class="sidebar-title">Menu</div>
            <div class="menu-buttons">
                <button class="menu-btn active">Dokumenty</button>
                <button class="menu-btn" onclick="location.href='index.php?action=announcements'">Ogłoszenia</button>
                <button class="menu-btn" onclick="location.href='index.php?action=applications'">Wnioski</button>
                <?php if ($is_admin): ?>
                    <button class="menu-btn" onclick="location.href='index.php?action=admin-users'" style="background-color: #ff5151;">Panel Admina</button>
                <?php endif; ?>
            </div>
            <div class="logout-container">
                <a href="index.php?action=logout" class="logout-btn">Wyloguj się</a>
            </div>
        </div>

        <div class="main-content">
            <div class="content-header">
                <h1 class="content-title">Dokumenty</h1>
                <p class="content-description">Zarządzaj swoimi kartami i dokumentami</p>
            </div>

            <div class="content-body">
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">Operacja wykonana pomyślnie!</div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-danger"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
                <?php endif; ?>

                <?php if ($card_count === 0): ?>
                    <div class="alert alert-info">
                        <h4>Obecnie nie posiadasz żadnej karty</h4>
                        <p>Możesz dodać nową kartę korzystając z poniższych przycisków.</p>
                    </div>
                <?php endif; ?>

                <div class="card-section">
                    <h3>Twoje karty</h3>

                    <!-- MKA Card -->
                    <div class="card-item">
                        <h4>Małopolska Karta Aglomeracyjna (MKA)</h4>
                        <?php if ($has_mka): ?>
                            <span class="card-status status-active">Aktywna</span>
                            <div class="card-details">
                                <p><strong>Numer karty:</strong> <span class="card-number"><?php echo $mka_data['id_karty']; ?></span></p>
                                <p><strong>Typ karty:</strong> <?php echo htmlspecialchars($mka_data['typ_karty']); ?></p>
                                <p><strong>Ważna do:</strong> <?php echo $mka_data['formatted_expiry']; ?></p>
                                <p><strong>Strefa:</strong> <?php echo $mka_data['strefa']; ?></p>
                            </div>
                            <div class="card-actions">
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editMkaModal">Edytuj kartę</button>
                                <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#qrMkaModal">Kontrola biletu</button>
                            </div>
                        <?php else: ?>
                            <span class="card-status status-none">Brak karty</span>
                            <p>Nie posiadasz jeszcze karty MKA.</p>
                            <button class="add-card-btn" data-bs-toggle="modal" data-bs-target="#mkaModal">Dodaj kartę MKA</button>
                        <?php endif; ?>
                    </div>

                    <!-- RPK Card -->
                    <div class="card-item">
                        <h4>Karta RPK Bochnia</h4>
                        <?php if ($has_rpk): ?>
                            <span class="card-status status-active">Aktywna</span>
                            <div class="card-details">
                                <p><strong>Numer karty:</strong> <span class="card-number"><?php echo $rpk_data['id_karty']; ?></span></p>
                                <p><strong>Typ karty:</strong> <?php echo htmlspecialchars($rpk_data['typ_karty']); ?></p>
                                <p><strong>Ważna do:</strong> <?php echo $rpk_data['formatted_expiry']; ?></p>
                            </div>
                            <div class="card-actions">
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editRpkModal">Edytuj kartę</button>
                                <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#qrRpkModal">Kontrola biletu</button>
                            </div>
                        <?php else: ?>
                            <span class="card-status status-none">Brak karty</span>
                            <p>Nie posiadasz jeszcze karty RPK.</p>
                            <button class="add-card-btn" data-bs-toggle="modal" data-bs-target="#rpkModal">Dodaj kartę RPK</button>
                        <?php endif; ?>
                    </div>

                    <!-- RES Card -->
                    <div class="card-item">
                        <h4>Karta Mieszkańca</h4>
                        <?php if ($has_res): ?>
                            <span class="card-status status-active">Aktywna</span>
                            <div class="card-details">
                                <p><strong>Numer karty:</strong> <span class="card-number"><?php echo $res_data['id_karty']; ?></span></p>
                                <p><strong>Data zameldowania:</strong> <?php echo $res_data['formatted_registration']; ?></p>
                                <p><strong>Adres:</strong> <?php echo htmlspecialchars($res_data['osiedle']); ?>,
                                    <?php echo htmlspecialchars($res_data['ulica']); ?>
                                    <?php if (!empty($res_data['nr_domu'])) echo ' ' . $res_data['nr_domu']; ?>
                                    <?php if (!empty($res_data['nr_mieszkania'])) echo '/' . $res_data['nr_mieszkania']; ?>
                                </p>
                            </div>
                            <div class="card-actions">
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editResModal">Edytuj kartę</button>
                                <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#qrResModal">Kontrola biletu</button>
                            </div>
                        <?php else: ?>
                            <span class="card-status status-none">Brak karty</span>
                            <p>Nie posiadasz jeszcze Karty Mieszkańca.</p>
                            <button class="add-card-btn" data-bs-toggle="modal" data-bs-target="#resModal">Dodaj Kartę Mieszkańca</button>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ($is_admin): ?>
                    <div class="alert alert-warning mt-3">
                        <h5>Status administratora</h5>
                        <p>Posiadasz uprawnienia administratora systemu.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Include the modals (we'll create this as a separate partial) -->
<?php require_once __DIR__ . '/../views/partials/card_modals.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('.menu-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            document.querySelectorAll('.menu-btn').forEach(function(btn) {
                btn.classList.remove('active');
            });
            this.classList.add('active');
        });
    });
</script>
</body>
</html>