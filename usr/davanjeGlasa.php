<?php

include("../sesija.class.php");
include("../baza.class.php");

Sesija::kreirajSesiju();

if (isset($_SESSION["korisnik"])) {
    if ($_SESSION["uloga"] == 'Moderator') {
        header("Location: ../mod/index.php");
    }
    if ($_SESSION["uloga"] == 'Administrator') {
        header("Location: ../admin/index.php");
    }
}

$id = "";
$korisnik = "";
$termin = "";
$db = new Baza();
$db->spojiDB();

if (isset($_GET["id"]) && isset($_GET["korisnik"]) && isset($_GET["termin"])) {
    $id = $_GET["id"];
    $korisnik = $_GET["korisnik"];
    $termin = $_GET["termin"];
}

$sql_upit_termin_pjesma = "SELECT broj_glasova, pjesme_idpjesme FROM pjesme_terminiemitiranja WHERE id = '" . $id . "'";
$termin_pjesma = $db->selectDB($sql_upit_termin_pjesma);
if (mysqli_num_rows($termin_pjesma) > 0) {
    $row = mysqli_fetch_row($termin_pjesma);
    $brojGlasova = $row[0];
    $pjesma = $row[1];
    $brojGlasova++;
    $sql_povecaj_broj_glasova = "UPDATE pjesme_terminiemitiranja SET broj_glasova = '". $brojGlasova ."' WHERE id = '" . $id . "'";
    $danGlasPjesmi = $db->selectDB($sql_povecaj_broj_glasova);
    $sql_glasanje = "INSERT INTO `glasovanje`(`termin`, `korisnik`) VALUES('" . $termin . "', '" . $korisnik ."')";
    $noviZapisGlasanja = $db->selectDB($sql_glasanje);
    $datum = date("Y-m-d H:i:s");
    $sql_dnevnik = "INSERT INTO `dnevnik`(`skripta`, `vrijeme`, `opis`) VALUES('Davanje glasa pjesmi', '" . $datum . "', 'Korisnik ". $korisnik." je dao glas za pjesmu " . $pjesma ."')";
    $noviZapis = $db->selectDB($sql_dnevnik);
    header("Location: glasnja.php");
} else {
    header("Location: glasnja.php");
}

$db->zatvoriDB();
