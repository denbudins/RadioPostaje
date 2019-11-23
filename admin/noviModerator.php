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

$sql_upit_korisnici = "SELECT * FROM korisnici WHERE tipkorisnika_idtipkorisnika NOT LIKE '3'";
$korisnici = $db->selectDB($sql_upit_korisnici);
$head = "<thead>" . "<tr>" . "<th>Ime i prezime</th>" . "<th>Email</th>" . "<th>Korisničko ime</th>" . "<th>Uloga</th>" . "<th>Dodaj moderatora</th>" . "<th>Oduzmi moderatora</th>" . "</tr>" . "</thead>";
$table = "";

while ($row = $korisnici->fetch_assoc()) {
    $table = $table . "<tr>";
    if ($row["tipkorisnika_idtipkorisnika"] == '1') {
        $table = $table . "<td>" . $row["ime"] . " " . $row["prezime"] . "</td>" . "<td>" . $row["email"] . "</td>" . "<td>" . $row["username"] . "</td>" . "<td>Registriran korisnik</td>" . "<td><a href='obradaKorisnika.php?id=" . $row["idkorisnici"] . "&akcija=2'>DAJ PRAVO MODERATORA</a></td>" . "<td><a href='obradaKorisnika.php?id=" . $row["idkorisnici"] . "&akcija=3'>ODUZMI PRAVO MODERATORA</a></td>";
    }
    if ($row["tipkorisnika_idtipkorisnika"] == '2') {
        $table = $table . "<td>" . $row["ime"] . " " . $row["prezime"] . "</td>" . "<td>" . $row["email"] . "</td>" . "<td>" . $row["username"] . "</td>" . "<td>Moderator</td>" . "<td><a href='obradaKorisnika.php?id=" . $row["idkorisnici"] . "&akcija=2'>DAJ PRAVO MODERATORA</a></td>" . "<td><a href='obradaKorisnika.php?id=" . $row["idkorisnici"] . "&akcija=3'>ODUZMI PRAVO MODERATORA</a></td>";
    }
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
        <meta name="datum_izrade" content="29.10.2019." />
        <meta name="autor" content="Denis Martin Budinski" />
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
        <title>Novi moderator</title>
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