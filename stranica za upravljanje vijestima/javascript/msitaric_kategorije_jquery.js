$(document).ready(function (){
    var itemi = new Array();
    
    $.getJSON('../json/searchKorisnici.json', function (jsonData){
        $.each(jsonData, function (kljucevi, vr){
            itemi.push(vr);
        });
    });

    $('.popisKorisnika').autocomplete({source:itemi});

    
});