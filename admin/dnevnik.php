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

$sql_upit_dnevnik = "SELECT * FROM dnevnik";
$dnevnik = $db->selectDB($sql_upit_dnevnik);

$head = "<thead class='table-section__zaglavlje'>" . "<tr>" . "<th>Akcija</th>" . "<th>Datum i vrijeme akcije</th>" . "<th>Opis akcije</th>" . "</tr>" . "</thead>";
$table = "";

while ($row = $dnevnik->fetch_assoc()) {
    $table = $table . "<tr>";
    $table = $table . "<td>" . $row["skripta"] . "</td>" . "<td>" . $row["vrijeme"] . "</td>" . "<td>" . $row["opis"];
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
        <meta name="datum_izrade" content="28.10.2019." />
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
        <title>Dnevnik</title>
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
                    <tbody class="table-section__tjelo">
                        <?php
                        echo $table;
                        ?>
                    </tbody>
                </table>
        </section>
    </main>
    </body>
</html>