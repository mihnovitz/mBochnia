<?php
// app/views/home/dashboard.php
$user = Auth::user();
$is_admin = Auth::isAdmin();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Główny - mBochnia</title>
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">  -->

    <link rel="stylesheet" href="/public/assets/css/theme.css">
    <link rel="stylesheet" href="/public/assets/css/auth.css"> <!-- For auth pages -->
    <link rel="stylesheet" href="/public/assets/css/admin.css"> <!-- For admin pages -->
    <style>
        .dashboard-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        .card-icon {
            font-size: 3rem;
            color: #ff5151;
            margin-bottom: 1rem;
        }
        .welcome-section {
            background: linear-gradient(135deg, #ff5151 0%, #e04040 100%);
            color: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
<div class="main-container">
    <nav class="navbar">
        <div class="container-fluid">
            <span class="navbar-text me-auto"><strong>mBochnia</strong> - Panel Główny</span>
            <div class="d-flex">
                <a class="btn btn-primary" href="index.php?action=logout">Wyloguj</a>
            </div>
        </div>
    </nav>

    <div class="logo-text">mBochnia</div>

    <div class="welcome-section">
        <h2>Witaj, <?php echo htmlspecialchars($user['name']); ?>!</h2>
        <p class="mb-0">Co chcesz dzisiaj zrobić?</p>
    </div>

    <div class="row">
        <!-- Dokumenty -->
        <div class="col-md-4">
            <div class="dashboard-card text-center">
                <div class="card-icon">📄</div>
                <h4>Dokumenty</h4>
                <p>Zarządzaj swoimi kartami miejskimi</p>
                <a href="index.php?action=documents" class="btn btn-primary w-100">Przejdź do dokumentów</a>
            </div>
        </div>

        <!-- Ogłoszenia -->
        <div class="col-md-4">
            <div class="dashboard-card text-center">
                <div class="card-icon">📢</div>
                <h4>Ogłoszenia</h4>
                <p>Sprawdź najnowsze komunikaty</p>
                <a href="index.php?action=announcements" class="btn btn-primary w-100">Przejdź do ogłoszeń</a>
            </div>
        </div>

        <!-- Wnioski -->
        <div class="col-md-4">
            <div class="dashboard-card text-center">
                <div class="card-icon">📋</div>
                <h4>Wnioski</h4>
                <p>Złóż wniosek lub sprawdź status</p>
                <a href="index.php?action=applications" class="btn btn-primary w-100">Przejdź do wniosków</a>
            </div>
        </div>
    </div>

    <?php if ($is_admin): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="dashboard-card">
                    <h4 class="text-center mb-3">Panel Administratora</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-grid gap-2">
                                <a href="index.php?action=admin-users" class="btn btn-danger">
                                    🛠️ Zarządzaj użytkownikami
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-grid gap-2">
                                <a href="index.php?action=announcements" class="btn btn-danger">
                                    📢 Zarządzaj ogłoszeniami
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="row mt-4">
        <div class="col-12">
            <div class="dashboard-card">
                <h4 class="text-center mb-3">Szybkie statystyki</h4>
                <div class="row text-center">
                    <div class="col-md-3">
                        <h5 class="text-primary">0</h5>
                        <small>Aktywnych kart</small>
                    </div>
                    <div class="col-md-3">
                        <h5 class="text-success">0</h5>
                        <small>Nowych ogłoszeń</small>
                    </div>
                    <div class="col-md-3">
                        <h5 class="text-warning">0</h5>
                        <small>Oczekujących wniosków</small>
                    </div>
                    <div class="col-md-3">
                        <h5 class="text-info"><?php echo date('d.m.Y'); ?></h5>
                        <small>Dzisiejsza data</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>