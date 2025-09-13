<?php
// app/controllers/AdminController.php

require_once __DIR__ . '/../models/User.php';

class AdminController {
    protected $userModel;

    public function __construct() {
        // Check if user is logged in and is admin
        Auth::redirectIfNotLoggedIn('index.php?action=login');

        if (!Auth::isAdmin()) {
            header('Location: index.php?action=home');
            exit;
        }

        $this->userModel = new User();
    }

    public function users() {
        // Get all users from the model
        $users = $this->userModel->getAllUsers();

        // Load the view
        require_once __DIR__ . '/../../views/admin/users.php';
    }

    public function deleteUser() {
        // Only allow GET requests with id parameter
        if ($_SERVER['REQUEST_METHOD'] !== 'GET' || !isset($_GET['id'])) {
            header('Location: index.php?action=admin-users');
            exit;
        }

        $pesel = trim($_GET['id']);

        // Basic validation
        if (empty($pesel) || strlen($pesel) !== 11 || !is_numeric($pesel)) {
            $_SESSION['error_message'] = "Nieprawidłowy format PESEL.";
            header('Location: index.php?action=admin-users');
            exit;
        }

        try {
            // Perform the deletion
            $success = $this->userModel->deleteUser($pesel);

            if ($success) {
                $_SESSION['success_message'] = "Użytkownik został pomyślnie usunięty.";
            } else {
                $_SESSION['error_message'] = "Nie udało się usunąć użytkownika.";
            }

        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
        }

        // Add this at the beginning of the deleteUser method:
        if (!isset($_GET['csrf']) || !Security::validateCsrfToken($_GET['csrf'])) {
            $_SESSION['error_message'] = "Błąd zabezpieczenia CSRF. Operacja została zablokowana.";
            header('Location: index.php?action=admin-users');
            exit;
        }

        // Redirect back to admin panel
        header('Location: index.php?action=admin-users');
        exit;
    }

    // Add these methods to the existing AdminController class

    public function editUser() {
        // Check if PESEL is provided
        if (!isset($_GET['id'])) {
            $_SESSION['error_message'] = "Nie podano identyfikatora użytkownika.";
            header('Location: index.php?action=admin-users');
            exit;
        }

        $pesel = trim($_GET['id']);

        // Validate PESEL format
        if (empty($pesel) || strlen($pesel) !== 11 || !is_numeric($pesel)) {
            $_SESSION['error_message'] = "Nieprawidłowy format PESEL.";
            header('Location: index.php?action=admin-users');
            exit;
        }

        // Get user data
        $user = $this->userModel->getUserByPesel($pesel);
        if (!$user) {
            $_SESSION['error_message'] = "Użytkownik nie istnieje.";
            header('Location: index.php?action=admin-users');
            exit;
        }

        $errorMessage = '';
        $successMessage = '';

        // Form data with user's current values
        $formData = [
            'pesel' => $user['pesel'],
            'imie' => $user['imie'],
            'nazwisko' => $user['nazwisko'],
            'data_urodzenia' => $user['data_urodzenia'],
            'plec' => $user['plec'],
            'saldo' => $user['saldo'],
            'admin' => $user['admin'],
            'haslo' => '', // Password is not pre-filled for security
            'email' => $user['email'] ?? ''
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get form data
            $formData = [
                'pesel' => $pesel, // Keep original PESEL
                'imie' => trim($_POST['imie']),
                'nazwisko' => trim($_POST['nazwisko']),
                'data_urodzenia' => trim($_POST['data_urodzenia']),
                'plec' => trim($_POST['plec']),
                'saldo' => floatval(str_replace(',', '.', $_POST['saldo'])),
                'admin' => isset($_POST['admin']) ? 't' : 'f',
                'haslo' => trim($_POST['haslo']),
                'email' => trim($_POST['email'])
            ];

            // Validate required fields
            $requiredFields = ['imie', 'nazwisko', 'data_urodzenia', 'plec', 'saldo', 'email'];
            foreach ($requiredFields as $field) {
                if (empty($formData[$field])) {
                    $errorMessage = "Wszystkie pola są wymagane.";
                    break;
                }
            }

            // Validate email format
            if (empty($errorMessage) && !filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
                $errorMessage = "Nieprawidłowy format adresu email.";
            }

            // Check if email is already used by another user
            if (empty($errorMessage) && $this->userModel->emailExistsForOtherUser($formData['email'], $pesel)) {
                $errorMessage = "Email jest już używany przez innego użytkownika.";
            }

            if (empty($errorMessage)) {
                try {
                    if (!empty($formData['haslo'])) {
                        // Update with new password
                        $formData['haslo'] = password_hash($formData['haslo'], PASSWORD_DEFAULT);
                        $this->userModel->updateWithPassword($formData);
                    } else {
                        // Update without changing password
                        $this->userModel->update($formData);
                    }

                    $_SESSION['success_message'] = "Dane użytkownika zostały zaktualizowane pomyślnie.";
                    header('Location: index.php?action=admin-users');
                    exit;

                } catch (Exception $e) {
                    $errorMessage = "Błąd podczas aktualizacji użytkownika: " . $e->getMessage();
                }
            }
        }

        // Load the view with form data and messages
        require_once __DIR__ . '/../../views/admin/edit_user.php';
    }

