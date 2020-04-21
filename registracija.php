<?php
include("sesija.class.php");
include("baza.class.php");

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

$ime = "";
$prezime = "";
$korisnickoIme = "";
$email = "";
$lozinka1 = "";
$lozinka2 = "";
$aktivacijskiLink = "";
$brojGresaka = 0;
$povratnaInformacija = "";
$db = new Baza();
$db->spojiDB();

if(isset($_POST["submit"])) {
    $ime = $_POST["ime"];
    $prezime = $_POST["prezime"];
    $korisnickoIme = $_POST["korIme"];
    $email = $_POST["email"];
    $lozinka1 = $_POST["lozinka1"];
    $lozinka2 = $_POST["lozinka2"];
    //Provjera spama CAPTCHA
    $captcha = $_POST["g-recaptcha-response"];
    $secretKey = "6LfGmOQUAAAAACdKFUOAt3hjZ4gYNd0aQ-_E9HlD";
    $ip = $_SERVER['REMOTE_ADDR'];
    $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) .  '&response=' . urlencode($captcha);
    $response = file_get_contents($url);
    $responseKeys = json_decode($response,true);
    if(!($responseKeys["success"])) {
        $brojGresaka++;
    }
    //Ukoliko nisu sva polja ispunjena
    if ($ime === '' || $prezime === '' || $korisnickoIme === '' || $email === '' || $lozinka1 === '' || $lozinka2 === '') {
        $brojGresaka++;
    }
    //Ukoliko su koristeni nedozvoljeni simboli
    if ((strpos($ime, "'") !== false) || (strpos($prezime, "'") !== false) || (strpos($korisnickoIme, "'") !== false) || (strpos($email, "'") !== false) || (strpos($lozinka1, "'") !== false) || (strpos($lozinka2, "'") !== false) || (strpos($ime, "!") !== false) || (strpos($prezime, "!") !== false) || (strpos($korisnickoIme, "!") !== false) || (strpos($email, "!") !== false) || (strpos($lozinka1, "!") !== false) || (strpos($lozinka2, "!") !== false) || (strpos($ime, "?") !== false) || (strpos($prezime, "?") !== false) || (strpos($korisnickoIme, "?") !== false) || (strpos($email, "?") !== false) || (strpos($lozinka1, "?") !== false) || (strpos($lozinka2, "?") !== false) || (strpos($ime, "#") !== false) || (strpos($prezime, "#") !== false) || (strpos($korisnickoIme, "#") !== false) || (strpos($email, "#") !== false) || (strpos($lozinka1, "#") !== false) || (strpos($lozinka2, "#") !== false)) {
        $brojGresaka++;
    }
    //Ukoliko mail nije dobrog formata
    if (preg_match("/[a-z0-9]+[_a-z0-9\.-]*[a-z0-9]+@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})/", $email) === false) {
        $brojGresaka++;
    }
    //Ukoliko lozinke nisu jednake
    if ($lozinka1 !== $lozinka2) {
        $brojGresaka++;
    }
    //Provjera korisnickog imena postoji li u bazi
    $sql_provjera_korimena = "SELECT * FROM korisnici WHERE username LIKE '" . $korisnickoIme . "'";
    $rezultatProvjereKorimena = $db->selectDB($sql_provjera_korimena);
    if(mysqli_num_rows($rezultatProvjereKorimena) > 0){
        $brojGresaka++;
    } 
    //Provjera emaila postoji li u bazi
    $sql_provjera_emaila = "SELECT * FROM korisnici WHERE email LIKE '" . $email . "'";
    $rezultatProvjereEmaila = $db->selectDB($sql_provjera_emaila);
    if(mysqli_num_rows($rezultatProvjereEmaila) > 0){
        $brojGresaka++;
    } 
    //Uspjesna registracija
    if($brojGresaka === 0) {
        $sol = sha1(time());
        $kriptiranaLozinka = sha1($sol . "--" . $lozinka1);
        $opisAkcije = "Korisnik " . $korisnickoIme . " se registrirao";
        $datumRegistracije = date("Y-m-d H:i:s");
        $sql_novi_korisnik = "INSERT INTO `korisnici`(`username`, `email`, `lozinka`, `ime`, `prezime`, `tipkorisnika_idtipkorisnika`, `status`, `brojGresaka`, `kriptiranaLozinka`) VALUES('" . $korisnickoIme . "', '" . $email . "', '" . $lozinka1 . "', '" . $ime . "', '" . $prezime . "', '1', '0', '0', '". $kriptiranaLozinka ."')";
        $zapisivanjeNovogKorisnika = $db->selectDB($sql_novi_korisnik);
        $sql_dnevnik = "INSERT INTO `dnevnik`(`skripta`, `vrijeme`, `opis`) VALUES('Registracija', '" . $datumRegistracije . "', '" . $opisAkcije . "')";
        $dnevnikZapis = $db->selectDB($sql_dnevnik);
        $aktivacijskiLink = "<a href='http://barka.foi.hr/WebDiP/2018_projekti/WebDiP2018x018/aktivacija.php?korIme=" . $korisnickoIme . "&kod=" . $kriptiranaLozinka . ">Aktivirajte svoj racun</a>";
        $mail_to = $email;
        $mail_from = "From: WebDiP2018x018";
        $mail_subject = "Aktivacija registracije";
        $mail_body = "Pritisnite na iduci link kako biste aktivirali vas racun: " . $aktivacijskiLink;
        if (mail($mail_to, $mail_subject, $mail_body, $mail_from)) {
            $povratnaInformacija = "Uspjesno ste registrirali vas racun!" . "<br>";
        }
    }else {
        $povratnaInformacija = "Problem kod registracije korisnickog racuna!" . "<br>";
    }
}



