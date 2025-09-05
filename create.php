<?php
    $pesel = "";
    $imie = "";
    $nazwisko = "";
    $data_urodzenia = "";
    $plec = "";
    $saldo = "";
    $admin = "";
    $haslo = "";

    $errorMessage = "";
    $successMessage = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $pesel = $_POST["pesel"];
        $imie = $_POST["imie"];
        $nazwisko = $_POST["nazwisko"];
        $data_urodzenia = $_POST["data_urodzenia"];
        $plec = $_POST["plec"];
        $saldo = $_POST["saldo"];
        $admin = $_POST["admin"];
        $haslo = $_POST["haslo"];

        do {
            if (empty($pesel) || empty($imie) || empty($nazwisko) || empty($data_urodzenia) || empty($plec) || empty($saldo) || empty($haslo) ) {
                $errorMessage = "All fields are required.";
                break;

            }


        }   while (false);

        // add new client to database

        $pesel = "";
        $imie = "";
        $nazwisko = "";
        $data_urodzenia = "";
        $plec = "";
        $saldo = "";
        $admin = "";
        $haslo = "";

        $successMessage = "New client added successfully.";

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