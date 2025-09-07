<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$pesel = $_SESSION['user_id'];

// Pobierz email użytkownika
$user_email = '';
try {
    $stmt = $pdo->prepare("SELECT email FROM account_doc WHERE pesel = ?");
    $stmt->execute(array($pesel));
    $user = $stmt->fetch();
    $user_email = $user['email'] ?? '';
} catch (PDOException $e) {
    error_log("Błąd: " . $e->getMessage());
}

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

// Funkcja do odczytu wniosków z pliku tekstowego
function readWnioskiFromFile($filename = 'wnioski.txt') {
    $wnioski = array();

    if (!file_exists($filename)) {
        file_put_contents($filename, '');
    }

    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    if ($lines) {
        for ($i = 0; $i < count($lines); $i += 5) {
            if (isset($lines[$i]) && isset($lines[$i+1]) && isset($lines[$i+2]) && isset($lines[$i+3]) && isset($lines[$i+4])) {
                $wnioski[] = array(
                    'id_wpisu' => trim($lines[$i]),
                    'data' => trim($lines[$i+1]),
                    'tresc' => trim($lines[$i+2]),
                    'autor' => trim($lines[$i+3]),
                    'informacja_zwrotna' => trim($lines[$i+4])
                );
            }
        }
    }

    // Sortowanie od najnowszych do najstarszych
    usort($wnioski, function($a, $b) {
        return strtotime($b['data']) - strtotime($a['data']);
    });

    return $wnioski;
}

$wnioski = readWnioskiFromFile();

// Obsługa dodawania nowych wniosków
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_wniosek') {
    $tresc = $_POST['tresc'];
    $data = date('Y-m-d');
    $id_wpisu = rand(100000, 999999); // Losowe ID

    try {
        // Zapisz do bazy danych
        $stmt = $pdo->prepare("INSERT INTO wnioski (id_wpisu, data, tresc, autor, informacja_zwrotna) 
                              VALUES (?, ?, ?, ?, ?)");
        $stmt->execute(array($id_wpisu, $data, $tresc, $user_email, ''));

        // Zapisz do pliku tekstowego
        $new_wniosek = array(
            'id_wpisu' => $id_wpisu,
            'data' => $data,
            'tresc' => $tresc,
            'autor' => $user_email,
            'informacja_zwrotna' => ''
        );

        $wnioski[] = $new_wniosek;

        $lines = array();
        foreach ($wnioski as $wniosek) {
            $lines[] = $wniosek['id_wpisu'];
            $lines[] = $wniosek['data'];
            $lines[] = $wniosek['tresc'];
            $lines[] = $wniosek['autor'];
            $lines[] = $wniosek['informacja_zwrotna'];
        }

        file_put_contents('wnioski.txt', implode("\n", $lines));
        header("Location: wnioski.php?success=1");
        exit;

    } catch (PDOException $e) {
        $error_message = "Błąd podczas dodawania wniosku: " . $e->getMessage();
    }
}

// Obsługa odpowiedzi na wniosek (tylko dla admina)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $is_admin && isset($_POST['action']) && $_POST['action'] === 'add_response') {
    $id_wpisu = $_POST['id_wpisu'];
    $response = $_POST['informacja_zwrotna'];

    try {
        // Aktualizuj w bazie danych
        $stmt = $pdo->prepare("UPDATE wnioski SET informacja_zwrotna = ? WHERE id_wpisu = ?");
        $stmt->execute(array($response, $id_wpisu));

        // Aktualizuj w pliku tekstowym
        $wnioski = readWnioskiFromFile();
        foreach ($wnioski as &$wniosek) {
            if ($wniosek['id_wpisu'] == $id_wpisu) {
                $wniosek['informacja_zwrotna'] = $response;
            }
        }

        $lines = array();
        foreach ($wnioski as $wniosek) {
            $lines[] = $wniosek['id_wpisu'];
            $lines[] = $wniosek['data'];
            $lines[] = $wniosek['tresc'];
            $lines[] = $wniosek['autor'];
            $lines[] = $wniosek['informacja_zwrotna'];
        }

        file_put_contents('wnioski.txt', implode("\n", $lines));
        header("Location: wnioski.php?success_response=1");
        exit;

    } catch (PDOException $e) {
        $error_message = "Błąd podczas dodawania odpowiedzi: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wnioski - mBochnia</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="theme.css">
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
    </style>
</head>
<body>
<div class="main-container">
    <nav class="navbar">
        <div class="container-fluid">
            <span class="navbar-text me-auto"><strong>mBochnia</strong> - Wnioski</span>
            <div class="d-flex">
                <a class="btn btn-outline-primary me-2" href="documents.php">Dokumenty</a>
                <a class="btn btn-outline-primary me-2" href="announcements.php">Ogłoszenia</a>
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
                <button class="menu-btn active">Wnioski</button>
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

                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                <?php endif; ?>

                <div class="text-end mb-3">
                    <button class="add-wniosek-btn" data-bs-toggle="modal" data-bs-target="#addWniosekModal">
                        + Złóż nowy wniosek
                    </button>
                </div>

                <?php if (empty($wnioski)): ?>
                    <div class="alert alert-info">
                        <h4>Brak wniosków</h4>
                        <p>Nie masz jeszcze żadnych wniosków. Możesz złożyć pierwszy wniosek korzystając z przycisku powyżej.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($wnioski as $wniosek): ?>
                        <div class="wniosek-card">
                            <div class="wniosek-header">
                                <div class="wniosek-meta">
                                    <strong>Data złożenia:</strong> <?php echo date('d.m.Y', strtotime($wniosek['data'])); ?> |
                                    <strong>Numer wniosku:</strong> <?php echo htmlspecialchars($wniosek['id_wpisu']); ?> |
                                    <strong>Autor:</strong> <?php echo htmlspecialchars($wniosek['autor']); ?>
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
                                    <button class="response-btn" data-bs-toggle="modal" data-bs-target="#responseModal<?php echo $wniosek['id_wpisu']; ?>">
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
                            <div class="modal fade" id="responseModal<?php echo $wniosek['id_wpisu']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Dodaj odpowiedź do wniosku #<?php echo $wniosek['id_wpisu']; ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST">
                                            <input type="hidden" name="action" value="add_response">
                                            <input type="hidden" name="id_wpisu" value="<?php echo $wniosek['id_wpisu']; ?>">
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Treść wniosku:</label>
                                                    <div class="form-control" style="background: #f8f9fa; min-height: 100px;">
                                                        <?php echo nl2br(htmlspecialchars($wniosek['tresc'])); ?>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Odpowiedź *</label>
                                                    <textarea class="form-control" name="informacja_zwrotna" required rows="4"
                                                              placeholder="Wpisz odpowiedź na wniosek..."></textarea>
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
            <form method="POST">
                <input type="hidden" name="action" value="add_wniosek">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Twój email:</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($user_email); ?>" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Treść wniosku *</label>
                        <textarea class="form-control" name="tresc" required rows="6"
                                  placeholder="Opisz szczegółowo swój wniosek, sugestię lub problem..."></textarea>
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