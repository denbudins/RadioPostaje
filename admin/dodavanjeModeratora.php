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

$sql_upit_radio_postaje = "SELECT * FROM radiopostaje";
$radio_postaje = $db->selectDB($sql_upit_radio_postaje);
$selected1 = "<select id='postaja' name='postaja'>";
while ($row = mysqli_fetch_array($radio_postaje)) {
    $prikaz = $row['naziv'];
    $selected1 .= "<option value='" . $row['idradiopostaje'] . "'>" . $prikaz . "</option>";
}
$selected1 .= "</select>";

$sql_upit_moderatori = "SELECT korisnici.idkorisnici, korisnici.ime, korisnici.prezime FROM korisnici WHERE tipkorisnika_idtipkorisnika LIKE '2'";
$moderatori = $db->selectDB($sql_upit_moderatori);
$selected2 = "<select id='mod' name='moderator'>";
while ($row = mysqli_fetch_array($moderatori)) {
    $prikaz = $row['ime'] . " " . $row["prezime"];
    $selected2 .= "<option value='" . $row['idkorisnici'] . "'>" . $prikaz . "</option>";
}
$selected2 .= "</select>";

$idRadioPostaje = "";
$idModeratora = "";
$kontrola = 0;

if (isset($_POST["submit"])) {
    $idRadioPostaje = $_POST["postaja"];
    $idModeratora = $_POST["moderator"];

    $sql_upit_moderacija = "SELECT * FROM moderatori WHERE radiopostaje_idradiopostaje LIKE '" . $idRadioPostaje . "' AND korisnici_idkorisnici LIKE '" . $idModeratora . "'";
    $moderacija = $db->selectDB($sql_upit_moderacija);
    if (mysqli_num_rows($moderacija) > 0) {
        $kontrola = 1;
    } else {
        $datum = date("Y-m-d H:i:s");
        $sql_moderator_hotela = "INSERT INTO `moderatori`(`radiopostaje_idradiopostaje`, `korisnici_idkorisnici`, `korisnici_tipkorisnika_idtipkorisnika`, `datum_aktivacije`, `status`) VALUES('" . $idRadioPostaje . "', '" . $idModeratora . "', '2', '" . $datum . "', '1')";
        $unosModeratora = $db->selectDB($sql_moderator_hotela);
        $sql_dnevnik = "INSERT INTO `dnevnik`(`skripta`, `vrijeme`, `opis`) VALUES('Dodan moderator', '" . $datum . "', 'Dodan novi moderator radio postaji " . $idRadioPostaje . "')";
        $noviZapis = $db->selectDB($sql_dnevnik);
        header("Location: indexAdmin.php");
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
        <meta name="datum_izrade" content="22.12.2019." />
        <meta name="autor" content="Denis Martin Budinski" />
        <link rel="stylesheet" type="text/css" href="../css/denbudins.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/s/dt/jq-2.1.4,dt-1.10.10/datatables.min.css"/>
        <script type="text/javascript" src="https://cdn.datatables.net/s/dt/jq-2.1.4,dt-1.10.10/datatables.min.js"></script>
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
        <title>Dodavanje moderatora</title>
    </head>
    <body>
    <?php
        include("zaglavlje.php");
    ?>
        <div id="greske">
            <?php if($kontrola === 1) echo("<h4>Moderator već dodijeljen toj radio postaji</h4><br>"); ?>
        </div>
        <form class="form-login" id="noviModerator" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <div class="form-log-in-with-email">
                <div class="form-white-background">
                    <div class="form-title-row">
                        <h1>Dodjela moderatora</h1>
                    </div>
                    <div class="form-row">
                        <h4>Radio postaja:</h4>
                        <?php echo$selected1; ?>
                    </div>
                    <div class="form-row">
                        <h4>Moderator:</h4>
                        <?php echo$selected2; ?>
                    </div>
                    <div class="form-row">
                        <input id="submit" class="button" type="submit" name="submit" value="Dodaj"/>
                    </div>
                </div>
            </div>
        </form>
    </body>
</html>