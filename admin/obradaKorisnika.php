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
    if ($akcija == '0') {
        $sql_otkljucavanje_korisnika = "UPDATE korisnici SET status = '0' WHERE idkorisnici LIKE '" . $idKorisnika . "'";
        $otkljucanKorisnik = $db->selectDB($sql_otkljucavanje_korisnika);
        $datum = date("Y-m-d H:i:s");
        $sql_dnevnik = "INSERT INTO `dnevnik`(`skripta`, `vrijeme`, `opis`) VALUES('Zaključavanje računa', '" . $datum . "', '" . $_SESSION["korisnik"] . " je zaključao račun " . $idKorisnika . "')";
        $noviZapis = $db->selectDB($sql_dnevnik);
        header("Location: otkljucavanjeRacuna.php");
    }
    if ($akcija == '2') {
        $sql_otkljucavanje_korisnika = "UPDATE korisnici SET tipkorisnika_idtipkorisnika = '2' WHERE idkorisnici LIKE '" . $idKorisnika . "'";
        $otkljucanKorisnik = $db->selectDB($sql_otkljucavanje_korisnika);
        $datum = date("Y-m-d H:i:s");
        $sql_dnevnik = "INSERT INTO `dnevnik`(`skripta`, `vrijeme`, `opis`) VALUES('Dodana prava moderatora', '" . $datum . "', '" . $_SESSION["korisnik"] . " je korisniku " . $idKorisnika . " dao pravo moderatora')";
        $noviZapis = $db->selectDB($sql_dnevnik);
        header("Location: noviModerator.php");
    }
    if ($akcija == '3') {
        $sql_obrisi_moderatora = "DELETE FROM moderatori WHERE korisnici_idkorisnici LIKE '" . $idKorisnika . "'";
        $obrisiModeratora = $db->selectDB($sql_obrisi_moderatora);
        $sql_otkljucavanje_korisnika = "UPDATE korisnici SET tipkorisnika_idtipkorisnika = '1' WHERE idkorisnici LIKE '" . $idKorisnika . "'";
        $otkljucanKorisnik = $db->selectDB($sql_otkljucavanje_korisnika);
        $datum = date("Y-m-d H:i:s");
        $sql_dnevnik = "INSERT INTO `dnevnik`(`skripta`, `vrijeme`, `opis`) VALUES('Oduzeta prava moderatora', '" . $datum . "', '" . $_SESSION["korisnik"] . " je korisniku " . $idKorisnika . " oduzeo pravo moderatora')";
        $noviZapis = $db->selectDB($sql_dnevnik);
        header("Location: noviModerator.php");
    }
} else {
    header("Location: otkljucavanjeRacuna.php");
}

$db->zatvoriDB();
