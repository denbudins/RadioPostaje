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

$idZahtjeva = "";
$akcija = "";
$db = new Baza();
$db->spojiDB();

if (isset($_GET["id"]) && isset($_GET["akcija"])) {
    $idZahtjeva = $_GET["id"];
    $akcija = $_GET["akcija"];
}

if ($akcija == '1') {
    $sql_nadi_zahtjev = "SELECT * FROM zahtjevi WHERE idzahtjevi = '" . $idZahtjeva . "'";
    $zahtjevi = $db->selectDB($sql_nadi_zahtjev);
    if (mysqli_num_rows($zahtjevi) > 0) {
        while ($row = $zahtjevi->fetch_assoc()) {
            $idPjesme = $row["idPjesme"];
            $idTerminEmitiranja = $row["terminiemitiranja_idterminiemitiranja"];
        }
    }
    $sql_nova_pjesma = "INSERT INTO `pjesme_terminiemitiranja`(`pjesme_idpjesme`, `terminiemitiranja_idterminiemitiranja`, `broj_glasova`) VALUES('" . $idPjesme . "', '" . $idTerminEmitiranja . "', '0')";
    $dodajPjesmu = $db->selectDB($sql_nova_pjesma);
    $sql_obrada_zahtjeva = "UPDATE zahtjevi SET status = '5' WHERE idzahtjevi LIKE '" . $idZahtjeva . "'";
    $obradiZahtjev = $db->selectDB($sql_obrada_zahtjeva);
    $datum = date("Y-m-d H:i:s");
    $sql_dnevnik = "INSERT INTO `dnevnik`(`skripta`, `vrijeme`, `opis`) VALUES('Dodana pjesma u termin emitiranja', '" . $datum . "', 'Pjesma " . $idPjesme . " je dodana u termin emitiranja " . $idTerminEmitiranja . "')";
    $noviZapis = $db->selectDB($sql_dnevnik);
    header("Location: pjesmaTerminEmitiranja.php");
}

$db->zatvoriDB();
