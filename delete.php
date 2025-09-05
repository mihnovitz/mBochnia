<?php
    if (isset($_GET["pesel"])) {
        $pesel = $_GET["pesel"];


        $host = "db";
        $dbname = "db";
        $user = "docker";
        $password = "";


        $connection = pg_connect("host=db dbname=db user=docker password=docker");

        $sql = "DELETE FROM account_doc WHERE pesel =$pesel";
        //
        //$result = pg_exec($connection, $sql);

        $connection -> query($sql);

    }

    header("Location: index.php");
    exit;
?>