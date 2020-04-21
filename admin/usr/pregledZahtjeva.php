<?php
include("../../sesija.class.php");
include("../../baza.class.php");

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

$sql_upit_dnevnik = "SELECT * FROM zahtjevi WHERE korisnici_idkorisnici = '" . $_SESSION["idkorisnik"] . "'";
$dnevnik = $db->selectDB($sql_upit_dnevnik);

$head = "<thead class='table-section__zaglavlje'>" . "<tr>" . "<th>Oznaka zahtjeva</th>" . "<th>Naziv kategorije</th>" . "<th>Opis kategorije</th>" . "<th>Status zahtjeva</th>" . "</tr>" . "</thead>";
$table = "";

while ($row = $dnevnik->fetch_assoc()) {
    if($row["status"] == 0){
        $status = "Zahtjev u obradi";
        $table = $table . "<tr>";
        $table = $table . "<td>" . $row["idzahtjevi"] . "</td>" . "<td>" . $row["naziv"] . "</td>" . "<td>" . $row["opis"] . "</td>" . "<td>" . $status;
        $table = $table . "</tr>";
    }else if($row["status"] == 1){
        $status = "Prihvaćen";
        $table = $table . "<tr>";
        $table = $table . "<td>" . $row["idzahtjevi"] . "</td>" . "<td>" . $row["naziv"] . "</td>" . "<td>" . $row["opis"] . "</td>" . "<td>" . $status;
        $table = $table . "</tr>"; 
    }else if($row["status"] == 2){
        $status = "Odbijen od administratora";
        $table = $table . "<tr>";
        $table = $table . "<td>" . $row["idzahtjevi"] . "</td>" . "<td>" . $row["naziv"] . "</td>" . "<td>" . $row["opis"] . "</td>" . "<td>" . $status;
        $table = $table . "</tr>"; 
    }else if($row["status"] == 3){
        $status = "Odbijen od moderatora";
        $table = $table . "<tr>";
        $table = $table . "<td>" . $row["idzahtjevi"] . "</td>" . "<td>" . $row["naziv"] . "</td>" . "<td>" . $row["opis"] . "</td>" . "<td>" . $status;
        $table = $table . "</tr>"; 
    }
    else if($row["status"] == 4){
        $status = "Odobrena pjesma";
        $table = $table . "<tr>";
        $table = $table . "<td>" . $row["idzahtjevi"] . "</td>" . "<td>" . $row["naziv"] . "</td>" . "<td>" . $row["opis"] . "</td>" . "<td>" . $status;
        $table = $table . "</tr>"; 
    }else if($row["status"] == 5){
        $status = "Pjesma dodana u termin emitiranja";
        $table = $table . "<tr>";
        $table = $table . "<td>" . $row["idzahtjevi"] . "</td>" . "<td>" . $row["naziv"] . "</td>" . "<td>" . $row["opis"] . "</td>" . "<td>" . $status;
        $table = $table . "</tr>"; 
    }
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
        <link rel="stylesheet" type="text/css" href="../../css/denbudins.css">
        <script type="text/javascript" src="https://cdn.datatables.net/s/dt/jq-2.1.4,dt-1.10.10/datatables.min.js"></script>
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#tablica').DataTable();
            });
        </script>
        <title>Pregled kategorija pjesama</title>
    </head>
    <body>
        <?php
            include("zaglavlje.php");
        ?>
        <main>
        <section class="table-section table-section_margin">
            <table id="tablica" class="display">
                <?php
                echo $head;
                ?>
                <tbody  class="table-section__tjelo">
                    <?php
                    echo $table;
                    ?>
                </tbody>
            </table>
        </section>
    <main>
    </body>
</html>