<?php
session_start();
// Poprawne include config.php
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$pesel = $_SESSION['user_id'];
$is_admin = false;
$mka_data = null;
$rpk_data = null;
$res_data = null;
$has_mka = false;
$has_rpk = false;
$has_res = false;

// Sprawdzenie admina i kart
try {
    $stmt = $pdo->prepare("SELECT admin FROM account_doc WHERE pesel = ?");
    $stmt->execute(array($pesel));
    $user = $stmt->fetch();
    $is_admin = ($user && isset($user['admin']) && $user['admin'] == 't');

    // Sprawdzenie MKA
    $stmt = $pdo->prepare("SELECT * FROM mka_card_doc WHERE pesel = ? AND status_karty = true");
    $stmt->execute(array($pesel));
    $mka_data = $stmt->fetch();
    $has_mka = $mka_data !== false;

    // Sprawdzenie RPK
    $stmt = $pdo->prepare("SELECT * FROM rpk_card_doc WHERE pesel = ? AND status_karty = true");
    $stmt->execute(array($pesel));
    $rpk_data = $stmt->fetch();
    $has_rpk = $rpk_data !== false;

    // Sprawdzenie RES
    $stmt = $pdo->prepare("SELECT * FROM res_card_doc WHERE pesel = ?");
    $stmt->execute(array($pesel));
    $res_data = $stmt->fetch();
    $has_res = $res_data !== false;

} catch (PDOException $e) {
    error_log("Błąd: " . $e->getMessage());
}

$card_count = ($has_mka ? 1 : 0) + ($has_rpk ? 1 : 0) + ($has_res ? 1 : 0);

function generateCardId() {
    return rand(100000000, 999999999);
}

function generateQRCode($data) {
    return "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($data);
}

