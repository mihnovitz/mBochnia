<?php
// delete.php

// 1. Connect to PostgreSQL (update with your DB credentials)
$host = "db";
$dbname = "db";
$user = "docker";
$password = "docker";

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// 2. Check if "id" (PESEL) is provided
if (isset($_GET['id'])) {
    $pesel = $_GET['id'];

    // 3. Prepare and execute DELETE query
    $stmt = $pdo->prepare("DELETE FROM account_doc WHERE pesel = :pesel");
    $stmt->bindParam(':pesel', $pesel, PDO::PARAM_STR);

    if ($stmt->execute()) {
        // Success – redirect back to list
        header("Location: index.php?msg=deleted");
        exit;
    } else {
        echo "Error: Could not delete record.";
    }
} else {
    echo "No PESEL provided.";
}
?>