$db->zatvoriDB();
?>

<!DOCTYPE html>
<html lang="hr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="naslov" content="Registracija" />
        <meta name="kljucne_rijeci" content="projekt, početna, radio postaje, registracija" />
        <meta name="datum_izrade" content="18.10.2019." />
        <meta name="autor" content="Denis Martin Budinski" />
        <link rel="stylesheet" type="text/css" href="css/denbudins.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script src='https://www.google.com/recaptcha/api.js'></script>
        <script type='text/javascript' src='http://code.jquery.com/jquery.min.js'></script>
        <script src="js/registracija.js"></script>
        <title>Registracija korisnika</title>
    </head>
    <body>
        <header>
            <?php
            include("zaglavlje.php");
            ?>
        </header>
        <div id="greske">
            <?php 
                if(isset($povratnaInformacija)) {
                    echo $povratnaInformacija."<br>";
                }
                elseif(isset($aktivacijskiLink)) {
                    echo $aktivacijskiLink."<br>";
                }
            ?>
        </div>
        <form class="form-login" id="prijava" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <div class="login-box">
                <div class="login-box__naslov">
                    <h3>Registracija korisnika</h3>
                </div>
                <div>
                    <input id="ime" type="text" name="ime" placeholder="Ime"/>
                </div>
                <div>
                    <input id="prezime" type="text" name="prezime" placeholder="Prezime"/>
                </div>
                <div>
                    <input id="korIme" type="text" name="korIme" placeholder="Korisničko ime"/>
                </div>
                <div>
                    <input id="email" type="text" name="email" placeholder="Email"/>
                </div>
                <div>
                    <input id="lozinka1" type="password" name="lozinka1" placeholder="Lozinka"/>
                </div>
                <div>
                    <input id="lozinka2" type="password" name="lozinka2" placeholder="Ponovljena lozinka"/>
                </div>
                <div class="g-recaptcha" data-sitekey="6LfGmOQUAAAAAHxcJX5Ffu0JPd5mCgZBoe9x8j7T"></div>
                <div>
                    <input id="submit" class="button" type="submit" name="submit" value="REGISTRACIJA"/>
                </div>
                    <a href="prijava.php" class="login-box__link">Imaš račun? Prijavi se</a>
            </div>
        </form>

    </body>
</html>