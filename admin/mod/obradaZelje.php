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

$idZahtjava = "";
$akcija = "";
$db = new Baza();
$db->spojiDB();

if (isset($_GET["id"]) && isset($_GET["akcija"])) {
    $idZahtjava = $_GET["id"];
    $akcija = $_GET["akcija"];
}

$sql_upit_korisnik = "SELECT * FROM zahtjevi WHERE idzahtjevi LIKE '" . $idZahtjava . "'";
$korisnik = $db->selectDB($sql_upit_korisnik);
if (mysqli_num_rows($korisnik) > 0) {
    if ($akcija == '1') {
        $sql_obrada_zahtjeva = "UPDATE zahtjevi SET status = '4' WHERE idzahtjevi LIKE '" . $idZahtjava . "'";
        $obradiZahtjev = $db->selectDB($sql_obrada_zahtjeva);
        $datum = date("Y-m-d H:i:s");
        $sql_zahtjev_zapisnik = "INSERT INTO `obradeniZahtjevi`(`datumIVrijeme`, `status`, `idZahtjeva`) VALUES('" . $datum . "', '1', '" . $idZahtjava . "')";
        $noviZapisnik = $db->selectDB($sql_zahtjev_zapisnik);
        $sql_dnevnik = "INSERT INTO `dnevnik`(`skripta`, `vrijeme`, `opis`) VALUES('Obrada zahtjeva glazbene želje', '" . $datum . "', 'Zahtjev " . $idZahtjava . " je odobren od moderatora')";
        $noviZapis = $db->selectDB($sql_dnevnik);
        header("Location: glazbeneZelje.php");
    }
    if ($akcija == '2') {
        $sql_obrada_zahtjeva = "UPDATE zahtjevi SET status = '3' WHERE idzahtjevi LIKE '" . $idZahtjava . "'";
        $obradiZahtjev = $db->selectDB($sql_obrada_zahtjeva);
        $datum = date("Y-m-d H:i:s");
        $sql_zahtjev_zapisnik = "INSERT INTO `obradeniZahtjevi`(`datumIVrijeme`, `status`, `idZahtjeva`) VALUES('" . $datum . "', '1', '" . $idZahtjava . "')";
        $noviZapisnik = $db->selectDB($sql_zahtjev_zapisnik);
        $sql_dnevnik = "INSERT INTO `dnevnik`(`skripta`, `vrijeme`, `opis`) VALUES('Obrada zahtjeva glazbene želje', '" . $datum . "', 'Zahtjev " . $idZahtjava . " je odbijen od moderatora')";
        $noviZapis = $db->selectDB($sql_dnevnik);
        header("Location: glazbeneZelje.php");
    }
}

$db->zatvoriDB();
