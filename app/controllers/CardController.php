<?php
// app/controllers/CardController.php

require_once __DIR__ . '/../models/Card.php';

class CardController {
    protected $cardModel;
    protected $userPesel;

    public function __construct() {
        Auth::redirectIfNotLoggedIn('index.php?action=login');
        $this->cardModel = new Card();
        $this->userPesel = Auth::user()['id'];
    }

    public function index() {
        $mka_data = $this->cardModel->getUserMkaCard($this->userPesel);
        $rpk_data = $this->cardModel->getUserRpkCard($this->userPesel);
        $res_data = $this->cardModel->getUserResCard($this->userPesel);

        $has_mka = $mka_data !== false;
        $has_rpk = $rpk_data !== false;
        $has_res = $res_data !== false;

        $card_count = ($has_mka ? 1 : 0) + ($has_rpk ? 1 : 0) + ($has_res ? 1 : 0);
        $is_admin = Auth::isAdmin();
        $success = isset($_GET['success']);

        require_once __DIR__ . '/../../views/cards/index.php';
    }

    public function handleCardOperation() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=documents');
            exit;
        }

        $card_type = $_POST['card_type'] ?? '';
        $action = $_POST['action'] ?? 'add';

        try {
            if ($action === 'add') {
                $this->addCard($card_type);
            } elseif ($action === 'edit') {
                $this->editCard($card_type);
            }

            $_SESSION['success_message'] = "Operacja wykonana pomyślnie!";
            header('Location: index.php?action=documents&success=1');
            exit;

        } catch (Exception $e) {
            $_SESSION['error_message'] = "Błąd podczas operacji: " . $e->getMessage();
            header('Location: index.php?action=documents');
            exit;
        }
    }

    private function addCard($card_type) {
        $id_karty = $this->cardModel->generateCardId();

        switch ($card_type) {
            case 'mka':
                $this->cardModel->createMkaCard([
                    'id_karty' => $id_karty,
                    'pesel' => $this->userPesel,
                    'data_waznosci' => date('Y-m-d', strtotime('+1 year')),
                    'typ_karty' => $_POST['typ_karty'],
                    'strefa' => $_POST['strefa']
                ]);
                break;

            case 'rpk':
                $this->cardModel->createRpkCard([
                    'id_karty' => $id_karty,
                    'pesel' => $this->userPesel,
                    'data_waznosci' => date('Y-m-d', strtotime('+1 year')),
                    'typ_karty' => $_POST['typ_karty']
                ]);
                break;

            case 'res':
                $this->cardModel->createResCard([
                    'id_karty' => $id_karty,
                    'pesel' => $this->userPesel,
                    'data_zam' => $_POST['data_zam'],
                    'osiedle' => $_POST['osiedle'],
                    'ulica' => $_POST['ulica'],
                    'nr_domu' => $_POST['nr_domu'],
                    'nr_mieszkania' => $_POST['nr_mieszkania'] ?? null
                ]);
                break;

            default:
                throw new Exception("Nieznany typ karty");
        }
    }

    private function editCard($card_type) {
        $id_karty = $_POST['id_karty'];

        switch ($card_type) {
            case 'mka':
                $this->cardModel->updateMkaCard([
                    'id_karty' => $id_karty,
                    'pesel' => $this->userPesel,
                    'typ_karty' => $_POST['typ_karty'],
                    'strefa' => $_POST['strefa']
                ]);
                break;

            case 'rpk':
                $this->cardModel->updateRpkCard([
                    'id_karty' => $id_karty,
                    'pesel' => $this->userPesel,
                    'typ_karty' => $_POST['typ_karty']
                ]);
                break;

            case 'res':
                $this->cardModel->updateResCard([
                    'id_karty' => $id_karty,
                    'pesel' => $this->userPesel,
                    'data_zam' => $_POST['data_zam'],
                    'osiedle' => $_POST['osiedle'],
                    'ulica' => $_POST['ulica'],
                    'nr_domu' => $_POST['nr_domu'],
                    'nr_mieszkania' => $_POST['nr_mieszkania'] ?? null
                ]);
                break;

            default:
                throw new Exception("Nieznany typ karty");
        }
    }

    public function generateQRCode($data) {
        return "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($data);
    }
}
?>