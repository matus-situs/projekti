var forma;

document.addEventListener("DOMContentLoaded", ucitaj);

function ucitaj(){
    forma=document.getElementById("registracijaForm");
    forma.addEventListener("submit", function(e){predaja_forme(e);});
}

function predaja_forme(event){
    var gr="";
    var nepopunjeno=0;
    var labele = document.getElementsByTagName('label');
    
    for(var i =0; i<forma.length;i++){
        if(forma[i].value==""){
            for(var j=0; j<labele.length; j++){
                if(labele[j].htmlFor==forma[i].id){
                    gr+= labele[j].innerHTML + " mora biti upisano!<br>";
                    labele[j].style="background-color:#e75443;";
                }
            }
            nepopunjeno++;
        } 
        else if(forma[i].value!=0){
            if(forma[i].value.includes("'") || forma[i].value.includes('"') || forma[i].value.includes("<") || forma[i].value.includes(">")){
                for(var j=0; j<labele.length; j++){
                    if(labele[j].htmlFor==forma[i].id){
                        gr+= labele[j].innerHTML + " ne smije sadržavati znakove >, < ', "+'"'+"<br>";
                        labele[j].style="background-color:#e75443;";
                    }
                }
                nepopunjeno++;
            }
            for(var j=0; j<labele.length; j++){
                if(labele[j].htmlFor==forma[i].id){
                    labele[j].style="background-color:none;";
                }
            }
        }
    }

    if(!forma[6].checked){
        gr+= "Uvjeti korištenja moraju biti prihvaćeni!<br>";
        nepopunjeno++;
    } 

    if(forma[4].length<6){
        gr+="Lozinka mora imati minimalno 6 znakova!<br>"
    }


    if(forma[4].value!=forma[5].value){
        gr+= "Lozinka i ponovljena lozinka nisu jednake!<br>";
        nepopunjeno++;
    }
        
    if(nepopunjeno!=0){
        var greske = document.getElementById("greske");
        greske.innerHTML = gr;
        event.preventDefault();
    }
}
