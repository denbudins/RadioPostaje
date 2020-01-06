function IsEmpty() {
    if (document.forms['dodavanjeKategorije'].naziv.value === "") {
      alert("Naziv kategorije je obavezno polje!");
      return false;
    }else if(document.forms['dodavanjeKategorije'].opis.value === ""){
        alert("Opis kategorije je obavezno polje!");
        return false;
    }
    return true;
  }

$(function(){
  $("#postaja").ready (function(){
    var salji = $("#postaja").val();
      $.ajax({
          url: '../usr/dohvatiTermine.php',
          type: 'POST',
          data: {salji},
          dataType: 'json',
          success: function(json) {
              var inHTML = '<select id="termin" name="termin">';
              $.each(json, function(i, k) {
                  inHTML += '<option value="' + k.idterminiemitiranja + '">' +
                       k.naziv + '</option>';
              });
              inHTML += '</select>';
              $('#termini')[0].innerHTML = inHTML;
          }
      });
  });
  $("#postaja").change (function(){
    var salji = $("#postaja").val();
      $.ajax({
          url: '../usr/dohvatiTermine.php',
          type: 'POST',
          data: {salji},
          dataType: 'json',
          success: function(json) {
              var inHTML = '<select id="termin" name="termin">';
              $.each(json, function(i, k) {
                  inHTML += '<option value="' + k.idterminiemitiranja + '">' +
                       k.naziv + '</option>';
              });
              inHTML += '</select>';
              $('#termini')[0].innerHTML = inHTML;
          }
      });
  });
  $("#termini").change (function(){
    var salji1 = $("#postaja").val();
    var salji = $("#termini :selected").val();
      $.ajax({
          url: '../usr/pjesmeIzTermina.php',
          type: 'POST',
          data: {salji, salji1},
          dataType: 'json',
          success: function(json) {
            var inHTML = '';
              $.each(json, function(i, k) {
                inHTML += '<tr>';
                  inHTML += '<td>' + k["idterminiemitiranja"] + '</td><td>' + k["naziv_termina"] + '</td><td>' + k["naziv_pjesme"]+ '</td><td>' + k["trajanje"] + '</td><td><a href="davanjeGlasa.php?id=' + k["id"] + '&korisnik=' + k["idKorisnik"] + '&termin=' + k["idterminiemitiranja"] + '">DAJ GLAS</a></td>';
              });
              inHTML += '</tr>';
              $('#tjeloTablice')[0].innerHTML = inHTML;
          }
      });
  });
})