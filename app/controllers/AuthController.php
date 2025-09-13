<?php
// app/controllers/AuthController.php

require_once __DIR__ . '/../models/User.php';

class AuthController {
    protected $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function login() {
        // If user is already logged in, redirect to dashboard
        if (Auth::check()) {
            header('Location: index.php?action=dashboard');
            exit;
        }

        $error = '';
        $email = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email']);
            $password = $_POST['password'];

            if (empty($email) || empty($password)) {
                $error = "Proszę wypełnić wszystkie pola";
            } else {
                try {
                    // Check if user exists
                    $user = $this->userModel->getUserByEmail($email);

                    if ($user && password_verify($password, $user['haslo'])) {
                        // Set session variables
                        $_SESSION['user_id'] = $user['pesel'];
                        $_SESSION['user_email'] = $user['email'];
                        $_SESSION['user_name'] = $user['imie'] . ' ' . $user['nazwisko'];
                        $_SESSION['is_admin'] = $user['admin'];

                        // Regenerate session ID for security
                        session_regenerate_id(true);

                        // Redirect to dashboard
                        header('Location: index.php?action=dashboard');
                        exit;
                    } else {
                        $error = "Nieprawidłowy email lub hasło";
                    }

                } catch (Exception $e) {
                    error_log("Login error: " . $e->getMessage());
                    $error = "Wystąpił błąd podczas logowania. Spróbuj ponownie.";
                }
            }
        }

        // Load the login view
        require_once __DIR__ . '/../../views/auth/login.php';
    }

    public function register() {
        // If user is already logged in, redirect to dashboard
        if (Auth::check()) {
            header('Location: index.php?action=dashboard');
            exit;
        }

        $errors = [];
        $success = '';
        $formData = [
            'pesel' => '', 'imie' => '', 'nazwisko' => '', 'data_urodzenia' => '',
            'plec' => '', 'email' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get form data
            $formData = [
                'pesel' => trim($_POST['pesel']),
                'imie' => trim($_POST['imie']),
                'nazwisko' => trim($_POST['nazwisko']),
                'data_urodzenia' => trim($_POST['data_urodzenia']),
                'plec' => trim($_POST['plec']),
                'email' => trim($_POST['email']),
                'password' => $_POST['password'],
                'confirm_password' => $_POST['confirm_password']
            ];

            // Validation
            if (empty($formData['pesel']) || strlen($formData['pesel']) != 11 || !is_numeric($formData['pesel'])) {
                $errors[] = "PESEL musi mieć 11 cyfr";
            }

            if (empty($formData['imie'])) $errors[] = "Imię jest wymagane";
            if (empty($formData['nazwisko'])) $errors[] = "Nazwisko jest wymagane";

            if (!strtotime($formData['data_urodzenia'])) {
                $errors[] = "Nieprawidłowa data urodzenia";
            }

            if (!in_array(strtoupper($formData['plec']), ['M', 'K'])) {
                $errors[] = "Płeć musi być M lub K";
            }

            if (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Nieprawidłowy adres email";
            }

            if (strlen($formData['password']) < 6) {
                $errors[] = "Hasło musi mieć co najmniej 6 znaków";
            }

            if ($formData['password'] !== $formData['confirm_password']) {
                $errors[] = "Hasła nie są identyczne";
            }

            // Check if email or PESEL already exists
            if (empty($errors)) {
                try {
                    if ($this->userModel->userExists($formData['pesel'], $formData['email'])) {
                        $errors[] = "Użytkownik z tym emailem lub PESEL-em już istnieje";
                    }
                } catch (Exception $e) {
                    $errors[] = "Błąd podczas sprawdzania użytkownika";
                }
            }

            // Register user
            if (empty($errors)) {
                try {
                    $hashed_password = password_hash($formData['password'], PASSWORD_DEFAULT);
                    $saldo = 0.00;

                    $this->userModel->create([
                        'pesel' => $formData['pesel'],
                        'imie' => $formData['imie'],
                        'nazwisko' => $formData['nazwisko'],
                        'data_urodzenia' => $formData['data_urodzenia'],
                        'plec' => strtoupper($formData['plec']),
                        'saldo' => $saldo,
                        'admin' => 'f',
                        'haslo' => $hashed_password,
                        'email' => $formData['email']
                    ]);

                    $success = "Rejestracja przebiegła pomyślnie! Możesz się teraz zalogować.";
                    // Clear form data
                    $formData = [
                        'pesel' => '', 'imie' => '', 'nazwisko' => '', 'data_urodzenia' => '',
                        'plec' => '', 'email' => ''
                    ];

                } catch (Exception $e) {
                    $errors[] = "Błąd podczas rejestracji: " . $e->getMessage();
                }
            }
        }

        // Load the register view
        require_once __DIR__ . '/../../views/auth/register.php';
    }

    public function logout() {
        // Ensure session is started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Store some data for feedback if needed
        $userEmail = $_SESSION['user_email'] ?? '';

        // Unset all session variables
        $_SESSION = array();

        // Delete session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        // Destroy the session
        session_destroy();

        // Optional: Log the logout action
        error_log("User logged out: " . $userEmail);

        // Clear output buffer and redirect
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        header("Location: index.php?action=login");
        exit;
    }
}
?>