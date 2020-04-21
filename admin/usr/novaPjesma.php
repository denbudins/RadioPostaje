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

$idKorisnika = "";
$db = new Baza();
$db->spojiDB();

$sql_upit_radio_postaja = "SELECT idradiopostaje, naziv FROM radiopostaje";
$radioPostaja = $db->selectDB($sql_upit_radio_postaja);
$selected3 = "<select id='postaja' name='postaja' class='selected-dodavanje'>";
while ($row = mysqli_fetch_array($radioPostaja)) {
    $prikaz = $row['naziv'];
    $selected3 .= "<option value='" . $row['idradiopostaje'] . "'>" . $prikaz . "</option>";
}
$selected3 .= "</select>";

$sql_upit_korisnik = "SELECT * FROM korisnici WHERE username LIKE '" . $_SESSION["korisnik"] . "'";
$korisnik = $db->selectDB($sql_upit_korisnik);
while ($row = $korisnik->fetch_assoc()) {
    $idKorisnika = $row["idkorisnici"];
}

$naziv = "";
$opis = "";
$url = "";
$trajanje = "";
$idTermina = "";

if(isset($_POST["submit"])) {
    $naziv = $_POST["naziv"];
    $opis = $_POST["opis"];
    $url = $_POST["url"];
    $datumZahtjeva = date("Y-m-d H:i:s");
    $trajanje = $_POST["trajanje"];
    $idTermina = $_POST["termin"]; 
    
    $sql_novi_zahtjev = "INSERT INTO `zahtjevi`(`naziv`, `opis`, `trajanje`, `lokacija_zvuka`, `status`, `korisnici_idkorisnici`, `korisnici_tipkorisnika_idtipkorisnika`, `tipovizahtjeva_idtipovizahtjeva`, `terminiemitiranja_idterminiemitiranja`) VALUES('" . $naziv . "', '" . $opis . "', '" . $trajanje . "', '" . $url . "', '0', '" . $idKorisnika . "', '1', '2', '" . $idTermina . "')";
    $noviZahtjev = $db->selectDB($sql_novi_zahtjev);
    $sql_dnevnik = "INSERT INTO `dnevnik`(`skripta`, `vrijeme`, `opis`) VALUES('Novi zahtjev', '" . $datumZahtjeva . "', 'Podnesen zahtjev za novom pjesmom')";
    $noviZapis = $db->selectDB($sql_dnevnik);
    header("Location: index.php");
} 
?>

<!DOCTYPE html>
<html lang="hr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="naslov" content="Početna stranica" />
        <meta name="kljucne_rijeci" content="projekt, početna" />
        <meta name="datum_izrade" content="24.11.2019." />
        <meta name="autor" content="Denis Martin Budinski" />
        <link rel="stylesheet" type="text/css" href="../../css/denbudins.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/s/dt/jq-2.1.4,dt-1.10.10/datatables.min.css"/>
        <script type="text/javascript" src="https://cdn.datatables.net/s/dt/jq-2.1.4,dt-1.10.10/datatables.min.js"></script>
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
        <script type="text/javascript" src="../../js/denbudins.js"></script>
        <title>Novi zahtjev za glazbenom željom</title>
    </head>
    <body>
        <header>
        <?php
                include("zaglavlje.php");
            ?>
        </header>
        <form class="form-login" id="noviOglas" method="POST" action="#file" enctype="multipart/form-data">
            <div class="form-log-in-with-email">
                <div class="form-white-background">
                    <div class="form-row">
                        <input id="naziv" type="text" name="naziv" placeholder="Naziv pjesme"/>
                    </div>
                    <div class="form-row">
                        <input id="opis" type="text" name="opis" placeholder="Opis pjesme"/>
                    </div>
                    <div class="form-row">
                        <input id="url" type="text" name="url" placeholder="URL lokacije zvuka"/>
                    </div>
                    <div class="form-row">
                        <label for="trajanje">Trajanje pjesme: </label>
                        <input id="trajanje" type="number" name="trajanje" step="0.01"/>
                    </div>
                    <div class="form-row">
                        <h4>Radio postaja:</h4>
                        <?php echo$selected3; ?>
                    </div>
                    <div class="form-row">
                        <h4>Termin emitiranja:</h4>
                        <selected id=termini></selected>
                    </div>
                    <div class="form-row">
                        <input id="submit" class="button" type="submit" name="submit" value="POŠALJI ZAHTJEV"/>
                    </div>
                </div>

            </div>
        </form>
        </body>
</html>