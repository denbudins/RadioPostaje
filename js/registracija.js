$(document).ready(function () {
    var simb1 = "!";
    var simb2 = "?";
    var simb3 = "#";
    $('#ime').keyup(function () {
        var ime = $("#ime").val();
        var zastavica = 0;
        if (ime.indexOf(simb1) != -1) {
            zastavica = 1;
        }
        if (ime.indexOf(simb2) != -1) {
            zastavica = 1;
        }
        if (ime.indexOf(simb3) != -1) {
            zastavica = 1;
        }
        if (zastavica === 0) {
            $('.button').prop('disabled', false)
            $("#ime").css("background-color", "green");
            //console.log('Sve u redu!');
        } else {
            $('.button').prop('disabled', true)
            $("#ime").css("background-color", "red");
            //console.log('Koristen nedozvoljeni simbol!');
        }
    });
    $('#prezime').keyup(function () {
        var prezime = $("#prezime").val();
        var zastavica = 0;
        if (prezime.indexOf(simb1) != -1) {
            zastavica = 1;
        }
        if (prezime.indexOf(simb2) != -1) {
            zastavica = 1;
        }
        if (prezime.indexOf(simb3) != -1) {
            zastavica = 1;
        }
        if (zastavica === 0) {
            $('.button').prop('disabled', false)
            $("#prezime").css("background-color", "green");
            console.log('Sve u redu!');
        } else {
            $('.button').prop('disabled', true)
            $("#prezime").css("background-color", "red");
            //console.log('Koristen nedozvoljeni simbol!');
        }
    });


    $('#korIme').keyup(function () {
        var zastavica = 0;
        var response = '';
        $.ajax({
            type: "GET",
            url: "korisnici.php",
            async: false,
            success: function (text) {
                response = text;
            }
        });
        var korisnickoIme = $("#korIme").val();
        var zastavica = response.indexOf(korisnickoIme);
        if(zastavica == -1) {
            $('.button').prop('disabled', false)
            $("#korIme").css("background-color", "green");
            //console.log('Korisnik ne postoji!');
        }
        else {
            $('.button').prop('disabled', true)
            $("#korIme").css("background-color", "red");
            //console.log('Korisnik vec postoji!');
        }
    });
    
    $('#email').keyup(function () {
        var email = $("#email").val();
        var reg = new RegExp(/^\w+([-+.'][^\s]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/);
        if (reg.test(email)) {
            $('.button').prop('disabled', false)
            $("#email").css("background-color", "green");
        } else {
            $('.button').prop('disabled', true)
            $("#email").css("background-color", "red");
        }
    });

    $('#lozinka2').keyup(function () {
        var lozinka1 = $("#lozinka1").val();
        var lozinka2 = $("#lozinka2").val();
        if (lozinka2 !== lozinka1) {
            $('.button').prop('disabled', true)
            $("#lozinka2").css("background-color", "red");
        } else {
            $('.button').prop('disabled', false)
            $("#lozinka2").css("background-color", "green");
        }
    });

});