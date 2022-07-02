<?php
require_once("../vanjske_datoteke/baza.class.php");

$baza = new Baza();
$baza ->spojiDB();

$xmldata = simplexml_load_file("../xml/postavke.xml");
if($xmldata->onemoguceno==1){
    header("Location: ../nedostupno.html");
}

if(!isset($_GET["stranica"])){
    $_GET["stranica"]=1;
}

session_start();

if(!isset($_SESSION["uloga"]) || $_SESSION["uloga"]!=1){
    header("Location: ../index.php");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">    
    <title>Projekt</title>
    <link rel="stylesheet" href="../css/opcenito.css">
    <link rel="stylesheet" href="../css/dnevnik.css">
    <link rel="stylesheet" href="../css/stranicenje.css">
    <link rel="stylesheet" href="../css/tablice.css">
</head>
<body>
<header>
            
            <nav>
                <ul>
                    <li><a href='../index.php'>Početna</a></li>
                    <?php
                    if($_SESSION["uloga"]==1){
                        echo "<li><a href='blokirani_korisnici.php'>Blokirani korisnici</a></li>";
                        echo "<li><a href='kategorije.php'>Kategorije</a></li>";
                        echo "<li><a href='korisnici.php'>Korisnici</a></li>";
                        echo "<li><a href='vijesti_recenzija.php'>Vijesti za recenziju</a></li>";
                        echo "<li><a href='postavke.php'>Postavke sustava</a></li>";
                    }
                    if($_SESSION["uloga"]<4){
                        echo "<li class='desno'><a href='../index.php?odjava=1'>Odjava</a></li>";
                    }
                    ?>
                </ul>
            </nav>
            <h1>Dnevnik</h1>
        </header>


        <form method='GET'>
            <label for='sort'>Uzlazno/silazno:</label>
            <select id='sort' name='sort'>
                <option value='asc'>Uzlazno</option>
                <option value='desc'>Silazno</option>
            </select>

            <label for='tip'>Prema kriteriju:</label>
            <select id='tip' name='tip'>
                <option value='korime'>Korisnik</option>
                <option value='vrijeme'>Vrijeme</option>
                <option value='prijave'>Prijave</option>
                <option value='odjava'>Odjave</option>
                <option value='registracija'>Registracije</option>
                <option value='aktivacija'>Aktiviran račun</option> 
                <option value='obrisanakategorija'>Obrisana kategorija</option>
                <option value='dodanakategorija'>Dodana kategorija</option>
                <option value='dodanakategorijakorisniku'>Dodan korisnik kategoriji</option>  
                <option value='oduzetopravo'>Oduzeta prava korisniku</option>    
                <option value='dodanopravo'>Dodana prava korisniku</option>
                <option value='blokiran'>Blokiran korisnik</option>  
                <option value='odblokiran'>Odblokiran korisnik</option>
                <option value='postavke'>Mijenjanje postavki sustava</option>                         
            </select>
            <input type='submit' name='filtriraj' id="filtriraj" value='Filtriraj' />
            </form>

        <table>
            <thead>
                <td>Vrijeme radnje</td>
                <td>Opis radnje</td>
                <td>Naziv korisnika</td>
            </thead>
            <?php 
            if(!isset($_GET["sort"]) && !isset($_GET["tip"])){
                $upit = "SELECT dnevnik_rada.vrijeme, dnevnik_rada.radnja, korisnici.kor_ime FROM korisnici"
                ." INNER JOIN dnevnik_rada on korisnici.id=dnevnik_rada.korisnici_id"; 
            }           
               

            if(isset($_GET["sort"]) && isset($_GET["tip"])){
                switch($_GET["tip"]){
                    case "korime": $upit = "SELECT dnevnik_rada.vrijeme, dnevnik_rada.radnja, korisnici.kor_ime FROM korisnici"
                        ." INNER JOIN dnevnik_rada on korisnici.id=dnevnik_rada.korisnici_id ORDER BY korisnici.kor_ime ".$_GET["sort"];
                        break;
                    case "vrijeme": $upit = "SELECT dnevnik_rada.vrijeme, dnevnik_rada.radnja, korisnici.kor_ime FROM korisnici"
                        ." INNER JOIN dnevnik_rada on korisnici.id=dnevnik_rada.korisnici_id ORDER BY dnevnik_rada.vrijeme ".$_GET["sort"];
                        break;
                    case "prijave": $upit = "SELECT dnevnik_rada.vrijeme, dnevnik_rada.radnja, korisnici.kor_ime FROM korisnici"
                        ." INNER JOIN dnevnik_rada on korisnici.id=dnevnik_rada.korisnici_id WHERE dnevnik_rada.radnja LIKE '%prijavio%' ORDER BY dnevnik_rada.vrijeme ".$_GET["sort"];
                    break;
                    case "odjava": $upit = "SELECT dnevnik_rada.vrijeme, dnevnik_rada.radnja, korisnici.kor_ime FROM korisnici"
                        ." INNER JOIN dnevnik_rada on korisnici.id=dnevnik_rada.korisnici_id WHERE dnevnik_rada.radnja LIKE '%odjavio%' ORDER BY dnevnik_rada.vrijeme ".$_GET["sort"];
                    break;
                    case "registracija": $upit = "SELECT dnevnik_rada.vrijeme, dnevnik_rada.radnja, korisnici.kor_ime FROM korisnici"
                        ." INNER JOIN dnevnik_rada on korisnici.id=dnevnik_rada.korisnici_id WHERE dnevnik_rada.radnja LIKE '%registriran%' ORDER BY dnevnik_rada.vrijeme ".$_GET["sort"];
                    break;
                    case "aktivacija": $upit = "SELECT dnevnik_rada.vrijeme, dnevnik_rada.radnja, korisnici.kor_ime FROM korisnici"
                        ." INNER JOIN dnevnik_rada on korisnici.id=dnevnik_rada.korisnici_id WHERE dnevnik_rada.radnja LIKE '%aktiviran%' ORDER BY dnevnik_rada.vrijeme ".$_GET["sort"];
                    break;
                    case "obrisanakategorija": $upit = "SELECT dnevnik_rada.vrijeme, dnevnik_rada.radnja, korisnici.kor_ime FROM korisnici"
                        ." INNER JOIN dnevnik_rada on korisnici.id=dnevnik_rada.korisnici_id WHERE dnevnik_rada.radnja LIKE '%obrisana je kategorija%' ORDER BY dnevnik_rada.vrijeme ".$_GET["sort"];
                    break;
                    case "dodanakategorija": $upit = "SELECT dnevnik_rada.vrijeme, dnevnik_rada.radnja, korisnici.kor_ime FROM korisnici"
                        ." INNER JOIN dnevnik_rada on korisnici.id=dnevnik_rada.korisnici_id WHERE dnevnik_rada.radnja LIKE '%unesena je kategorija%' ORDER BY dnevnik_rada.vrijeme ".$_GET["sort"];
                    break;
                    case "oduzetopravo": $upit = "SELECT dnevnik_rada.vrijeme, dnevnik_rada.radnja, korisnici.kor_ime FROM korisnici"
                        ." INNER JOIN dnevnik_rada on korisnici.id=dnevnik_rada.korisnici_id WHERE dnevnik_rada.radnja LIKE '%oduzeto pravo%' ORDER BY dnevnik_rada.vrijeme ".$_GET["sort"];
                    break;
                    case "dodanopravo": $upit = "SELECT dnevnik_rada.vrijeme, dnevnik_rada.radnja, korisnici.kor_ime FROM korisnici"
                        ." INNER JOIN dnevnik_rada on korisnici.id=dnevnik_rada.korisnici_id WHERE dnevnik_rada.radnja LIKE '%dodjeljeno pravo%' ORDER BY dnevnik_rada.vrijeme ".$_GET["sort"];
                    break;
                    case "blokiran": $upit = "SELECT dnevnik_rada.vrijeme, dnevnik_rada.radnja, korisnici.kor_ime FROM korisnici"
                        ." INNER JOIN dnevnik_rada on korisnici.id=dnevnik_rada.korisnici_id WHERE dnevnik_rada.radnja LIKE '%blokiran%' ORDER BY dnevnik_rada.vrijeme ".$_GET["sort"];
                    break;
                    case "odblokiran": $upit = "SELECT dnevnik_rada.vrijeme, dnevnik_rada.radnja, korisnici.kor_ime FROM korisnici"
                        ." INNER JOIN dnevnik_rada on korisnici.id=dnevnik_rada.korisnici_id WHERE dnevnik_rada.radnja LIKE '%nije više blokiran%' ORDER BY dnevnik_rada.vrijeme ".$_GET["sort"];
                    break;
                    case "dodanakategorijakorisniku": $upit = "SELECT dnevnik_rada.vrijeme, dnevnik_rada.radnja, korisnici.kor_ime FROM korisnici"
                        ." INNER JOIN dnevnik_rada on korisnici.id=dnevnik_rada.korisnici_id WHERE dnevnik_rada.radnja LIKE '%Dodjeljeno je korisniku%' ORDER BY dnevnik_rada.vrijeme ".$_GET["sort"];
                    break;
                    case "postavke": $upit = "SELECT dnevnik_rada.vrijeme, dnevnik_rada.radnja, korisnici.kor_ime FROM korisnici"
                        ." INNER JOIN dnevnik_rada on korisnici.id=dnevnik_rada.korisnici_id WHERE dnevnik_rada.radnja LIKE '%postavke%' ORDER BY dnevnik_rada.vrijeme ".$_GET["sort"];
                    break;
                }
            }

            $rezultatiPoStranici = $xmldata->rezultatipostranici;
            $podaci = $baza->selectDB($upit);
            
            $brojRezultata = mysqli_num_rows($podaci);
            $brojStranica = ceil($brojRezultata/$rezultatiPoStranici);
            
            if(!isset($_GET["stranica"])){
                $trenutnaStranica = 1;
            }else{
                $trenutnaStranica = $_GET["stranica"];
            }

            $rezultatiStranice = ($trenutnaStranica-1)*$rezultatiPoStranici;

            $upit .= " LIMIT ".$rezultatiStranice.",".$rezultatiPoStranici;
            $podaci = $baza->selectDB($upit);

            while ($red = mysqli_fetch_assoc($podaci)){
                echo "<tr><td>".$red["vrijeme"]."</td>"
                . "<td>".$red["radnja"]."</td>"
                . "<td>".$red["kor_ime"]."</td></tr>";
            }
            ?>
        </table>

        <?php
        for($trenutnaStranica =1; $trenutnaStranica<=$brojStranica; $trenutnaStranica++){
            if(!isset($_GET["sort"]) && !isset($_GET["tip"])){
                echo "<a class='stranica' href='dnevnik.php?stranica=".$trenutnaStranica."'>".$trenutnaStranica."</a>";
            }
            else{
                echo "<a class='stranica' href='dnevnik.php?stranica=".$trenutnaStranica."&sort=".$_GET["sort"]."&tip=".$_GET["tip"]."'>".$trenutnaStranica."</a>";
            }
            
        }
        echo "<br> <div id='trenutno'>Trenutna stranica: ".$_GET["stranica"]."</div>";
        ?>

        <footer>

        </footer>
    
</body>
</html>

<?php

$baza ->zatvoriDB();
?>