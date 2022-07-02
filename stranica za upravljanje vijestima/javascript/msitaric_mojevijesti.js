function NovaVijest() {
    document.getElementById("vijestForm").style.display = "block";
  }
  
  function zatvoriNovaVijest() {
   document.getElementById("vijestForm").style.display = "none";
  }


var forma;
document.addEventListener("DOMContentLoaded", ucitaj);

function ucitaj(){
    forma=document.getElementById("novaVijestForm");
    document.getElementById("vijestForm").style.display = "none"
    forma.addEventListener("submit", function(e){predaja_forme(e);});
}

function predaja_forme(event){
    var gr="";
    var nepopunjeno=0;
    
    var naslov = document.forms["novaVijestForm"]["naslov"].value;
    var sadrzaj = document.forms["novaVijestForm"]["sadrzaj"].value;
    var izvor = document.forms["novaVijestForm"]["izvor"].value;
    var kategorija = document.forms["novaVijestForm"]["kategorija"].value;
    var slika = document.forms["novaVijestForm"]["slika"].value.split(".");
    slika=slika[slika.length-1].toLowerCase();
    var tagiranje = document.forms["novaVijestForm"]["tagiranje"].value;
    var audio = document.forms["novaVijestForm"]["audio"].value.split(".");
    audio=audio[audio.length-1].toLowerCase();
    var video = document.forms["novaVijestForm"]["video"].value.split(".");
    video=video[video.length-1].toLowerCase();

    if(document.forms["novaVijestForm"]["slika"].value==""){
      gr+="Slika se mora priložiti<br>";
      nepopunjeno++;
    }
    if(naslov==""){
      gr+="Naslov ne smije biti prazan<br>";
      nepopunjeno++;
    }
    if(naslov.includes("'") || naslov.includes("<") || naslov.includes(">") || naslov.includes('"')){
      gr+="Naslov ne smije sadržavati znakove >, < ', "+'"'+"<br>";
      nepopunjeno++;
    }
    if(sadrzaj.includes("'") || sadrzaj.includes("<") || sadrzaj.includes(">") || sadrzaj.includes('"')){
      gr+="Sadržaj ne smije sadržavati znakove >, < ', "+'"'+"<br>";
      nepopunjeno++;
    }
    if(sadrzaj==""){
      gr+="Sadržaj ne smije biti prazan<br>";
      nepopunjeno++;
    }
    if(izvor.includes("'") || izvor.includes("<") || izvor.includes(">") || izvor.includes('"')){
      gr+="Izvor ne smije sadržavati znakove >, < ', "+'"'+"<br>";
      nepopunjeno++;
    }
    if(kategorija==""){
      gr+="Kategorija mora biti odabrana<br>";
      nepopunjeno++;
    }
    if(slika.includes("'") || slika.includes("<") || slika.includes(">") || slika.includes('"')){
      gr+="Naziv slike ne smije sadržavati znakove >, < ', "+'"'+"<br>";
      nepopunjeno++;
    }
    if(!(slika=="png" || slika=="jpg" || slika=="jpeg")){
      gr+="Slika mora imati nastavak jpg, png ili jpeg<br>";
      nepopunjeno++;
    }
    if(tagiranje.includes("'") || tagiranje.includes("<") || tagiranje.includes(">") || tagiranje.includes('"')){
      gr+="Tagiranje ne smije sadržavati znakove >, < ', "+'"'+"<br>";
      nepopunjeno++;
    }
    if(audio.includes("'") || audio.includes("<") || audio.includes(">") || audio.includes('"')){
      gr+="Naziv audio datoteke ne smije sadržavati znakove >, < ', "+'"'+"<br>";
      nepopunjeno++;
    }
    if(audio!="" && !(audio=="mp3")){
      gr+="Audio mora imati nastavak mp3<br>";
      nepopunjeno++;
    }
    if(video.includes("'") || video.includes("<") || video.includes(">") || video.includes('"')){
      gr+="Naziv videa ne smije sadržavati znakove >, < ', "+'"'+"<br>";
      nepopunjeno++;
    }
    if(video!="" && !(video=="mp4" || video=="avi" || video=="mpg" || video=="m4v")){
      gr+="Video mora imati nastavak mp4, avi, mpg ili m4v<br>";
      nepopunjeno++;
    }

        
    if(nepopunjeno!=0){
        var greske = document.getElementById("greske");
        greske.innerHTML = gr;
        event.preventDefault();
    }
}
