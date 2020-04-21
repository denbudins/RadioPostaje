<?php
include("../sesija.class.php");

Sesija::kreirajSesiju();

if (isset($_SESSION["korisnik"])) {
    if ($_SESSION["uloga"] == 'Moderator') {
        header("Location: ../mod/index.php");
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
            <?php
                include("zaglavlje.php");
            ?>
        </header>
        <main>
            <h2 class="naslovna-text">Dobro došli u korisnički dio aplikacija!</h2>
            <div class="naslovna-box">
                <img src="../image/radio.jpg" alt="Ikona radija" class="naslovna-box__icon">
                <h3 class="naslovna-text">Sa jednog mijesta utječite na program Vaših omiljnih radio postaja.</h3>
            </div>   
        </main>
    </body>
</html>