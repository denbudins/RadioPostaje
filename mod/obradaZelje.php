<?php

include("../sesija.class.php");
include("../baza.class.php");

Sesija::kreirajSesiju();

if (isset($_SESSION["korisnik"])) {
    if ($_SESSION["uloga"] == 'Registriran korisnik') {
        header("Location: ../usr/index.php");
    }
    if ($_SESSION["uloga"] == 'Administrator') {
        header("Location: ../admin/index.php");
    }
}

$idKorisnika = "";
$akcija = "";
$db = new Baza();
$db->spojiDB();

if (isset($_GET["id"]) && isset($_GET["akcija"])) {
    $idKorisnika = $_GET["id"];
    $akcija = $_GET["akcija"];
}

$sql_upit_korisnik = "SELECT * FROM korisnici WHERE idkorisnici LIKE '" . $idKorisnika . "'";
$korisnik = $db->selectDB($sql_upit_korisnik);
if (mysqli_num_rows($korisnik) > 0) {
    if ($akcija == '1') {
        $sql_otkljucavanje_korisnika = "UPDATE korisnici SET status = '1' WHERE idkorisnici LIKE '" . $idKorisnika . "'";
        $otkljucanKorisnik = $db->selectDB($sql_otkljucavanje_korisnika);
        $datum = date("Y-m-d H:i:s");
        $sql_dnevnik = "INSERT INTO `dnevnik`(`skripta`, `vrijeme`, `opis`) VALUES('Otključavanje računa', '" . $datum . "', '" . $_SESSION["korisnik"] . " je otključao račun " . $idKorisnika . "')";
        $noviZapis = $db->selectDB($sql_dnevnik);
        header("Location: otkljucavanjeRacuna.php");
    }
    if ($akcija == '2') {
        $sql_otkljucavanje_korisnika = "UPDATE korisnici SET status = '0' WHERE idkorisnici LIKE '" . $idKorisnika . "'";
        $otkljucanKorisnik = $db->selectDB($sql_otkljucavanje_korisnika);
        $datum = date("Y-m-d H:i:s");
        $sql_dnevnik = "INSERT INTO `dnevnik`(`skripta`, `vrijeme`, `opis`) VALUES('Zaključavanje računa', '" . $datum . "', '" . $_SESSION["korisnik"] . " je zaključao račun " . $idKorisnika . "')";
        $noviZapis = $db->selectDB($sql_dnevnik);
        header("Location: otkljucavanjeRacuna.php");
    }
}

$db->zatvoriDB();
