<?php
include("../../baza.class.php");

$db = new Baza();
$db->spojiDB();

$sql_upit_termin = "SELECT terminiemitiranja.idterminiemitiranja, terminiemitiranja.naziv FROM terminiemitiranja WHERE radiopostaje_idradiopostaje = '". $_POST['salji']."'";

$odgovor = array();
$result = $db->selectDB($sql_upit_termin);
if (mysqli_num_rows($result)) {
    while ($row = mysqli_fetch_row($result)) {
        $redak["idterminiemitiranja"] = $row[0];
        $redak["naziv"] = $row[1];
        $odgovor[] = $redak;
    }
}

header("Content-Type: application/json");
print json_encode($odgovor);
?>