<?php
include("../baza.class.php");
include("../sesija.class.php");

Sesija::kreirajSesiju();

if (isset($_SESSION["korisnik"])) {
    if ($_SESSION["uloga"] == 'Moderator') {
        header("Location: ../mod/index.php");
    }
    if ($_SESSION["uloga"] == 'Administrator') {
        header("Location: ../admin/index.php");
    }
}

$idVrste = "";
$idKorisnika = "";

$db = new Baza();
$db->spojiDB();

$sql_upit_pjesme = "SELECT pjesme.idpjesme, pjesme.naziv FROM pjesme";
$pjesme = $db->selectDB($sql_upit_pjesme);
$selected1 = "<select id='pjesma' name='pjesma'>";
while ($row = mysqli_fetch_array($pjesme)) {
    $prikaz = $row['naziv'];
    $selected1 .= "<option value='" . $row['idpjesme'] . "'>" . $prikaz . "</option>";
}
$selected1 .= "</select>";

$sql_upit_termin = "SELECT terminiemitiranja.idterminiemitiranja, terminiemitiranja.naziv FROM terminiemitiranja";
$termin = $db->selectDB($sql_upit_termin);
$selected2 = "<select id='termin' name='termin'>";
while ($row = mysqli_fetch_array($termin)) {
    $prikaz = $row['naziv'];
    $selected2 .= "<option value='" . $row['idterminiemitiranja'] . "'>" . $prikaz . "</option>";
}
$selected2 .= "</select>";

$sql_upit_korisnik = "SELECT * FROM korisnici WHERE username LIKE '" . $_SESSION["korisnik"] . "'";
$korisnik = $db->selectDB($sql_upit_korisnik);
while ($row = $korisnik->fetch_assoc()) {
    $idKorisnika = $row["idkorisnici"];
    $idTipKorisnika = $row["tipkorisnika_idtipkorisnika"];
}

$naziv = "";
$opis = "";
$trajanje = "";
$lokacijaZvuka = "";
$idPjesma = "";
$idTermina = "";

if(isset($_POST["submit"])) {
    $idPjesma = $_POST["pjesma"];
    $idTermina = $_POST["termin"];
    $sql_upit_pjesma_odabrana = "SELECT * FROM pjesme WHERE idpjesme LIKE '" . $idPjesma . "'";
    $odabranaPjesma = $db->selectDB($sql_upit_pjesma_odabrana);
    while ($row = $odabranaPjesma->fetch_assoc()) {
        $naziv = $row["naziv"];
        $opis = $row["opis"];
        $trajanje = $row["trajanje"];
        $lokacijaZvuka = $row["lokacija_zvuka"];
    }
    $datumZahtjeva = date("Y-m-d H:i:s");
    
    
    $sql_novi_zahtjev = "INSERT INTO `zahtjevi`(`naziv`, `opis`, `trajanje`, `lokacija_zvuka`, `status`, `korisnici_idkorisnici`, `korisnici_tipkorisnika_idtipkorisnika`, `datumivrijeme`, `tipovizahtjeva_idtipovizahtjeva`) VALUES('" . $naziv . "', '" . $opis . "', '" . $trajanje . "', '" . $lokacijaZvuka . "', '0', '" . $idKorisnika . "', '" . $idTipKorisnika . "', '" . $datumZahtjeva . "', '1')";
    $noviZahtjev = $db->selectDB($sql_novi_zahtjev);
    $sql_nova_pjesma_termin = "INSERT INTO `pjesme_terminiemitiranja`(`pjesme_idpjesme`, `terminiemitiranja_idterminiemitiranja`) VALUES('" . $idPjesma . "', '" . $idTermina . "')";
    $noviZahtjevTermin = $db->selectDB($sql_nova_pjesma_termin);
    $sql_dnevnik = "INSERT INTO `dnevnik`(`skripta`, `vrijeme`, `opis`) VALUES('Novi zahtjev ', '" . $datumZahtjeva . "', 'Podnesen zahtjev za novom glazbenom željom')";
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
        <link rel="stylesheet" type="text/css" href="../css/denbudins.css">
        <title>Novi zahtjev za glazbenom željom</title>
    </head>
    <body>
        <header>
            <?php
                include("zaglavlje.php");
            ?>
        </header>
        <form class="form-login" id="noviOglas" method="POST" action="#file" enctype="multipart/form-data">
                    <div class="form-row">
                        <input type="hidden" id="sifraVrste" name="sifraVrste" value="<?php echo$idVrste; ?>"
                    </div>
                    <div class="form-row">
                        <h4>Pjesma:</h4>
                        <?php echo$selected1; ?>
                    </div>
                    <div class="form-row">
                        <h4>Termin emitiranja:</h4>
                        <?php echo$selected2; ?>
                    </div>
                    <div class="form-row">
                        <input id="submit" class="button" type="submit" name="submit" value="POŠALJI ZAHTJEV"/>
                    </div>
        </form>

    </body>
</html>