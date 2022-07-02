var forma;

document.addEventListener("DOMContentLoaded", ucitaj);

function ucitaj(){
    forma=document.getElementById("kategorijeForm");
    
    $("#kategorijeForm").submit(function (e) { 
        predaja_forme(e);        
    });
}

function predaja_forme(event){
    var gr="";
    var nepopunjeno=0;
    var labele = document.getElementsByTagName('label');

    if(forma[0].value==""){        
        if(labele[0].htmlFor==forma[0].id){
            gr+= labele[0].innerHTML + " mora biti upisano!<br>";
            labele[0].style="background-color:red;";
        }        
        nepopunjeno++;
    } else if(forma[0].value!=0){
        if(forma[0].value.includes("'") || forma[0].value.includes('"') || forma[0].value.includes("<") || forma[0].value.includes(">")){
            
                if(labele[0].htmlFor==forma[0].id){
                    gr+= labele[0].innerHTML + " ne smije sadržavati znakove >, < ', "+'"'+"<br>";
                    labele[0].style="background-color:red;";
                }
            
            nepopunjeno++;
        }
        if(labele[0].htmlFor==forma[0].id){
            labele[0].style="background-color:none;";
        }        
    }

    if(nepopunjeno!=0){
        var greske = document.getElementById("greske");
        greske.innerHTML = gr;
        event.preventDefault();
    }
}

function unos_korisnika(unos){
    var gr="";
    var nepopunjeno=0;

    if(unos[0].value==""){        
            gr = "Mora biti uneseno korisničko ime korisnika!";
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