// Obsługa formularzy
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $card_type = isset($_POST['card_type']) ? $_POST['card_type'] : '';
    $action = isset($_POST['action']) ? $_POST['action'] : 'add';

    try {
        if ($action === 'add') {
            $id_karty = generateCardId();

            switch ($card_type) {
                case 'mka':
                    $stmt = $pdo->prepare("INSERT INTO mka_card_doc (id_karty, pesel, data_waznosci, typ_karty, status_karty, strefa) 
                                          VALUES (?, ?, ?, ?, true, ?)");
                    $stmt->execute(array($id_karty, $pesel, date('Y-m-d', strtotime('+1 year')), $_POST['typ_karty'], $_POST['strefa']));
                    break;

                case 'rpk':
                    $stmt = $pdo->prepare("INSERT INTO rpk_card_doc (id_karty, pesel, data_waznosci, typ_karty, status_karty) 
                                          VALUES (?, ?, ?, ?, true)");
                    $stmt->execute(array($id_karty, $pesel, date('Y-m-d', strtotime('+1 year')), $_POST['typ_karty']));
                    break;

                case 'res':
                    $stmt = $pdo->prepare("INSERT INTO res_card_doc (pesel, id_karty, data_zam, osiedle, ulica, nr_domu, nr_mieszkania) 
                                          VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute(array($pesel, $id_karty, $_POST['data_zam'], $_POST['osiedle'], $_POST['ulica'], $_POST['nr_domu'], $_POST['nr_mieszkania']));
                    break;
            }
        } elseif ($action === 'edit') {
            $id_karty = $_POST['id_karty'];

            switch ($card_type) {
                case 'mka':
                    $stmt = $pdo->prepare("UPDATE mka_card_doc SET typ_karty = ?, strefa = ? WHERE id_karty = ? AND pesel = ?");
                    $stmt->execute(array($_POST['typ_karty'], $_POST['strefa'], $id_karty, $pesel));
                    break;

                case 'rpk':
                    $stmt = $pdo->prepare("UPDATE rpk_card_doc SET typ_karty = ? WHERE id_karty = ? AND pesel = ?");
                    $stmt->execute(array($_POST['typ_karty'], $id_karty, $pesel));
                    break;

                case 'res':
                    $stmt = $pdo->prepare("UPDATE res_card_doc SET data_zam = ?, osiedle = ?, ulica = ?, nr_domu = ?, nr_mieszkania = ? WHERE id_karty = ? AND pesel = ?");
                    $stmt->execute(array($_POST['data_zam'], $_POST['osiedle'], $_POST['ulica'], $_POST['nr_domu'], $_POST['nr_mieszkania'], $id_karty, $pesel));
                    break;
            }
        }

        header("Location: documents.php?success=1");
        exit;

    } catch (PDOException $e) {
        $error_message = "Błąd podczas operacji na karcie.";
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dokumenty - mBochnia</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="theme.css">
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
                <a class="btn btn-outline-primary me-2" href="announcements.php">Ogłoszenia</a>
                <a class="btn btn-outline-primary me-2" href="wnioski.php">Wnioski</a>
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
                <h1 class="content-title">Dokumenty</h1>
                <p class="content-description">Zarządzaj swoimi kartami i dokumentami</p>
            </div>

            <div class="content-body">
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">Operacja wykonana pomyślnie!</div>
                <?php endif; ?>
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
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
                                <p><strong>Ważna do:</strong> <?php echo date('d.m.Y', strtotime($mka_data['data_waznosci'])); ?></p>
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
                                <p><strong>Ważna do:</strong> <?php echo date('d.m.Y', strtotime($rpk_data['data_waznosci'])); ?></p>
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
                                <p><strong>Data zameldowania:</strong> <?php echo date('d.m.Y', strtotime($res_data['data_zam'])); ?></p>
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

<!-- Modal MKA -->
<div class="modal fade" id="mkaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Dodaj kartę MKA</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form method="POST"><input type="hidden" name="card_type" value="mka">
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Typ karty *</label><select class="form-select" name="typ_karty" required><option value="">Wybierz typ karty</option><option value="normalny">Normalny</option><option value="ulgowy">Ulgowy</option></select></div>
                    <div class="mb-3"><label class="form-label">Strefa *</label><select class="form-select" name="strefa" required><option value="">Wybierz strefę</option><option value="1">Strefa 1</option><option value="2">Strefa 2</option><option value="3">Strefa 3</option></select></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button><button type="submit" class="btn btn-primary">Dodaj kartę</button></div>
            </form>
        </div>
    </div>
</div>

<!-- Modal RPK -->
<div class="modal fade" id="rpkModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Dodaj kartę RPK</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form method="POST"><input type="hidden" name="card_type" value="rpk">
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Typ karty *</label><select class="form-select" name="typ_karty" required><option value="">Wybierz typ karty</option><option value="normalny">Normalny</option><option value="ulgowy">Ulgowy</option></select></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button><button type="submit" class="btn btn-primary">Dodaj kartę</button></div>
            </form>
        </div>
    </div>
</div>

<!-- Modal RES -->
<div class="modal fade" id="resModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Dodaj Kartę Mieszkańca</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form method="POST"><input type="hidden" name="card_type" value="res">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6"><div class="mb-3"><label class="form-label">Data zameldowania *</label><input type="date" class="form-control" name="data_zam" required value="<?php echo date('Y-m-d'); ?>"></div></div>
                        <div class="col-md-6"><div class="mb-3"><label class="form-label">Osiedle *</label><select class="form-select" name="osiedle" required><option value="">Wybierz osiedle</option><option value="Śródmieście">Śródmieście</option><option value="Krzeczów">Krzeczów</option><option value="Niepodległości">Niepodległości</option><option value="Planty">Planty</option></select></div></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6"><div class="mb-3"><label class="form-label">Ulica *</label><input type="text" class="form-control" name="ulica" required placeholder="np. Kazimierza Wielkiego"></div></div>
                        <div class="col-md-3"><div class="mb-3"><label class="form-label">Nr domu *</label><input type="text" class="form-control" name="nr_domu" required placeholder="np. 15"></div></div>
                        <div class="col-md-3"><div class="mb-3"><label class="form-label">Nr mieszkania</label><input type="text" class="form-control" name="nr_mieszkania" placeholder="np. 5"></div></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button><button type="submit" class="btn btn-primary">Dodaj kartę</button></div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal MKA -->
<div class="modal fade" id="editMkaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Edytuj kartę MKA</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form method="POST">
                <input type="hidden" name="card_type" value="mka">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id_karty" value="<?php echo $mka_data['id_karty']; ?>">
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Typ karty *</label>
                        <select class="form-select" name="typ_karty" required>
                            <option value="normalny" <?php echo $mka_data['typ_karty'] == 'normalny' ? 'selected' : ''; ?>>Normalny</option>
                            <option value="ulgowy" <?php echo $mka_data['typ_karty'] == 'ulgowy' ? 'selected' : ''; ?>>Ulgowy</option>
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label">Strefa *</label>
                        <select class="form-select" name="strefa" required>
                            <option value="1" <?php echo $mka_data['strefa'] == 1 ? 'selected' : ''; ?>>Strefa 1</option>
                            <option value="2" <?php echo $mka_data['strefa'] == 2 ? 'selected' : ''; ?>>Strefa 2</option>
                            <option value="3" <?php echo $mka_data['strefa'] == 3 ? 'selected' : ''; ?>>Strefa 3</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button><button type="submit" class="btn btn-primary">Zapisz zmiany</button></div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal RPK -->
<div class="modal fade" id="editRpkModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Edytuj kartę RPK</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form method="POST">
                <input type="hidden" name="card_type" value="rpk">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id_karty" value="<?php echo $rpk_data['id_karty']; ?>">
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Typ karty *</label>
                        <select class="form-select" name="typ_karty" required>
                            <option value="normalny" <?php echo $rpk_data['typ_karty'] == 'normalny' ? 'selected' : ''; ?>>Normalny</option>
                            <option value="ulgowy" <?php echo $rpk_data['typ_karty'] == 'ulgowy' ? 'selected' : ''; ?>>Ulgowy</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button><button type="submit" class="btn btn-primary">Zapisz zmiany</button></div>
            </form>
        </div>
    </div>
</div>

<!-- QR Modal MKA -->
<div class="modal fade" id="qrMkaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Kontrola biletu - MKA</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body text-center">
                <img src="<?php echo generateQRCode('MKA:' . $mka_data['id_karty'] . ':' . $pesel); ?>" alt="QR Code" class="qr-code mb-3">
                <p><strong>Numer karty:</strong> <span class="card-number"><?php echo $mka_data['id_karty']; ?></span></p>
                <p><strong>Ważna do:</strong> <?php echo date('d.m.Y', strtotime($mka_data['data_waznosci'])); ?></p>
            </div>
        </div>
    </div>
</div>

<!-- QR Modal RPK -->
<div class="modal fade" id="qrRpkModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Kontrola biletu - RPK</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body text-center">
                <img src="<?php echo generateQRCode('RPK:' . $rpk_data['id_karty'] . ':' . $pesel); ?>" alt="QR Code" class="qr-code mb-3">
                <p><strong>Numer karty:</strong> <span class="card-number"><?php echo $rpk_data['id_karty']; ?></span></p>
                <p><strong>Ważna do:</strong> <?php echo date('d.m.Y', strtotime($rpk_data['data_waznosci'])); ?></p>
            </div>
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