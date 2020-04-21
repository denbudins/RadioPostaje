<?php
include("../sesija.class.php");
include("../baza.class.php");

Sesija::kreirajSesiju();

if (isset($_SESSION["korisnik"])) {
    if ($_SESSION["uloga"] == 'Registriran korisnik') {
        header("Location: ../usr/index.php");
    }
    if ($_SESSION["uloga"] == 'Moderator') {
        header("Location: ../mod/index.php");
    }
}

$db = new Baza();
$db->spojiDB();

$sql_upit_oglasi = "SELECT korisnici.ime, korisnici.prezime, zahtjevi.naziv, zahtjevi.opis, zahtjevi.status FROM korisnici, zahtjevi WHERE korisnici.idkorisnici LIKE zahtjevi.korisnici_idkorisnici";
$oglasi = $db->selectDB($sql_upit_oglasi);

$head = "<thead>" . "<tr>" . "<th>Naziv</th>" . "<th>Opis</th>" . "<th>Vlasnik oglasa</th>" . "<th>Status</th>" .  "</tr>" . "</thead>";
$table = "";

while ($row = $oglasi->fetch_assoc()) {
    $table = $table . "<tr>";
    $table = $table . "<td>" . $row["naziv"] . "</td>" . "<td>" . $row["opis"] ."</td>" . "<td>" . $row["ime"] . " " . $row["prezime"] . "</td>" . "<td>" . $row["status"] . "</td>";
    $table = $table . "</tr>";
}
?>

<!DOCTYPE html>
<html lang="hr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="naslov" content="Početna stranica" />
        <meta name="kljucne_rijeci" content="projekt, početna" />
        <meta name="datum_izrade" content="25.03.2020." />
        <meta name="autor" content="Denis Martin Budinski" />
        <link rel="stylesheet" type="text/css" href="../css/denbudins.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/s/dt/jq-2.1.4,dt-1.10.10/datatables.min.css"/>
        <script type="text/javascript" src="https://cdn.datatables.net/s/dt/jq-2.1.4,dt-1.10.10/datatables.min.js"></script>
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#tablica').DataTable();
            });
        </script>
        <title>Statistika klikova</title>
    </head>
    <body>
    <?php
        include("zaglavlje.php");
    ?>
        <table id="tablica" class="display" style="width:90%">
            <?php
            echo $head;
            ?>
            <tbody style="text-align: center">
                <?php
                echo $table;
                ?>
            </tbody>
        </table>
    </body>
</html>