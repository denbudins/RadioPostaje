<?php
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
?>
<!DOCTYPE html>
<html lang="hr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="naslov" content="Početna stranica" />
        <meta name="kljucne_rijeci" content="projekt, početna, radio postaje" />
        <meta name="datum_izrade" content="17.10.2019." />
        <meta name="autor" content="Denis Martin Budinski" />
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css"/>
        <link rel="stylesheet" type="text/css" href="css/denbudins.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.js"></script>
        <script>
            window.addEventListener("load", function () {
                window.cookieconsent.initialise({
                    "palette": {
                        "popup": {
                            "background": "#fde428",
                            "text": "#002e5b"
                        },
                        "button": {
                            "background": "#002e5b",
                            "text": "#ffffff"
                        }
                    },
                    "theme": "classic",
                    "content": {
                        "message": "Ova stranica koristi kolačiće kako bi vam osigurala što je moguće bolje iskustvo.",
                        "dismiss": "Prihvaćam",
                        "link": "Prihvaćam",
                        "href": "http://barka.foi.hr/WebDiP/2018_projekti/WebDiP2018x018",
                    }
                })
            });
        </script>
        <title>Naslovnica</title>
    </head>
    <body>
        <?php
            include("zaglavlje.php");
        ?>
        <main>
            <section class="pocetna-main">
                <h2 class="pocetna-main__naslov">Dobro došli na stranicu za <span>upravljanje radio postajama</span></h2>
                <div class="box-korisnici">
                    <h3 class="box-korisnici__naslov">Naši zadovoljni korisnici</h3>
                    <div class="box-logo">
                        <figure class="box-logo__slike">
                            <img src="image/Narodni-main-logo.png" alt="Narodni radio">
                            <figcaption>Narodni radio</figcaption>
                        </figure>
                        <figure class="box-logo__slike">
                            <img src="image/antena.png" alt="Antena">
                            <figcaption>Antena</figcaption>
                        </figure>
                        <figure class="box-logo__slike">
                            <img src="image/otvoreni.jpg" alt="Otvoreni radio">
                            <figcaption>Otvoreni radio</figcaption>
                        </figure>
                        <figure class="box-logo__slike">
                            <img src="image/Radio_Krizevci.jpg" alt="Radio Križevci">
                            <figcaption>Radio Križevci</figcaption>
                        </figure>
                    </div>
                </div>
            </section>
        </main>
        <footer class="stranica-footer">
            <div class="stranica-footer__projekt">
                <p>Autor projekta: <span>Denis Martin Budinski</span></p>
                <p>Naziv projekta: <span>WebDiP 2018/2019 Radio postaje</span></p>
            </div>
            <div class="stranica-footer__linkovi">
             <div><a href="dokumentacija.html">Dokumentacija projekta</a></div>
             <div><a href="o_autoru.html">O autoru</a></div> 
            </div>
        </footer>
    </body>
</html>
