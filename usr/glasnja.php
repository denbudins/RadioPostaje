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

$db = new Baza();
$db->spojiDB();

$sql_upit_radio_postaja = "SELECT idradiopostaje, naziv FROM radiopostaje";
$radioPostaja = $db->selectDB($sql_upit_radio_postaja);
$selected3 = "<select id='postaja' name='postaja'>";
while ($row = mysqli_fetch_array($radioPostaja)) {
    $prikaz = $row['naziv'];
    $selected3 .= "<option value='" . $row['idradiopostaje'] . "'>" . $prikaz . "</option>";
}
$selected3 .= "</select>";

$sql_upit_dnevnik = "SELECT t1.*, t3.naziv As naziv_kategorije FROM pjesme t1 LEFT JOIN pjesme_kategorije t2 ON t1.idpjesme = t2.pjesme_idpjesme LEFT JOIN kategorijepjesama t3 ON t2.kategorijepjesama_idkategorijepjesama = t3.idkategorijepjesama";
$dnevnik = $db->selectDB($sql_upit_dnevnik);

$head = "<thead>" . "<tr>" . "<th>Redni broj</th>" . "<th>Naziv pjesme</th>" . "<th>Opis pjesme</th>" . "<th>Trajenje pjesme</th>". "<th>Glasanje</th>". "</tr>" . "</thead>";
$table = "";

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
        <link rel="stylesheet" type="text/css" href="../css/denbudins.css">
        <script type="text/javascript" src="https://cdn.datatables.net/s/dt/jq-2.1.4,dt-1.10.10/datatables.min.js"></script>
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
        <script type="text/javascript" src="../js/denbudins.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#tablicaPjesama').DataTable();
            });
        </script>
        <title>Pregled pjesama</title>
    </head>
    <body>        
        <?php
            include("zaglavlje.php");
        ?>
        <div class="form-row">
            <h4>Radio postaja:</h4>
            <?php echo$selected3; ?>
        </div>
        <div class="form-row">
            <h4>Termin emitiranja:</h4>
            <selected id='termini' name='termini'></selected>
        </div>
        <p style="padding-top: 1%;"></p>
        <table id="tablicaPjesama" class="display">
            <?php
            echo $head;
            ?>
            <tbody id='tjeloTablice'>
            </tbody>
        </table>
    </body>
</html>