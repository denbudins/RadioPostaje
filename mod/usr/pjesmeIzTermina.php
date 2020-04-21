<?php
include("../../sesija.class.php");
include("../../baza.class.php");

Sesija::kreirajSesiju();

$db = new Baza();
$db->spojiDB();

$sql_upit_termin_pjesma = "SELECT t1.id,t1.terminiemitiranja_idterminiemitiranja, t2.naziv AS naziv_termina, t3.naziv AS naziv_pjesme, t3.trajanje FROM pjesme_terminiemitiranja t1 INNER JOIN terminiemitiranja t2 ON t1.terminiemitiranja_idterminiemitiranja = t2.idterminiemitiranja INNER JOIN pjesme t3 ON t1.pjesme_idpjesme = t3.idpjesme WHERE t2.radiopostaje_idradiopostaje = '". $_POST['salji1']."' AND t1.terminiemitiranja_idterminiemitiranja = '". $_POST['salji']."'";

$odgovor = array();
$result = $db->selectDB($sql_upit_termin_pjesma);
if (mysqli_num_rows($result)) {
    while ($row = mysqli_fetch_row($result)) {
        $redak["idterminiemitiranja"] = $row[1];
        $redak["naziv_termina"] = $row[2];
        $redak["naziv_pjesme"] = $row[3];
        $redak["trajanje"] = $row[4];
        $redak["id"] = $row[0];
        $redak["idKorisnik"] = $_SESSION["idkorisnik"];
        $odgovor[] = $redak;
    }
}

header("Content-Type: application/json");
print json_encode($odgovor);
?>