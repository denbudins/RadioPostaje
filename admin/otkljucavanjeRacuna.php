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

$sql_upit_korisnici = "SELECT * FROM korisnici";
$korisnici = $db->selectDB($sql_upit_korisnici);
$head = "<thead class='table-section__zaglavlje'>" . "<tr>" . "<th>Ime i prezime</th>" . "<th>Korisničko ime</th>" . "<th>Status</th>" . "<th>Otključaj</th>" . "<th>Zaključaj</th>" . "</tr>" . "</thead>";
$table = "";

while ($row = $korisnici->fetch_assoc()) {
    $table = $table . "<tr>";
    if ($row["status"] == '0') {
        $table = $table . "<td>" . $row["ime"] . " " . $row["prezime"] . "</td>" . "<td>" . $row["username"] . "</td>" . "<td>Neaktivan</td>" . "<td><a href='obradaKorisnika.php?id=" . $row["idkorisnici"] . "&akcija=1'>OTKLJUČAJ</a></td>" . "<td><a href='obradaKorisnika.php?id=" . $row["idkorisnici"] . "&akcija=0'>ZAKLJUČAJ</a></td>";
    }
    if ($row["status"] == '1') {
        $table = $table . "<td>" . $row["ime"] . " " . $row["prezime"] . "</td>" . "<td>" . $row["username"] . "</td>" . "<td>Otključan</td>" . "<td><a href='obradaKorisnika.php?id=" . $row["idkorisnici"] . "&akcija=1'>OTKLJUČAJ</a></td>" . "<td><a href='obradaKorisnika.php?id=" . $row["idkorisnici"] . "&akcija=0'>ZAKLJUČAJ</a></td>";
    }
    if ($row["status"] == '2') {
        $table = $table . "<td>" . $row["ime"] . " " . $row["prezime"] . "</td>" . "<td>" . $row["username"] . "</td>" . "<td>Zaključan</td>" . "<td><a href='obradaKorisnika.php?id=" . $row["idkorisnici"] . "&akcija=1'>OTKLJUČAJ</a></td>" . "<td><a href='obradaKorisnika.php?id=" . $row["idkorisnici"] . "&akcija=0'>ZAKLJUČAJ</a></td>";
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
        <meta name="datum_izrade" content="28.03.2020." />
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
        <title>Upravljanje korisnicima</title>
    </head>
    <body>
        <header>
            <?php
            include("zaglavlje.php");
            ?>
        </header>
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