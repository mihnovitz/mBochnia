<?php
// app/controllers/AnnouncementController.php

require_once __DIR__ . '/../models/Announcement.php';

class AnnouncementController {
    protected $announcementModel;

    public function __construct() {
        Auth::redirectIfNotLoggedIn('index.php?action=login');
        $this->announcementModel = new Announcement();
    }

    public function index() {
        // Try to migrate from file if table is empty
        $announcements = $this->announcementModel->getAll();
        if (empty($announcements)) {
            $this->announcementModel->migrateFromFile();
            $announcements = $this->announcementModel->getAll();
        }

        $is_admin = Auth::isAdmin();
        $success = isset($_GET['success']);

        require_once __DIR__ . '/../../views/announcements/index.php';
    }

    public function create() {
        if (!Auth::isAdmin()) {
            header('Location: index.php?action=announcements');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'data' => $_POST['data'],
                'watek' => $_POST['watek'],
                'autor' => $_POST['autor'],
                'tresc' => $_POST['tresc']
            ];

            try {
                $this->announcementModel->create($data);
                $_SESSION['success_message'] = "Ogłoszenie zostało dodane pomyślnie!";
                header('Location: index.php?action=announcements&success=1');
                exit;
            } catch (Exception $e) {
                $_SESSION['error_message'] = "Błąd podczas dodawania ogłoszenia: " . $e->getMessage();
                header('Location: index.php?action=announcements');
                exit;
            }
        }

        // If not POST, redirect to announcements
        header('Location: index.php?action=announcements');
        exit;
    }
}
?>