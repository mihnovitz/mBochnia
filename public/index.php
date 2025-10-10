<?php
$host = 'db';
$db   = 'appdb';
$user = 'appuser';
$pass = 'apppass';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$db", $user, $pass);
    echo "✅ Connected to PostgreSQL successfully!";
} catch (PDOException $e) {
    echo "❌ Connection failed: " . $e->getMessage();
}
?>

