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

$naziv = "";
$opis = "";

if(isset($_POST["submit"])) {
    $naziv = $_POST["naziv"];
    $opis = $_POST["opis"];
    if ($naziv != "" AND $opis != ""){
    $sql_nova_kategorija= "INSERT INTO `kategorijepjesama`(`naziv`, `opis`, `status`) VALUES('" . $naziv . "', '" . $opis . "', '1')";
    $unosKategorije = $db->selectDB($sql_nova_kategorija);

    $datum = date("Y-m-d H:i:s");
    $sql_dnevnik = "INSERT INTO `dnevnik`(`skripta`, `vrijeme`, `opis`) VALUES('Nova kategorija za pjesmeu', '" . $datum . "', 'Dodana nova kategorija pjesme " . $naziv . "')";
    $noviZapis = $db->selectDB($sql_dnevnik);
    header("Location: indexAdmin.php");
    }else{
        echo "Pogreška";
    }
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
        <script type="text/javascript" src="../js/denbudins.js"></script>
        <title>Nova kategorija pjesama</title>
    </head>
    <body>
    <?php
        include("zaglavlje.php");
    ?>
        <form class="form-login" name="dodavanjeKategorije" id="novaKategorija" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <div class="form-log-in-with-email">
                <div class="form-white-background">
                    <div class="form-title-row">
                        <h1>Nova kategorija pjesama</h1>
                    </div>
                    <div class="form-row">
                        <input id="naziv" type="text" name="naziv" placeholder="Naziv kategorije"/>
                    </div>
                    <div class="form-row">
                        <input id="opis" type="text" name="opis" placeholder="Opis"/>
                    </div>
                    <div class="form-row">
                        <input id="submit" class="button" onclick="return IsEmpty();" type="submit" name="submit" value="KREIRAJ KATEGORIJU"/>
                    </div>
                </div>
            </div>
        </form>

    </body>
</html>