<?php
// app/controllers/HomeController.php

class HomeController {

    public function index() {
        $is_logged_in = Auth::check();
        $user_data = [];

        if ($is_logged_in) {
            $user_data = Auth::user();
        }

        require_once __DIR__ . '/../../views/home/index.php';

    }

    public function dashboard() {
        Auth::redirectIfNotLoggedIn('index.php?action=login');

        $user_data = Auth::user();
        $is_admin = Auth::isAdmin();

        require_once __DIR__ . '/../../views/home/dashboard.php';
    }
}
?>