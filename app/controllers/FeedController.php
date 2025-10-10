<?php

class FeedController
{
    public function index()
    {
        if (empty($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $user = $_SESSION['user'];
        require APP_PATH . '/views/feed.php';
    }
}
