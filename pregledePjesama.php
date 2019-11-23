<?php
include("sesija.class.php");
include("baza.class.php");

Sesija::kreirajSesiju();

if (isset($_SESSION["korisnik"])) {
    if ($_SESSION["uloga"] == 'Registriran korisnik') {
        header("Location: usr/index.php");
    }
    if ($_SESSION["uloga"] == 'Moderator') {
        header("Location: mod/index.php");
    }
    if ($_SESSION["uloga"] == 'Administrator') {
        header("Location: admin/index.php");
    }
}

$db = new Baza();
$db->spojiDB();

$sql_upit_dnevnik = "SELECT t1.*, t3.naziv As naziv_kategorije FROM pjesme t1 LEFT JOIN pjesme_kategorije t2 ON t1.idpjesme = t2.pjesme_idpjesme LEFT JOIN kategorijepjesama t3 ON t2.kategorijepjesama_idkategorijepjesama = t3.idkategorijepjesama";
$dnevnik = $db->selectDB($sql_upit_dnevnik);

$head = "<thead>" . "<tr>" . "<th>Redni broj</th>" . "<th>Naziv pjesme</th>" . "<th>Opis pjesme</th>" . "<th>Trajenje pjesme</th>". "<th>Naziv kategorije</th>". "</tr>" . "</thead>";
$table = "";

while ($row = $dnevnik->fetch_assoc()) {
    $table = $table . "<tr>";
    $table = $table . "<td>" . $row["idpjesme"] . "</td>" . "<td>" . $row["naziv"] . "</td>" . "<td>" . $row["opis"]. "</td>" . "<td>" . $row["trajanje"] . "</td>"."<td>" . $row["naziv_kategorije"];
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
        <meta name="datum_izrade" content="16.11.2019." />
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
        <title>Pregled pjesama</title>
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