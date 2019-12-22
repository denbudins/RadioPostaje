<?php
include("../baza.class.php");
include("../sesija.class.php");

Sesija::kreirajSesiju();

if (isset($_SESSION["korisnik"])) {
    if ($_SESSION["uloga"] == 'Registriran korisnik') {
        header("Location: ../usr/index.php");
    }
    if ($_SESSION["uloga"] == 'Administrator') {
        header("Location: ../admin/index.php");
    }
}

$idVrste = "";
$idKorisnika = "";

$db = new Baza();
$db->spojiDB();

$sql_upit_moderatori = "SELECT t1.idradiopostaje, t1.naziv FROM radiopostaje t1 LEFT JOIN moderatori t2 ON t1.idradiopostaje = t2.radiopostaje_idradiopostaje WHERE t2.korisnici_idkorisnici = '" . $_SESSION["idkorisnik"]. "'";
$moderatori = $db->selectDB($sql_upit_moderatori);
$selected1 = "<select id='radiopostaje' name='radiopostaje'>";
while ($row = mysqli_fetch_array($moderatori)) {
    $prikaz = $row['naziv'];
    $selected1 .= "<option value='" . $row['idradiopostaje'] . "'>" . $prikaz . "</option>";
}
$selected1 .= "</select>";

$datum = "";
$pocetak = "";
$kraj = "";
$naziv = "";

if(isset($_POST["submit"])) {

    $datum = $_POST["datum"];
    $pocetak = $_POST["pocetak"];
    $kraj = $_POST["kraj"];
    $naziv = $_POST["naziv"];
    $datumZahtjeva = date("Y-m-d H:i:s");
    $idRadioPostaje = $_POST["radiopostaje"];
    $opisAkcije = "Dodan novi termin emitiranja za radio postaju " . $idRadioPostaje . "";
    
    $sql_novi_zahtjev = "INSERT INTO `terminiemitiranja`(`datum`, `pocetak_emitiranja`, `kraj_emitiranja`, `naziv`, `status`, radiopostaje_idradiopostaje) VALUES('" . $datum . "', '" . $pocetak . "', '" . $kraj . "', '" . $naziv . "', '1', '" . $idRadioPostaje . "')";
    $noviZahtjev = $db->selectDB($sql_novi_zahtjev);
    $sql_dnevnik = "INSERT INTO `dnevnik`(`skripta`, `vrijeme`, `opis`) VALUES('Novi termin emitiranja ', '" . $datumZahtjeva . "', '" . $opisAkcije . "')";
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
        <meta name="datum_izrade" content="17.12.2019." />
        <meta name="autor" content="Denis Martin Budinski" />
        <link rel="stylesheet" type="text/css" href="../css/denbudins.css">
        <title>Novi termin emitiranja</title>
    </head>
    <body>
        <header>
            <?php
                include("zaglavlje.php");
            ?>
        </header>
        <form class="form-login" id="novaPostaja" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <div class="form-log-in-with-email">
                <div class="form-white-background">
                    <div class="form-title-row">
                        <h1>Novi termin emitiranja</h1>
                    </div>
                    <div class="form-row">
                        <input id="naziv" type="text" name="naziv" placeholder="Naziv termina emitiranja"/>
                    </div>
                    <div class="form-row">
                        <h4>Datum emitiranja:</h4>
                        <input id="datum" type="date" name="datum"/>
                    </div>
                    <div class="form-row">
                        <h4>Početak emitiranja:</h4>
                        <input id="pocetak" type="time" name="pocetak"/>
                    </div>
                        <h4>Kraj emitiranja:</h4>
                        <input id="kraj" type="time" name="kraj"/>
                    </div>
                    <div class="form-row">
                        <h4>Radio postaja:</h4>
                        <?php echo$selected1; ?>
                    </div>
                    <div class="form-row">
                        <input id="submit" class="button" type="submit" name="submit" value="KREIRAJ TERMIN"/>
                    </div>
                </div>
            </div>
        </form>
    </body>
</html>