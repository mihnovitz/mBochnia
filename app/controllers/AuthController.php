<?php

class AuthController
{
    public function registerForm()
    {
        require APP_PATH . '/views/register.php';
    }
    
    public function loginForm()
    {
        require APP_PATH . '/views/login.php';
    }

    public function register()
    {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Method Not Allowed";
            return;
        }

        $firstName = trim($_POST['first_name'] ?? '');
        $lastName  = trim($_POST['last_name'] ?? '');
        $address   = trim($_POST['address'] ?? '');
        $phone     = trim($_POST['phone'] ?? '');
        $email     = trim($_POST['email'] ?? '');
        $password  = $_POST['password'] ?? '';

        $errors = [];

        if ($firstName === '' || $lastName === '' || $address === '' || $phone === '' || $email === '' || $password === '') {
            $errors[] = "All fields are required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email address.";
        }

        if (!empty($errors)) {
            require APP_PATH . '/views/register.php';
            return;
        }

        require_once APP_PATH . '/models/User.php';
        $userModel = new User();

        if ($userModel->findByEmail($email)) {
            $errors[] = "Email is already registered.";
            require APP_PATH . '/views/register.php';
            return;
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

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
	$email = trim($_POST['email'] ?? '');
	$password = $_POST['password'] ?? '';

	if ($email === '' || $password === '') {
		echo "Email and password are required.";
		return;
	}

	require_once APP_PATH . '/models/User.php';
	$userModel = new User();
	$user = $userModel->findByEmail($email);

	if (!$user || !password_verify($password, $user['password'])) {
		require __DIR__ . '/../views/invalid_credentials.php';
		return;
	}

	$_SESSION['user'] = [
		'id' => $user['id'],
		'email' => $user['email'],
		'first_name' => $user['first_name'],
		'last_name' => $user['last_name'],
		'is_admin' => (bool)$user['is_admin']
	];

	if ($_SESSION['user']['is_admin']) {
	header('Location: /admin');
	} else {
		header('Location: /feed');
	}

	exit;
	}
    
    	public function logout()
    	{
    		session_unset();
    		session_destroy();

    		session_start();

    		require APP_PATH . '/views/logout.php';
    	}
    
}

