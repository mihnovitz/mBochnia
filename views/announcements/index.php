<?php
// app/views/announcements/index.php
$currentUser = Auth::user();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ogłoszenia - mBochnia</title>
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">  -->

    <link rel="stylesheet" href="assets/css/theme.css">
    <link rel="stylesheet" href="assets/css/auth.css"> <!-- For auth pages -->
    <link rel="stylesheet" href="assets/css/admin.css"> <!-- For admin pages -->
    <link rel="stylesheet" href="assets/css/theme.css">
    <style>
        .announcement-card { border: 2px solid #ff5151; border-radius: 10px; padding: 1.5rem; margin-bottom: 1.5rem; background: white; }
        .announcement-header { border-bottom: 2px solid #f0f0f0; padding-bottom: 1rem; margin-bottom: 1rem; }
        .announcement-title { color: #ff5151; font-weight: bold; font-size: 1.3rem; margin-bottom: 0.5rem; }
        .announcement-meta { color: #666; font-size: 0.9rem; }
        .announcement-content { font-size: 1rem; line-height: 1.6; }
        .add-announcement-btn { background-color: #ff5151; color: white; border: none; border-radius: 8px; padding: 10px 20px; font-weight: bold; }
        .add-announcement-btn:hover { background-color: #e04040; }
    </style>
</head>
<body>
<div class="main-container">
    <nav class="navbar">
        <div class="container-fluid">
            <span class="navbar-text me-auto"><strong>mBochnia</strong> - Ogłoszenia</span>
            <div class="d-flex">
                <a class="btn btn-outline-primary me-2" href="index.php?action=documents">Dokumenty</a>
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
                <button class="menu-btn" onclick="location.href='index.php?action=documents'">Dokumenty</button>
                <button class="menu-btn active">Ogłoszenia</button>
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
                <h1 class="content-title">Ogłoszenia</h1>
                <p class="content-description">Aktualne informacje i komunikaty dla mieszkańców</p>
            </div>

            <div class="content-body">
                <?php if ($success): ?>
                    <div class="alert alert-success">Ogłoszenie zostało dodane pomyślnie!</div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-danger"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
                <?php endif; ?>

                <?php if ($is_admin): ?>
                    <div class="text-end mb-3">
                        <button class="add-announcement-btn" data-bs-toggle="modal" data-bs-target="#addAnnouncementModal">
                            + Dodaj ogłoszenie
                        </button>
                    </div>
                <?php endif; ?>

                <?php if (empty($announcements)): ?>
                    <div class="alert alert-info">
                        <h4>Brak ogłoszeń</h4>
                        <p>Obecnie nie ma żadnych ogłoszeń.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($announcements as $announcement): ?>
                        <div class="announcement-card">
                            <div class="announcement-header">
                                <h3 class="announcement-title"><?php echo htmlspecialchars($announcement['watek']); ?></h3>
                                <div class="announcement-meta">
                                    <strong>Data:</strong> <?php echo $announcement['formatted_date']; ?> |
                                    <strong>Autor:</strong> <?php echo htmlspecialchars($announcement['autor']); ?>
                                </div>
                            </div>
                            <div class="announcement-content">
                                <?php echo nl2br(htmlspecialchars($announcement['tresc'])); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal dodawania ogłoszenia (dla admina) -->
<?php if ($is_admin): ?>
    <div class="modal fade" id="addAnnouncementModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Dodaj nowe ogłoszenie</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="index.php?action=create-announcement">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Data *</label>
                                    <input type="date" class="form-control" name="data" required
                                           value="<?php echo date('Y-m-d'); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Autor *</label>
                                    <input type="text" class="form-control" name="autor" required
                                           value="Administrator">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Temat *</label>
                            <input type="text" class="form-control" name="watek" required
                                   placeholder="np. Remont ulicy, Podwyżka opłat, itp.">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Treść ogłoszenia *</label>
                            <textarea class="form-control" name="tresc" required rows="5"
                                      placeholder="Wpisz treść ogłoszenia..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                        <button type="submit" class="btn btn-primary">Dodaj ogłoszenie</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

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