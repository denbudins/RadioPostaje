<?php
include("../../sesija.class.php");
include("../../baza.class.php");

Sesija::kreirajSesiju();

if (isset($_SESSION["korisnik"])) {
    if ($_SESSION["uloga"] == 'Registriran korisnik') {
        header("Location: ../usr/index.php");
    }
    if ($_SESSION["uloga"] == 'Administrator') {
        header("Location: ../admin/index.php");
    }
}

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

/*$sql_upit_dnevnik = "SELECT t1.id,t1.terminiemitiranja_idterminiemitiranja, t2.naziv AS naziv_termina, t3.naziv AS naziv_pjesme, t3.trajanje FROM pjesme_terminiemitiranja t1 INNER JOIN terminiemitiranja t2 ON t1.terminiemitiranja_idterminiemitiranja = t2.idterminiemitiranja INNER JOIN pjesme t3 ON t1.pjesme_idpjesme = t3.idpjesme WHERE t2.radiopostaje_idradiopostaje = '". $_POST['salji1']."' AND t1.terminiemitiranja_idterminiemitiranja = '". $_POST['salji']."'";
$dnevnik = $db->selectDB($sql_upit_dnevnik);*/

$head = "<thead class='table-section__zaglavlje'>" . "<tr>" . "<th>Redni broj</th>" . "<th>Naziv pjesme</th>" . "<th>Opis pjesme</th>" . "<th>Trajenje pjesme</th>". "<th>Glasanje</th>". "</tr>" . "</thead>";
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
        <link rel="stylesheet" type="text/css" href="../../css/denbudins.css">
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
        <main>
        <div class="form-row">
            <h4>Radio postaja:</h4>
            <?php echo$selected3; ?>
        </div>
        <div class="form-row">
            <h4>Termin emitiranja:</h4>
            <selected id='termini' name='termini'></selected>
        </div>
        <section class="table-section table-section_margin">
            <table id="tablica" class="display">
                <?php
                echo $head;
                ?>
                <tbody  class="table-section__tjelo" id="tjeloTablice">
                    <?php
                    echo $table;
                    ?>
                </tbody>
            </table>
        </section>
    <main>
    </body>
</html>