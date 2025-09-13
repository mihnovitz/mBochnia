<?php
// app/core/Auth.php

class Auth {
    public static function startSession() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function check() {
        self::startSession();
        return isset($_SESSION['user_id']);
    }

    public static function redirectIfNotLoggedIn($redirectTo = 'login.php') {
        if (!self::check()) {
            header('Location: ' . $redirectTo);
            exit;
        }
    }

    public static function user() {
        self::startSession();
        return [
            'id' => $_SESSION['user_id'] ?? null,
            'email' => $_SESSION['user_email'] ?? null,
            'name' => $_SESSION['user_name'] ?? null,
            'is_admin' => $_SESSION['is_admin'] ?? false
        ];
    }

    public static function isAdmin() {
        $user = self::user();
        return $user['is_admin'] === true || $user['is_admin'] === 't';
    }

    public static function logout() {
        self::startSession();
        $_SESSION = array();
        session_destroy();
    }
}
?>