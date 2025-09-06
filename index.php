<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Shop</title>
    <link rel="stylesheet" href="styles.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
</head>
<body>
	<div class="container my-5">
		<h2>List of Clients</h2>
			<!-- SPRAWDZ LINK -->
            <!-- SPRAWDZ LINK -->
            <!-- SPRAWDZ LINK -->
            <!-- SPRAWDZ LINK -->
			<a class="btn btn-primary" href="create.php" role="button">New Client</a>

			<br/>

			<table class="account_doc">
				<thead>
					<tr>
						<th>Piesel</th>
						<th>Imie</th>
						<th>Nazwisko</th>
						<th>Data Urodzenia</th>
						<th>Płeć</th>
						<th>Saldo</th>
						<th>Admin</th>
						<th>Hasło</th>
					</tr>
				</thead>
				<tbody>
					<?php
                    // PRZETŁUMACZONE
                    // PRZETŁUMACZONE
                    // PRZETŁUMACZONE
					$connection = pg_connect("host=db dbname=db user=docker password=docker");
					
					if ($connection->connect_error) {
						die("Connection failed: " . $connection->connect_error);						
					}
					
					//read all row from database table
					// PRZETŁUMACZONE
					// PRZETŁUMACZONE
					// PRZETŁUMACZONE
					$sql = "SELECT * FROM account_doc";
                    $result = pg_exec($connection, $sql);
					
					if (!$result) {
						die("Invalid query: " . $connection->error);
					}
					
					// read data of each row
                    // PRZETŁUMACZONE
                    // PRZETŁUMACZONE
                    // PRZETŁUMACZONE
					for($row = 0; $row = pg_fetch_assoc($result); $row++) {
						echo "
						<tr>
							<td>$row[pesel]</td>
							<td>$row[imie]</td>
							<td>$row[nazwisko]</td>
							<td>$row[data_urodzenia]</td>
							<td>$row[plec]</td>
							<td>$row[saldo]</td>
							<td>$row[admin]</td>
							<td>$row[haslo]</td>
						<td>
						
							<a class='btn btn-primary btn-sm' href='edit.php?id=$row[pesel]'>Edytuj</a>
							<a class='btn btn-danger btn-sm' href='delete.php?id=$row[pesel]'>Usuń Konto</a>
						
						</td>
						
					</tr>
						";
					}
					
					?>
					
					
					<tr>
                        <!-- <td>87050277152</td>
                        <td>Gałązka</td>
                        <td>Zenon</td>
                        <td>02/05/1987</td>
                        <td>M</td>
                        <td>34.10</td>
                        <td>FALSE</td>
                        <td>pierorzek2025</td>
                        <td>
                        -->
                            <!-- SPRAWDZ LINK -->
                            <!-- SPRAWDZ LINK -->
                            <!-- SPRAWDZ LINK -->
                            <!-- SPRAWDZ LINK -->
                            <!--
							<a class='btn btn-primary btn-sm' href='edit.php'>Edytuj</a>
							<a class='btn btn-danger btn-sm' href='delete.php'>Usuń Konto</a>
						
						</td>
                        -->
						
					</tr>
					
					
					</tr>
				</tbody>
				
			</table>
    </div>
</body>
</html>