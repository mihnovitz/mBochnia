<?php
// app/views/applications/index.php
$currentUser = Auth::user();
$is_admin = Auth::isAdmin();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wnioski - mBochnia</title>

    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">  -->

    <link rel="stylesheet" href="assets/css/theme.css">
    <link rel="stylesheet" href="assets/css/auth.css"> <!-- For auth pages -->
    <link rel="stylesheet" href="assets/css/admin.css"> <!-- For admin pages -->


    <style>
        .wniosek-card { border: 2px solid #007bff; border-radius: 10px; padding: 1.5rem; margin-bottom: 1.5rem; background: white; }
        .wniosek-header { border-bottom: 2px solid #f0f0f0; padding-bottom: 1rem; margin-bottom: 1rem; }
        .wniosek-meta { color: #666; font-size: 0.9rem; margin-bottom: 1rem; }
        .wniosek-content { font-size: 1rem; line-height: 1.6; margin-bottom: 1rem; }
        .response-section { background: #f8f9fa; padding: 1rem; border-radius: 8px; border-left: 4px solid #28a745; }
        .add-wniosek-btn { background-color: #007bff; color: white; border: none; border-radius: 8px; padding: 10px 20px; font-weight: bold; }
        .add-wniosek-btn:hover { background-color: #0056b3; }
        .response-btn { background-color: #28a745; color: white; border: none; border-radius: 5px; padding: 5px 10px; font-size: 0.8rem; }
        .response-btn:hover { background-color: #1e7e34; }
        .status-badge { padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: bold; }
        .status-pending { background-color: #ffc107; color: #000; }
        .status-answered { background-color: #28a745; color: #fff; }
    </style>
</head>
<body>
<div class="main-container">
    <nav class="navbar">
        <div class="container-fluid">
            <span class="navbar-text me-auto"><strong>mBochnia</strong> - Wnioski</span>
            <div class="d-flex">
                <a class="btn btn-outline-primary me-2" href="index.php?action=documents">Dokumenty</a>
                <a class="btn btn-outline-primary me-2" href="index.php?action=announcements">Ogłoszenia</a>
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
                <button class="menu-btn" onclick="location.href='index.php?action=announcements'">Ogłoszenia</button>
                <button class="menu-btn active">Wnioski</button>
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
                <h1 class="content-title">Wnioski</h1>
                <p class="content-description">Złóż wniosek lub sprawdź status swoich zgłoszeń</p>
            </div>

            <div class="content-body">
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">Wniosek został dodany pomyślnie!</div>
                <?php endif; ?>

                <?php if (isset($_GET['success_response'])): ?>
                    <div class="alert alert-success">Odpowiedź została dodana pomyślnie!</div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-danger"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
                <?php endif; ?>

                <div class="text-end mb-3">
                    <button class="add-wniosek-btn" data-bs-toggle="modal" data-bs-target="#addWniosekModal">
                        + Złóż nowy wniosek
                    </button>
                </div>

                <?php if (empty($applications)): ?>
                    <div class="alert alert-info">
                        <h4>Brak wniosków</h4>
                        <p>Nie masz jeszcze żadnych wniosków. Możesz złożyć pierwszy wniosek korzystając z przycisku powyżej.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($applications as $wniosek): ?>
                        <div class="wniosek-card">
                            <div class="wniosek-header">
                                <div class="wniosek-meta">
                                    <strong>Data złożenia:</strong> <?php echo $wniosek['formatted_date']; ?> |
                                    <strong>Numer wniosku:</strong> #<?php echo $wniosek['id']; ?> |
                                    <strong>Autor:</strong> <?php echo htmlspecialchars($wniosek['autor']); ?>
                                    <span class="status-badge status-<?php echo $wniosek['status']; ?>">
                                        <?php echo $wniosek['status'] == 'answered' ? 'Odpowiedziano' : 'Oczekuje'; ?>
                                    </span>
                                </div>
                            </div>
                            <div class="wniosek-content">
                                <strong>Treść wniosku:</strong><br>
                                <?php echo nl2br(htmlspecialchars($wniosek['tresc'])); ?>
                            </div>

                            <?php if (!empty($wniosek['informacja_zwrotna'])): ?>
                                <div class="response-section">
                                    <strong>Odpowiedź administracji:</strong><br>
                                    <?php echo nl2br(htmlspecialchars($wniosek['informacja_zwrotna'])); ?>
                                </div>
                            <?php elseif ($is_admin): ?>
                                <div class="text-end">
                                    <button class="response-btn" data-bs-toggle="modal" data-bs-target="#responseModal<?php echo $wniosek['id']; ?>">
                                        Dodaj odpowiedź
                                    </button>
                                </div>
                            <?php else: ?>
                                <div class="text-muted">
                                    <em>Oczekuje na odpowiedź administracji...</em>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Modal odpowiedzi (dla admina) -->
                        <?php if ($is_admin && empty($wniosek['informacja_zwrotna'])): ?>
                            <div class="modal fade" id="responseModal<?php echo $wniosek['id']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Dodaj odpowiedź do wniosku #<?php echo $wniosek['id']; ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="index.php?action=add-response">
                                            <input type="hidden" name="id" value="<?php echo $wniosek['id']; ?>">
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Treść wniosku:</label>
                                                    <div class="form-control" style="background: #f8f9fa; min-height: 100px; overflow-y: auto;">
                                                        <?php echo nl2br(htmlspecialchars($wniosek['tresc'])); ?>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Odpowiedź *</label>
                                                    <textarea class="form-control" name="informacja_zwrotna" required rows="4" placeholder="Wpisz odpowiedź na wniosek..."></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                                                <button type="submit" class="btn btn-primary">Dodaj odpowiedź</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal dodawania wniosku -->
<div class="modal fade" id="addWniosekModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Złóż nowy wniosek</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="index.php?action=create-application">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Twój email:</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($currentUser['email']); ?>" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Treść wniosku *</label>
                        <textarea class="form-control" name="tresc" required rows="6" placeholder="Opisz szczegółowo swój wniosek, sugestię lub problem..."></textarea>
                    </div>

                    <div class="alert alert-info">
                        <small>Twój wniosek zostanie przekazany do administracji. Odpowiedź otrzymasz na podany adres email.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                    <button type="submit" class="btn btn-primary">Złóż wniosek</button>
                </div>
            </form>
        </div>
    </div>
</div>

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