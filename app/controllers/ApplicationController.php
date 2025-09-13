<?php
// app/controllers/ApplicationController.php

require_once __DIR__ . '/../models/Application.php';

class ApplicationController {
    protected $applicationModel;
    protected $userPesel;
    protected $userEmail;

    public function __construct() {
        Auth::redirectIfNotLoggedIn('index.php?action=login');
        $this->applicationModel = new Application();

        $user = Auth::user();
        $this->userPesel = $user['id'];
        $this->userEmail = $user['email'];
    }

    public function index() {
        $is_admin = Auth::isAdmin();

        // Try to migrate from file if table is empty
        if ($is_admin) {
            $applications = $this->applicationModel->getAll();
        } else {
            $applications = $this->applicationModel->getUserApplications($this->userEmail);
        }

        if (empty($applications)) {
            $this->applicationModel->migrateFromFile();
            if ($is_admin) {
                $applications = $this->applicationModel->getAll();
            } else {
                $applications = $this->applicationModel->getUserApplications($this->userEmail);
            }
        }

        $success = isset($_GET['success']);
        $success_response = isset($_GET['success_response']);

        require_once __DIR__ . '/../../views/applications/index.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=applications');
            exit;
        }

        $tresc = trim($_POST['tresc']);

        if (empty($tresc)) {
            $_SESSION['error_message'] = "Treść wniosku jest wymagana.";
            header('Location: index.php?action=applications');
            exit;
        }

        try {
            $this->applicationModel->create([
                'data' => date('Y-m-d'),
                'tresc' => $tresc,
                'autor' => $this->userEmail,
                'informacja_zwrotna' => '',
                'status' => 'pending'
            ]);

            $_SESSION['success_message'] = "Wniosek został dodany pomyślnie!";
            header('Location: index.php?action=applications&success=1');
            exit;

        } catch (Exception $e) {
            $_SESSION['error_message'] = "Błąd podczas dodawania wniosku: " . $e->getMessage();
            header('Location: index.php?action=applications');
            exit;
        }
    }

    public function addResponse() {
        if (!Auth::isAdmin()) {
            header('Location: index.php?action=applications');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=applications');
            exit;
        }

        $id = $_POST['id'] ?? '';
        $response = trim($_POST['informacja_zwrotna'] ?? '');

        if (empty($id) || empty($response)) {
            $_SESSION['error_message'] = "Wypełnij wszystkie wymagane pola.";
            header('Location: index.php?action=applications');
            exit;
        }

        try {
            $this->applicationModel->updateResponse($id, $response);

            $_SESSION['success_message'] = "Odpowiedź została dodana pomyślnie!";
            header('Location: index.php?action=applications&success_response=1');
            exit;

        } catch (Exception $e) {
            $_SESSION['error_message'] = "Błąd podczas dodawania odpowiedzi: " . $e->getMessage();
            header('Location: index.php?action=applications');
            exit;
        }
    }
}
?>

