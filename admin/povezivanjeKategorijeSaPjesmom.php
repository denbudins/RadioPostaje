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

$sql_upit_radio_postaje = "SELECT * FROM kategorijepjesama WHERE status = 1";
$radio_postaje = $db->selectDB($sql_upit_radio_postaje);
$selected1 = "<select id='kategorija' name='kategorija'>";
while ($row = mysqli_fetch_array($radio_postaje)) {
    $prikaz = $row['naziv'];
    $selected1 .= "<option value='" . $row['idkategorijepjesama'] . "'>" . $prikaz . "</option>";
}
$selected1 .= "</select>";

if (isset($_POST["submit"])) { 
    $idPjesme = $_POST["pjesma"];
    $idKategorije = $_POST["kategorija"];
    $datum = date("Y-m-d H:i:s");

    $sql_kategorija_pjesme = "INSERT INTO `pjesme_kategorije`(`pjesme_idpjesme`, `kategorijepjesama_idkategorijepjesama`) VALUES('" . $idPjesme . "', '" . $idKategorije . "')";
    $unosKategorije = $db->selectDB($sql_kategorija_pjesme);
    $sql_dnevnik = "INSERT INTO `dnevnik`(`skripta`, `vrijeme`, `opis`) VALUES('Dodjeljena kategorija pjesmi', '" . $datum . "', 'Dodjeljena kategorija ". $idKategorije." pjesmi " . $idPjesme . "')";
    $noviZapis = $db->selectDB($sql_dnevnik);
    header("Location: indexAdmin.php");
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
        <meta name="datum_izrade" content="13.01.2020." />
        <meta name="autor" content="Denis Martin Budinski" />
        <link rel="stylesheet" type="text/css" href="../css/denbudins.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/s/dt/jq-2.1.4,dt-1.10.10/datatables.min.css"/>
        <script type="text/javascript" src="https://cdn.datatables.net/s/dt/jq-2.1.4,dt-1.10.10/datatables.min.js"></script>
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
        <title>Dodavanje kategorije odobrenoj pjesmi</title>
    </head>
    <body>
    <form class="form-login" id="novaKategorijaPjesme" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <div class="form-log-in-with-email">
                <div class="form-white-background">
                    <div class="form-title-row">
                        <h1>Dodjela kategorije pjesmi</h1>
                    </div>
                    <div class="form-row">
                        <h4>Kategorija pjesme:</h4>
                        <?php echo$selected1;?>
                    </div>
                    <input name="pjesma" type="hidden" id="pjesma" value="<?php 
                    if (isset($_GET["id"])) {
                        $idPjesma = $_GET["id"];
                    }
                    echo $idPjesma; ?>">
                    <div class="form-row">
                        <input id="submit" class="button" type="submit" name="submit" value="Dodaj kategoriju"/>
                    </div>
                </div>
            </div>
        </form>
    </body>
</html>