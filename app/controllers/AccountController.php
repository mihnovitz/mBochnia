<?php

class AccountController
{
    public function __construct()
    {
        if (empty($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        require_once APP_PATH . '/models/User.php';
    }

    public function index()
    {
        $user = $_SESSION['user'];
        require APP_PATH . '/views/account.php';
    }

    public function update()
    {
        $id = $_SESSION['user']['id'];
        $data = [
            'first_name' => trim($_POST['first_name'] ?? ''),
            'last_name'  => trim($_POST['last_name'] ?? ''),
            'address'    => trim($_POST['address'] ?? ''),
            'phone'      => trim($_POST['phone'] ?? ''),
        ];

        $password = $_POST['password'] ?? '';
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $userModel = new User();
        $userModel->update($id, $data);

        $_SESSION['user'] = $userModel->getById($id);

        header('Location: /account?updated=1');
        exit;
    }
}
