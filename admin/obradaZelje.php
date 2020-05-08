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
            $naziv = $row["naziv"];
            $opis = $row["opis"];
            $trajanje = $row["trajanje"];
            $lokacija_zvuka = $row["lokacija_zvuka"];
        }
    }
    $sql_nova_pjesma = "INSERT INTO `pjesme`(`naziv`, `opis`, `trajanje`, `lokacija_zvuka`, `status`) VALUES('" . $naziv . "', '" . $opis . "', '". $trajanje ."', '" . $lokacija_zvuka . "', '1')";
    $dodajPjesmu = $db->selectDB($sql_nova_pjesma);
    $sql_dodana_pjesma = "SELECT idpjesme FROM `pjesme` ORDER BY idpjesme DESC LIMIT 1";
    $dodana_pjesma = $db->selectDB($sql_dodana_pjesma);
    if (mysqli_num_rows($dodana_pjesma) > 0) {
        while ($row = $dodana_pjesma->fetch_assoc()) {
            $idPjesme = $row["idpjesme"];
        }
    }
    $sql_status_zahtjeva = "UPDATE zahtjevi SET tipovizahtjeva_idtipovizahtjeva = '1', `idPjesme` = '" . $idPjesme . "'  WHERE idzahtjevi = '" . $idZahtjeva . "'";
    $promjeniStatusZahtjeva = $db->selectDB($sql_status_zahtjeva);
    $datum = date("Y-m-d H:i:s");
    $sql_dnevnik = "INSERT INTO `dnevnik`(`skripta`, `vrijeme`, `opis`) VALUES('Odobren zahtjev za novom pjesmom', '" . $datum . "', 'Zahtjev " . $idZahtjeva . " je odobren od administratora')";
    $noviZapis = $db->selectDB($sql_dnevnik);
    header("Location: povezivanjeKategorijeSaPjesmom.php?id='" . $idPjesme  . "'");
}
if ($akcija == '2') {
    $sql_status_zahtjeva = "UPDATE zahtjevi SET status = '3' WHERE idzahtjevi = '" . $idZahtjeva . "'";
    $promjeniStatusZahtjeva = $db->selectDB($sql_status_zahtjeva);
    $datum = date("Y-m-d H:i:s");
    $sql_dnevnik = "INSERT INTO `dnevnik`(`skripta`, `vrijeme`, `opis`) VALUES('Odbijen zahtjev za novom pjesmom', '" . $datum . "', 'Zahtjev " . $idZahtjeva . " je odbijen od administratora')";
    $noviZapis = $db->selectDB($sql_dnevnik);
    header("Location: novaPjesma.php");
}

$db->zatvoriDB();
