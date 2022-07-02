function isprintajStatistiku(print) {
    var sadrzajZaIsprintat = document.getElementById(print).innerHTML;
    var cjelaStranica = document.body.innerHTML;

    document.body.innerHTML = sadrzajZaIsprintat;

    window.print();

    document.body.innerHTML = cjelaStranica;
}

function generirajPDF(id) {
    var sadrzajZaIsprintat = document.getElementById(id);
    var cjelaStranica = document.body.innerHTML;
    document.body.innerHTML = sadrzajZaIsprintat;

    html2pdf().from(sadrzajZaIsprintat).save();

    document.body.innerHTML = cjelaStranica;
}

