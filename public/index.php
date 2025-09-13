<?php
// public/index.php

// Load configuration files
require_once __DIR__ . '/../app/config/app.php';
require_once __DIR__ . '/../app/config/database.php';

// Load core components
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/Auth.php';

// Start session with configured settings
Auth::startSession();

// Simple routing based on the 'action' parameter
$action = $_GET['action'] ?? 'home';

// Route the request
switch ($action) {
    case 'home':
        require_once __DIR__ . '/../app/controllers/HomeController.php';
        $controller = new HomeController();
        $controller->index();
        break;

    case 'dashboard':
        require_once __DIR__ . '/../app/controllers/HomeController.php';
        $controller = new HomeController();
        $controller->dashboard();
        break;

    case 'login':
        require_once __DIR__ . '/../app/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->login();
        break;

    case 'register':
        require_once __DIR__ . '/../app/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->register();
        break;

    case 'logout':
        require_once __DIR__ . '/../app/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->logout();
        break;

    case 'admin-users':
        require_once __DIR__ . '/../app/controllers/AdminController.php';
        $controller = new AdminController();
        $controller->users();
        break;

    case 'create-user':
        require_once __DIR__ . '/../app/controllers/AdminController.php';
        $controller = new AdminController();
        $controller->createUser();
        break;

    case 'edit-user':
        require_once __DIR__ . '/../app/controllers/AdminController.php';
        $controller = new AdminController();
        $controller->editUser();
        break;

    case 'delete-user':
        require_once __DIR__ . '/../app/controllers/AdminController.php';
        $controller = new AdminController();
        $controller->deleteUser();
        break;

    case 'documents':
        require_once __DIR__ . '/../app/controllers/CardController.php';
        $controller = new CardController();
        $controller->index();
        break;

    case 'handle-card':
        require_once __DIR__ . '/../app/controllers/CardController.php';
        $controller = new CardController();
        $controller->handleCardOperation();
        break;

    case 'announcements':
        require_once __DIR__ . '/../app/controllers/AnnouncementController.php';
        $controller = new AnnouncementController();
        $controller->index();
        break;

    case 'create-announcement':
        require_once __DIR__ . '/../app/controllers/AnnouncementController.php';
        $controller = new AnnouncementController();
        $controller->create();
        break;

    case 'applications':
        require_once __DIR__ . '/../app/controllers/ApplicationController.php';
        $controller = new ApplicationController();
        $controller->index();
        break;

    case 'create-application':
        require_once __DIR__ . '/../app/controllers/ApplicationController.php';
        $controller = new ApplicationController();
        $controller->create();
        break;

    case 'add-response':
        require_once __DIR__ . '/../app/controllers/ApplicationController.php';
        $controller = new ApplicationController();
        $controller->addResponse();
        break;


    // Add more routes as we create controllers
    default:
        // Show homepage for unknown actions
        require_once __DIR__ . '/../app/controllers/HomeController.php';
        $controller = new HomeController();
        $controller->index();
        exit;
}
?>