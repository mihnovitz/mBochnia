<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$pesel = $_SESSION['user_id'];

// Sprawdzenie czy użytkownik jest administratorem
$is_admin = false;
try {
    $stmt = $pdo->prepare("SELECT admin FROM account_doc WHERE pesel = ?");
    $stmt->execute(array($pesel));
    $user = $stmt->fetch();
    $is_admin = ($user && isset($user['admin']) && $user['admin'] == 't');
} catch (PDOException $e) {
    error_log("Błąd: " . $e->getMessage());
}

// Funkcja do odczytu ogłoszeń z pliku tekstowego
function readAnnouncementsFromFile($filename = 'announcements.txt') {
    $announcements = array();

    if (!file_exists($filename)) {
        // Tworzymy przykładowe ogłoszenia jeśli plik nie istnieje
        $example_data = "1\n2024-01-15\nRemont ulicy\nAdministrator\nPlanowany remont ulicy Głównej w dniach 20-25.01.2024. Prosimy o korzystanie z objazdów.\n\n6\n2024-01-16\nPodwyżka opłat\nUrząd Miasta\nInformujemy o podwyżce opłat za wywóz śmieci od 01.02.2024.\n\n11\n2024-01-17\nPrzerwa w dostawie wody\nMPWiK\nW dniu 22.01.2024 w godzinach 8:00-14:00 przerwa w dostawie wody na osiedlu Centrum.";
        file_put_contents($filename, $example_data);
    }

    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    if ($lines) {
        for ($i = 0; $i < count($lines); $i += 5) {
            if (isset($lines[$i]) && isset($lines[$i+1]) && isset($lines[$i+2]) && isset($lines[$i+3]) && isset($lines[$i+4])) {
                $announcements[] = array(
                    'id_wpisu' => trim($lines[$i]),
                    'data' => trim($lines[$i+1]),
                    'watek' => trim($lines[$i+2]),
                    'autor' => trim($lines[$i+3]),
                    'tresc' => trim($lines[$i+4])
                );
            }
        }
    }

    // Sortowanie od najnowszych do najstarszych
    usort($announcements, function($a, $b) {
        return strtotime($b['data']) - strtotime($a['data']);
    });

    return $announcements;
}

$announcements = readAnnouncementsFromFile();

// Obsługa dodawania nowych ogłoszeń (tylko dla admina)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $is_admin && isset($_POST['action']) && $_POST['action'] === 'add_announcement') {
    $new_id = count($announcements) * 5 + 1;
    $new_data = array(
        'id_wpisu' => $new_id,
        'data' => $_POST['data'],
        'watek' => $_POST['watek'],
        'autor' => $_POST['autor'],
        'tresc' => $_POST['tresc']
    );

    // Dodanie do tablicy
    $announcements[] = $new_data;

    // Zapis do pliku
    $lines = array();
    foreach ($announcements as $ann) {
        $lines[] = $ann['id_wpisu'];
        $lines[] = $ann['data'];
        $lines[] = $ann['watek'];
        $lines[] = $ann['autor'];
        $lines[] = $ann['tresc'];
    }

    file_put_contents('announcements.txt', implode("\n", $lines));
    header("Location: announcements.php?success=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ogłoszenia - mBochnia</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="theme.css">
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
                <a class="btn btn-outline-primary me-2" href="documents.php">Dokumenty</a>
                <a class="btn btn-outline-primary me-2" href="index.php">Strona Główna</a>
                <a class="btn btn-primary" href="logout.php">Wyloguj</a>
            </div>
        </div>
    </nav>

    <div class="logo-text">mBochnia</div>

    <div class="content-wrapper">
        <div class="sidebar">
            <div class="sidebar-title">Menu</div>
            <div class="menu-buttons">
                <button class="menu-btn" onclick="location.href='documents.php'">Dokumenty</button>
                <button class="menu-btn" onclick="location.href='announcements.php'">Ogłoszenia</button>
                <button class="menu-btn" onclick="location.href='wnioski.php'">Wnioski</button>
                <?php if ($is_admin): ?>
                    <button class="menu-btn" onclick="location.href='admin.php'" style="background-color: #ff5151;">Panel Admina</button>
                <?php endif; ?>
            </div>
            <div class="logout-container">
                <a href="logout.php" class="logout-btn">Wyloguj się</a>
            </div>
        </div>

        <div class="main-content">
            <div class="content-header">
                <h1 class="content-title">Ogłoszenia</h1>
                <p class="content-description">Aktualne informacje i komunikaty dla mieszkańców</p>
            </div>

            <div class="content-body">
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">Ogłoszenie zostało dodane pomyślnie!</div>
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
                                    <strong>Data:</strong> <?php echo date('d.m.Y', strtotime($announcement['data'])); ?> |
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
                <form method="POST">
                    <input type="hidden" name="action" value="add_announcement">
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