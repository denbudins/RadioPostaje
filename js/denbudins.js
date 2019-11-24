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