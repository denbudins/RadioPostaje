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

$sql_upit_moderatori = "SELECT korisnici.idkorisnici, korisnici.ime, korisnici.prezime FROM korisnici WHERE tipkorisnika_idtipkorisnika LIKE '2'";
$moderatori = $db->selectDB($sql_upit_moderatori);
$selected1 = "<select id='mod' name='moderator'>";
while ($row = mysqli_fetch_array($moderatori)) {
    $prikaz = $row['ime'] . " " . $row["prezime"];
    $selected1 .= "<option value='" . $row['idkorisnici'] . "'>" . $prikaz . "</option>";
}
$selected1 .= "</select>";

$naziv = "";
$adresa = "";
$grad = "";
$web = "";
$idModeratora = "";
$idPostaje = "";

if(isset($_POST["submit"])) {
    $naziv = $_POST["naziv"];
    $adresa = $_POST["adresa"];
    $grad = $_POST["grad"];
    $web = $_POST["web"];
    $idModeratora = $_POST["moderator"];
    
    $sql_nova_postaja = "INSERT INTO `radiopostaje`(`naziv`, `adresa`, `grad`, `webadresa`) VALUES('" . $naziv . "', '" . $adresa . "', '" . $grad . "', '" . $web . "')";
    $unosPostaje = $db->selectDB($sql_nova_postaja);
    $sql_upit_postaja = "SELECT * FROM radiopostaje WHERE naziv LIKE '" . $naziv . "'";
    $postaja = $db->selectDB($sql_upit_postaja);
    while($row = $postaja->fetch_assoc()) {
        $idPostaje = $row["idradiopostaje"];
    }

    $sql_moderator_postaje = "INSERT INTO `moderatori`(`radiopostaje_idradiopostaje`, `korisnici_idkorisnici`, `korisnici_tipkorisnika_idtipkorisnika`, `status`) VALUES('" . $idPostaje . "', '" . $idModeratora . "', '2', '1')";
    $unosModeratora = $db->selectDB($sql_moderator_postaje);
    $datum = date("Y-m-d H:i:s");
    $sql_dnevnik = "INSERT INTO `dnevnik`(`skripta`, `vrijeme`, `opis`) VALUES('Nova radio postaja', '" . $datum . "', 'Dodana nova radio postaja " . $naziv . "')";
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
        <meta name="naslov" content="Nova radio postaja" />
        <meta name="kljucne_rijeci" content="projekt, početna" />
        <meta name="datum_izrade" content="26.10.2019." />
        <meta name="autor" content="Denis Martin Budinski" />
        <link rel="stylesheet" type="text/css" href="../css/denbudins.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/s/dt/jq-2.1.4,dt-1.10.10/datatables.min.css"/>
        <script type="text/javascript" src="https://cdn.datatables.net/s/dt/jq-2.1.4,dt-1.10.10/datatables.min.js"></script>
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
        <title>Nova radio postaja</title>
    </head>
    <body>
    <?php
        include("zaglavlje.php");
    ?>
        <form class="form-login" id="novaPostaja" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <div class="form-log-in-with-email">
                <div class="form-white-background">
                    <div class="form-title-row">
                        <h1>Nova radio postaja</h1>
                    </div>
                    <div class="form-row">
                        <input id="naziv" type="text" name="naziv" placeholder="Naziv radio postaje"/>
                    </div>
                    <div class="form-row">
                        <input id="adresa" type="text" name="adresa" placeholder="Adresa"/>
                    </div>
                    <input id="grad" type="text" name="grad" placeholder="Grad"/>
                    </div>
                    <div class="form-row">
                    <input id="web" type="text" name="web" placeholder="Web adresa"/>
                    </div>
                    <div class="form-row">
                        <h4>Moderator:</h4>
                        <?php echo$selected1; ?>
                    </div>
                    <div class="form-row">
                        <input id="submit" class="button" type="submit" name="submit" value="KREIRAJ"/>
                    </div>
                    <a href="dodavanjeModeratora.php" class="login-box__link">Već kreiran radio postaja? Dodaj moderatora &rarr;</a>
                </div>

            </div>
        </form>

    </body>
</html>