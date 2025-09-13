<?php
// app/config/database.php

class DatabaseConfig {
    public static function getConnection() {
        static $pdo = null;

        if ($pdo === null) {
            // Database configuration - you can move these to environment variables later
            $host = "db";
            $dbname = "db";
            $user = "docker";
            $password = "docker";
            $port = "5432";

            try {
                $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
                $pdo = new PDO($dsn, $user, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_PERSISTENT => false
                ]);

                // Set timezone if needed
                $pdo->exec("SET TIME ZONE 'UTC'");

            } catch (PDOException $e) {
                // Log the error instead of showing it to the user
                error_log("Database connection failed: " . $e->getMessage());

                // Show a generic error message
                if (php_sapi_name() !== 'cli') {
                    header('HTTP/1.1 500 Internal Server Error');
                    include __DIR__ . '/../views/errors/500.php';
                }
                exit;
            }
        }

        return $pdo;
    }

    // Helper method for raw queries (use sparingly)
    public static function rawQuery($sql) {
        $pdo = self::getConnection();
        return $pdo->query($sql);
    }
}
?>