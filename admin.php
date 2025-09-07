<?php
session_start();
// Sprawdzenie czy użytkownik jest zalogowany
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administracyjny - mBochnia</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #ff5151;
            font-family: Arial, sans-serif;
            padding-top: 20px;
            min-height: 100vh;
        }
        .container {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            margin-bottom: 2rem;
        }
        .logo-text {
            color: #ff5151;
            font-weight: bold;
            font-size: 2rem;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .btn-primary {
            background-color: #000;
            border-color: #000;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: bold;
        }
        .btn-primary:hover {
            background-color: #333;
            border-color: #333;
        }
        .btn-danger {
            border-radius: 8px;
        }
        .btn-outline-primary {
            border-radius: 8px;
            border-color: #ff5151;
            color: #ff5151;
        }
        .btn-outline-primary:hover {
            background-color: #ff5151;
            border-color: #ff5151;
            color: white;
        }
        .navbar {
            background-color: #000;
            border-radius: 10px;
            margin-bottom: 2rem;
            padding: 1rem;
        }
        .navbar-text {
            color: white !important;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #ff5151;
            color: white;
            font-weight: bold;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .table-container {
            overflow-x: auto;
            max-height: 600px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .table thead th {
            background-color: #ff5151;
            color: white;
            border-bottom: 2px solid #ddd;
        }
    </style>
</head>
<body>
<!-- Nawigacja -->
<div class="container">
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
                <span class="navbar-text me-auto">
                    <strong>mBochnia</strong> - Panel Administracyjny
                </span>
            <div class="d-flex">
                <a class="btn btn-outline-primary me-2" href="index.php">Strona Główna</a>
                <a class="btn btn-primary" href="logout.php">Wyloguj</a>
            </div>
        </div>
    </nav>

    <div class="logo-text">mBochnia</div>
    <h2 class="text-center mb-4">Lista Klientów</h2>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a class="btn btn-primary" href="create.php" role="button">Nowy Klient</a>
    </div>

    <div class="table-container">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>PESEL</th>
                <th>Imię</th>
                <th>Nazwisko</th>
                <th>Data Urodzenia</th>
                <th>Płeć</th>
                <th>Saldo</th>
                <th>Admin</th>
                <th>Akcje</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $connection = pg_connect("host=db dbname=db user=docker password=docker");

            if (!$connection) {
                die("Connection failed: " . pg_last_error());
            }

            // Odczytaj wszystkie wiersze z tabeli
            $sql = "SELECT * FROM account_doc";
            $result = pg_query($connection, $sql);

            if (!$result) {
                die("Invalid query: " . pg_last_error($connection));
            }

            // Odczytaj dane każdego wiersza
            while ($row = pg_fetch_assoc($result)) {
                // Formatowanie daty
                $data_urodzenia = date('d.m.Y', strtotime($row['data_urodzenia']));
                // Formatowanie salda
                $saldo = number_format($row['saldo'], 2, ',', ' ');
                // Formatowanie admina
                $admin = $row['admin'] == 't' ? 'TAK' : 'NIE';

                echo "
                        <tr>
                            <td>{$row['pesel']}</td>
                            <td>{$row['imie']}</td>
                            <td>{$row['nazwisko']}</td>
                            <td>{$data_urodzenia}</td>
                            <td>{$row['plec']}</td>
                            <td>{$saldo} zł</td>
                            <td>{$admin}</td>
                            <td>
                                <a class='btn btn-primary btn-sm' href='edit.php?id={$row['pesel']}'>Edytuj</a>
                                <a class='btn btn-danger btn-sm' href='delete.php?id={$row['pesel']}' 
                                   onclick='return confirm(\"Czy na pewno chcesz usunąć to konto?\")'>Usuń</a>
                            </td>
                        </tr>
                        ";
            }

            pg_close($connection);
            ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>