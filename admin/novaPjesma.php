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

$sql_upit_korisnici = "SELECT * FROM zahtjevi WHERE tipovizahtjeva_idtipovizahtjeva = '2' AND status = '0'";
$korisnici = $db->selectDB($sql_upit_korisnici);
$head = "<thead>" . "<tr>" . "<th>Naziv pjesme</th>" . "<th>Opis pjesme</th>". "<th>Odobri zahtjev za pjesmu</th>" . "<th>Odbij zahtjev za pjesmu</th>" . "</tr>" . "</thead>";
$table = "";

while ($row = $korisnici->fetch_assoc()) {
    $table = $table . "<tr>";
        $table = $table . "<td>" . $row["naziv"] . "</td>" . "<td>" . $row["opis"] . "</td>" . "<td><a href='obradaZelje.php?id=" . $row["idzahtjevi"] . "&akcija=1'>ODOBRI</a></td>" . "<td><a href='obradaZelje.php?id=" . $row["idzahtjevi"] . "&akcija=2'>ODBIJ</a></td>";
    $table = $table . "</tr>";
}

$db->zatvoriDB();
?>

<!DOCTYPE html>
<html lang="hr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="naslov" content="Početna stranica" />
        <meta name="kljucne_rijeci" content="projekt, početna" />
        <meta name="datum_izrade" content="17.12.2019." />
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
        <title>Obrada zahtjeva za glazbenim željama</title>
    </head>
    <body>
    <?php
        include("zaglavlje.php");
    ?>
    <p style="padding-top: 1%;"></p>
        <table id="tablica" class="display">
            <?php
            echo $head;
            ?>
            <tbody  >
                <?php
                echo $table;
                ?>
            </tbody>
        </table>
    </body>
</html>