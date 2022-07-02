var forma;
var forma2 = [];


document.addEventListener("DOMContentLoaded", ucitaj);

function ucitaj(){
    forma2=document.getElementsByClassName("popisRecenzenta");
}

function unos_recenzenta(unos){
    var gr="";
    var nepopunjeno=0;

    if(unos[0].value==""){        
            gr = "Mora biti uneseno korisničko ime recenzenta!";
            nepopunjeno++;        
    } else if(unos[0].value!=""){
        if(unos[0].value.includes("'") || unos[0].value.includes('"') || unos[0].value.includes("<") || unos[0].value.includes(">")){            
            gr = "Korisničko ime ne smije sadržavati znakove >, < ', "+'"'+"<br>";           
            nepopunjeno++;
        }
    }

    if(nepopunjeno!=0){
        var greske = document.getElementById("greske");
        greske.innerHTML = gr;
        event.preventDefault();
    }
}