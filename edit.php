<?php
$pesel = "";
$imie = "";
$nazwisko = "";
$data_urodzenia = "";
$plec = "";
$saldo = "";
$admin = "";
$haslo = "";

$connection = pg_connect("host=db dbname=db user=docker password=docker");

$errorMessage = "";
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //GET method: show the data of the client

    if (!isset($_GET["pesel"]) ){
        header("Location: index.php");
        exit;
    }

    $pesel = $_GET["pesel"];

    $sql = "SELECT * FROM account_doc WHERE pesel = '$pesel'";
    $result = $connection->query($sql);
    $row = $result->fetch_assoc();
    /*
    $result = pg_exec($connection, $sql);
    $row = pg_fetch_assoc($result);
    */
    if (!row) {
        header("Location: index.php");
        exit;

        $pesel = $row["pesel"];
        $imie = $row["imie"];
        $nazwisko = $row["nazwisko"];
        $data_urodzenia = $row["data_urodzenia"];
        $plec = $row["plec"];
        $saldo = $row["saldo"];
        $admin = $row["admin"];
        $haslo = $row["haslo"];
    }
    else {
        // POST method: update the data of the client
        $pesel = $row["pesel"];
        $imie = $row["imie"];
        $nazwisko = $row["nazwisko"];
        $data_urodzenia = $row["data_urodzenia"];
        $plec = $row["plec"];
        $saldo = $row["saldo"];
        $admin = $row["admin"];
        $haslo = $row["haslo"];

        do {
            if (empty($pesel) || empty($imie) || empty($nazwisko) || empty($data_urodzenia) || empty($plec) || empty($saldo) || empty($haslo) ) {
                $errorMessage = "All fields are required.";
                break;

            }

            $sql = "UPDATE clients " .
                    " SET pesel = '$pesel', imie = '$pesel', nazwisko = '$nazwisko', data_urodzenia = '$data_urodzenia', plec = '$plec', saldo = '$saldo', admin = '$admin', haslo = '$haslo' " .
                " WHERE pesel = '$pesel'";

            $result = pg_exec($connection, $sql);

            if (!$result) {
                $errorMessage = "Invalid query: ". $connection->error;
                break;
            }

            $successMessage = "Client updated successfully.";
            header("Location: index.php");
            exit;


        }   while (true);

    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Użytkownicy</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container my-5">
    <h2>New Client</h2>

    <?php
    /*

    if (!empty($errorMessage) ):
    echo "
    <div class='alert alert-warning alert-dismissible fade show' role='alert'>
        <strong>$errorMessage</strong>
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>

    </div>
    ";
    */
    ?>


    <form method="post">
        <input type="hidden" name="pesel" value="<?php echo $pesel; ?>">
        <div class="mb-3">
            <label class="col-sm-3 col-form-label">Pesel</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" name="pesel" value="<?php echo $pesel; ?>">
            </div>
        </div>
        <div class="mb-3">
            <label class="col-sm-3 col-form-label">Imie</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" name="imie" value="<?php echo $imie; ?>">
            </div>
        </div>
        <div class="mb-3">
            <label class="col-sm-3 col-form-label">Nazwisko</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" name="nazwisko" value="<?php echo $nazwisko; ?>">
            </div>
        </div>
        <div class="mb-3">
            <label class="col-sm-3 col-form-label">Data Urodzenia</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" name="data_urodzenia" value="<?php echo $data_urodzenia; ?>">
            </div>
        </div>
        <div class="mb-3">
            <label class="col-sm-3 col-form-label">Płeć</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" name="plec" value="<?php echo $plec; ?>">
            </div>
        </div>
        <div class="mb-3">
            <label class="col-sm-3 col-form-label">Saldo</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" name="saldo" value="<?php echo $saldo; ?>">
            </div>
        </div>
        <div class="mb-3">
            <label class="col-sm-3 col-form-label">Admin</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" name="admin" value="<?php echo $admin; ?>">
            </div>
        </div>
        <div class="mb-3">
            <label class="col-sm-3 col-form-label">Hasło</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" name="haslo" value="<?php echo $haslo; ?>">
            </div>
        </div>

        <!-- wrzutka 1 -->
        <?php
        /*
        if (!empty($successMessage)) {
            echo "
            <div class='row mb-3'>
                <div class='offset-sm-3 col-sm-6'>
                    <div class='alert alert-success alert-dismissible fade show' role='alert'>
                        <strong>$successMessage</strong>
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>
                </div>
            </div>
            ";
        }
        endif;
        */
        ?>


        <div class="row mb-3">
            <div class="offset-sm-3 col-sm-3 d-grid">
                <button type="submit" class="btn btn-primary">Dodaj</button>
            </div>
            <div class="col-sm-3 d-grid">
                <a class="btn btn-outline-primary" href="index.php" role="button">Anuluj</a>


            </div>

        </div>

    </form>



</div>


</body>
</html>