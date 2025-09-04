<?php
$db_handle = pg_connect("host=db dbname=db user=docker password=docker");
    pg_query("INSERT INTO my_table (name) VALUES ('Hello PostgreSQL!')");
    $query = "SELECT * FROM my_table";

    $result = pg_exec($db_handle, $query);

    for ($row = 0; $row < pg_numrows($result); $row++) {

        $firstname = pg_result($result, $row, 'id');

        echo $firstname . " ";
    }

