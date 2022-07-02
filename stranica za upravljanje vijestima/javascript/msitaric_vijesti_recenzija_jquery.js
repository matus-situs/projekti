$(document).ready(function (){
    var itemi = new Array();
    
    $.getJSON('../json/searchRecenzenti.json', function (jsonData){
        $.each(jsonData, function (kljucevi, vr){
            itemi.push(vr);
        });
    });

    $('.popisRecenzenta').autocomplete({source:itemi});

    
});