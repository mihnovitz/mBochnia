<?php
// app/config/app.php

class AppConfig {
    // Application settings
    const APP_NAME = 'mBochnia';
    const APP_VERSION = '1.0.0';
    const APP_ENV = 'development'; // 'development' or 'production'

    // Security settings
    const SESSION_NAME = 'mbochnia_session';
    const SESSION_LIFETIME = 3600; // 1 hour

    // Path settings
    const BASE_URL = '/public/'; // Adjust based on your installation

    // Email settings (if needed later)
    const ADMIN_EMAIL = 'admin@bochnia.pl';
    const SUPPORT_EMAIL = 'support@bochnia.pl';

    // File upload settings (if needed later)
    const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5MB
    const ALLOWED_FILE_TYPES = ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'];

    public static function init() {
        // Set session settings
        if (session_status() == PHP_SESSION_NONE) {
            session_name(self::SESSION_NAME);
            session_set_cookie_params([
                'lifetime' => self::SESSION_LIFETIME,
                'path' => '/',
                'domain' => $_SERVER['HTTP_HOST'] ?? '',
                'secure' => self::APP_ENV === 'production',
                'httponly' => true,
                'samesite' => 'Strict'
            ]);
        }

        // Set error reporting based on environment
        if (self::APP_ENV === 'development') {
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
        } else {
            error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
            ini_set('display_errors', 0);
        }

        // Set default timezone
        date_default_timezone_set('Europe/Warsaw');
    }

    public static function getBaseUrl() {
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            $protocol = 'https';
        } else {
            $protocol = 'http';
        }

        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        return $protocol . '://' . $host . self::BASE_URL;
    }

    public static function isProduction() {
        return self::APP_ENV === 'production';
    }

    public static function isDevelopment() {
        return self::APP_ENV === 'development';
    }
}

// Initialize application configuration
AppConfig::init();
?>