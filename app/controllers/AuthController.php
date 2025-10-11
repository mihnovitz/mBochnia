<?php

class AuthController
{
    public function registerForm()
    {
        // Render the registration page
        require APP_PATH . '/views/register.php';
    }
    
    public function loginForm()
    {
        require APP_PATH . '/views/login.php';
    }

    public function register()
    {
        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Method Not Allowed";
            return;
        }

        // Collect input safely
        $firstName = trim($_POST['first_name'] ?? '');
        $lastName  = trim($_POST['last_name'] ?? '');
        $address   = trim($_POST['address'] ?? '');
        $phone     = trim($_POST['phone'] ?? '');
        $email     = trim($_POST['email'] ?? '');
        $password  = $_POST['password'] ?? '';

        $errors = [];

        // Basic validation
        if ($firstName === '' || $lastName === '' || $address === '' || $phone === '' || $email === '' || $password === '') {
            $errors[] = "All fields are required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email address.";
        }

        // If validation fails, re-render view with errors
        if (!empty($errors)) {
            require APP_PATH . '/views/register.php';
            return;
        }

        // Load User model
        require_once APP_PATH . '/models/User.php';
        $userModel = new User();

        // Check for existing email
        if ($userModel->findByEmail($email)) {
            $errors[] = "Email is already registered.";
            require APP_PATH . '/views/register.php';
            return;
        }

        // Hash password securely
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Create user
        $success = $userModel->create([
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'address'    => $address,
            'phone'      => $phone,
            'email'      => $email,
            'password'   => $hashedPassword,
        ]);

        if ($success) {
            header("Location: /login");
            exit;
        } else {
            $errors[] = "Registration failed due to a server error.";
            require APP_PATH . '/views/register.php';
        }
    }
    
    public function login()
    {
        require_once APP_PATH . '/models/User.php';
        $userModel = new User();

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $errors = [];

        if ($email === '' || $password === '') {
            $errors[] = "Email and password are required.";
            require APP_PATH . '/views/login.php';
            return;
        }

        $user = $userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            $errors[] = "Invalid email or password.";
            require APP_PATH . '/views/login.php';
            return;
        }

        // Successful login → store user data in session
        $_SESSION['user'] = [
            'id' => $user['id'],
            'email' => $user['email'],
            'is_admin' => $user['is_admin'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
        ];

        header('Location: /feed');
        exit;
    }
    
    public function logout()
    {
    	// End session safely
    	session_unset();
    	session_destroy();

    	// Optionally start a new one (to allow flash messages later)
    	session_start();

    	// Render logout confirmation view
    	require APP_PATH . '/views/logout.php';
    }
    
}