    public function createUser() {
        $errorMessage = '';
        $successMessage = '';

        // Form data with default values
        $formData = [
            'pesel' => '', 'imie' => '', 'nazwisko' => '', 'data_urodzenia' => '',
            'plec' => '', 'saldo' => '', 'admin' => 'f', 'haslo' => '', 'email' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get form data
            $formData = [
                'pesel' => trim($_POST['pesel']),
                'imie' => trim($_POST['imie']),
                'nazwisko' => trim($_POST['nazwisko']),
                'data_urodzenia' => trim($_POST['data_urodzenia']),
                'plec' => trim($_POST['plec']),
                'saldo' => floatval(str_replace(',', '.', $_POST['saldo'])),
                'admin' => isset($_POST['admin']) ? 't' : 'f',
                'haslo' => $_POST['haslo'],
                'email' => trim($_POST['email'])
            ];

            // Validate required fields
            $requiredFields = ['pesel', 'imie', 'nazwisko', 'data_urodzenia', 'plec', 'saldo', 'haslo', 'email'];
            foreach ($requiredFields as $field) {
                if (empty($formData[$field])) {
                    $errorMessage = "Wszystkie pola są wymagane.";
                    break;
                }
            }

            // Validate PESEL format
            if (empty($errorMessage) && (strlen($formData['pesel']) != 11 || !is_numeric($formData['pesel']))) {
                $errorMessage = "PESEL musi mieć 11 cyfr.";
            }

            // Validate email format
            if (empty($errorMessage) && !filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
                $errorMessage = "Nieprawidłowy format adresu email.";
            }

            // Check if user already exists
            if (empty($errorMessage) && $this->userModel->userExists($formData['pesel'], $formData['email'])) {
                $errorMessage = "Użytkownik z tym PESEL-em lub emailem już istnieje.";
            }

            if (empty($errorMessage)) {
                try {
                    // Hash the password
                    $formData['haslo'] = password_hash($formData['haslo'], PASSWORD_DEFAULT);

                    // Create the user
                    $this->userModel->create($formData);

                    $_SESSION['success_message'] = "Nowy klient został dodany pomyślnie.";
                    header('Location: index.php?action=admin-users');
                    exit;

                } catch (Exception $e) {
                    $errorMessage = "Błąd podczas dodawania użytkownika: " . $e->getMessage();
                }
            }
        }

        // Load the view with form data and messages
        require_once __DIR__ . '/../../views/admin/create_user.php';
    }
}
?>