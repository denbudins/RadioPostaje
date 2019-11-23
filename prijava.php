<?php
include("baza.class.php");
include("sesija.class.php");

Sesija::kreirajSesiju();

if (isset($_SESSION["korisnik"])) {
    if ($_SESSION["uloga"] == 'Registriran korisnik') {
        header("Location: usr/index.php");
    }
    if ($_SESSION["uloga"] == 'Moderator') {
        header("Location: mod/index.php");
    }
    if ($_SESSION["uloga"] == 'Administrator') {
        header("Location: admin/index.php");
    }
}

$korisnickoIme = "";
$lozinka = "";
$ulogaId = "";
$tipKorisnika = "";
$brojGresaka = "";
$kontrola = 0;
$db = new Baza();
$db->spojiDB();

if(isset($_POST["submit"])) {
    $korisnickoIme = $_POST["korIme"];
    $lozinka = $_POST["lozinka"];
    
    $sql_korisnik = "SELECT * FROM korisnici WHERE username LIKE '" . $korisnickoIme . "' AND lozinka LIKE '" . $lozinka . "'";
    $korisnik = $db->selectDB($sql_korisnik);
    if(mysqli_num_rows($korisnik) > 0) {
        while($row = $korisnik->fetch_assoc()) {
            $ulogaId = $row["tipkorisnika_idtipkorisnika"];
        }
        if($ulogaId == '1') {
            $tipKorisnika = "Registriran korisnik";
        }
        if($ulogaId == '2') {
            $tipKorisnika = "Moderator";
        }
        if($ulogaId == '3') {
            $tipKorisnika = "Administrator";
        }
        Sesija::kreirajKorisnika($korisnickoIme, $tipKorisnika, '1');
        setcookie('Prijava', $korisnickoIme, time()+3600);
        $timestamp = date("Y-m-d H:i:s");
        $akcija = "Prijava";
        $opis = "Korisnik " . $korisnickoIme . " se prijavio";
        $sql_dnevnik = "INSERT INTO `dnevnik`(`akcija`, `datumVrijeme`, `opis`) VALUES('" . $akcija . "', '" . $timestamp . "', '" . $opis . "')";
        $insert = $db->selectDB($sql_dnevnik);
        header("Location: index.php");
    }
    else {
        $sql_upit_greske = "SELECT * FROM korisnik WHERE korisnicko_ime LIKE '" . $korisnickoIme . "' AND tip_korisnika_ID_tip_korisnika NOT LIKE '4'";
        $rezultat = $db->selectDB($sql_upit_greske);
        if(mysqli_num_rows($rezultat) > 0) {
            while($row = $rezultat->fetch_assoc()) {
                $brojGresaka = $row["brojGresaka"];
            }
            $brojGresaka = $brojGresaka + 1;
            $sql_pogresna_prijava = "UPDATE korisnik SET brojGresaka = '" . $brojGresaka . "' WHERE korisnicko_ime LIKE '" . $korisnickoIme . "'";
            $izmjena = $db->selectDB($sql_pogresna_prijava);
            $kontrola = 1;
        }
    }
}

$db->zatvoriDB();
?>

<!DOCTYPE html>
<html lang="hr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="naslov" content="Početna stranica" />
        <meta name="kljucne_rijeci" content="projekt, početna" />
        <meta name="datum_izrade" content="21.10.2019." />
        <meta name="autor" content="Denis Martin Budinski" />
        <title>Prijava</title>
    </head>
    <body>
        <header>
            <?php
            include("zaglavlje.php");
            ?>
        </header>
        <div id="greske">
            <?php if($kontrola === 1) echo("<h4>Pogrešna prijava</h4><br>"); ?>
        </div>
        <form class="form-login" id="prijava" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <div class="form-log-in-with-email">
                <div class="form-white-background">
                    <div class="form-title-row">
                        <h1>Prijava</h1>
                    </div>
                    <div class="form-row">
                        <input id="korIme" type="text" name="korIme" placeholder="Korisničko ime"/>
                    </div>
                    <div class="form-row">
                        <input id="lozinka" type="password" name="lozinka" placeholder="Lozinka"/>
                    </div>
                    <div class="form-row">
                        <input id="submit" class="button" type="submit" name="submit" value="PRIJAVA"/>
                    </div>
                    <a href="zabLozinka.php" class="form-forgotten-password">Zaboravljena lozinka? &middot;</a>
                    <a href="registracija.php" class="form-create-an-account">Registriraj se &rarr;</a>
                </div>

            </div>
        </form>

    </body>
</html>