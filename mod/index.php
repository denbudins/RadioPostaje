<?php
include("../sesija.class.php");

Sesija::kreirajSesiju();

if (isset($_SESSION["korisnik"])) {
    if ($_SESSION["uloga"] == 'Registriran korisnik') {
        header("Location: ../usr/index.php");
    }
    if ($_SESSION["uloga"] == 'Administrator') {
        header("Location: ../admin/index.php");
    }
}

?>

<!DOCTYPE html>
<html lang="hr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="naslov" content="Početna stranica" />
        <meta name="kljucne_rijeci" content="projekt, početna" />
        <meta name="datum_izrade" content="24.11.2019." />
        <meta name="autor" content="Denis Martin Budinski" />
        <link rel="stylesheet" type="text/css" href="../css/denbudins.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <title>Naslovnica</title>
    </head>
    <body>
        <header>
            <section class="header">
                <nav class="navigacija">
                    <a href="index.php" class="navigacija__link">Naslovna</a>
                    <a href="indexMod.php" class="navigacija__link">Pogled moderatora</a>
                    <a href="usr/index.php" class="navigacija__link">Pogled korisnika</a>
                    <a href="pregledKategorija.php" class="navigacija__link">Kategorija pjesama</a>
                    <a href="pregledePjesama.php" class="navigacija__link">Pjesama</a>
                    <a id="odjava" href="odjava.php" class="navigacija__link">Odjava</a>
                </nav>
        </header>
    </body>
</html>