<?php
include("../baza.class.php");

$baza = new Baza();
$baza->spojiDB();

$sql_upit_korisnici = "SELECT * FROM korisnici";
$korisnici = $baza->selectDB($sql_upit_korisnici);

$head = "<thead>" . "<tr>" . "<th>Korisnicko ime</th>" . "<th>Ime</th>" . "<th>Prezime</th>" . "<th>Email</th>" . "<th>Lozinka</th>" . "</tr>" . "</thead>";
$table = "";

while ($row = $korisnici->fetch_assoc()) {
    $table = $table . "<tr>";
    $table = $table . "<td>" . $row["username"] . "</td>" . "<td>" . $row["ime"] . "</td>" . "<td>" . $row["prezime"] . "</td>" . "<td>" . $row["email"] . "</td>" . "<td>" . $row["lozinka"] . "</td>";
    $table = $table . "</tr>";
}

$baza->zatvoriDB();
?>

<!DOCTYPE html>
<html lang="hr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="naslov" content="Početna stranica" />
        <meta name="kljucne_rijeci" content="projekt, početna" />
        <meta name="datum_izrade" content="28.03.2020." />
        <meta name="autor" content="Denis Martin Budinski" />
        <link rel="stylesheet" type="text/css" href="../css/denbudins.css">
        <!-- jQuery lib -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

        <!-- datatable lib -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/s/dt/jq-2.1.4,dt-1.10.10/datatables.min.css"/>
        <script type="text/javascript" src="https://cdn.datatables.net/s/dt/jq-2.1.4,dt-1.10.10/datatables.min.js"></script>

        <!-- jqueryUI -->
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#tablica').DataTable();
            }); 
        </script>
        <title>Korisnici</title>
    </head>
    <body>
        <header>
                <nav>
                    <a href="../index.php">Naslovna</a></li>
                    <a href="https://barka.foi.hr/WebDiP/2018_projekti/WebDiP2018x018/prijava.php">Prijava</a>
                    <a href="../registracija.php">Registracija</a>
                    <a href="../pregledKategorija.php">Kategorija pjesama</a>
                    <a href="../pregledePjesama.php">Pjesama</a>
                </nav>
        </header>
        <table id="tablica" class="display" style="width:90%">
            <?php
            echo $head;
            ?>
            <tbody>
                <?php
                echo $table;
                ?>
            </tbody>
        </table>
    </body>
</html>