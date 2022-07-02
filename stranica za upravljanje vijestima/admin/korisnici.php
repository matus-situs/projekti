<?php
require_once("../vanjske_datoteke/baza.class.php");

$baza = new Baza();
$baza ->spojiDB();

session_start();
$poruka ="";

if(!isset($_SESSION["uloga"]) || $_SESSION["uloga"]!=1){
    header("Location: ../index.php");
}

if(!isset($_GET["stranica"])){
    $_GET["stranica"]=1;
}

$xmldata = simplexml_load_file("../xml/postavke.xml");
if($xmldata->onemoguceno==1){
    header("Location: nedostupno.html");
}

if(isset($_GET["unaprijedi"])){
    if(strpos($_GET["unaprijedi"], "<") !==false || strpos($_GET["unaprijedi"], ">") !==false || strpos($_GET["unaprijedi"], "'") !==false || strpos($_GET["unaprijedi"], '"') !==false){
        $poruka = "Unaprijedi ne smije sadržavati znakove <, >, ', "."'"."!<br>";
    }
    else if(!ctype_digit($_GET["unaprijedi"])){
        $poruka = "Unprijedi smije sadržavati samo brojeve!<br>";
    } 
    else{
        $upit = "SELECT * FROM korisnici WHERE id=".$_GET["unaprijedi"];
        $podaci = $baza->selectDB($upit);
        if(mysqli_num_rows($podaci)==1){
            $red = mysqli_fetch_assoc($podaci);
            if($red["status"]=="neaktiviran"){
                $upit = "UPDATE korisnici SET uloga_id=uloga_id-1, status='aktiviran' WHERE id=".$_GET["unaprijedi"];
            }
            else{
                $upit = "UPDATE korisnici SET uloga_id=uloga_id-1 WHERE id=".$_GET["unaprijedi"];
            }
            
            $podaci = $baza->updateDB($upit);

            $upit = "SELECT * FROM korisnici INNER JOIN uloga ON uloga_id=uloga.id WHERE korisnici.id=".$_GET["unaprijedi"];
            $podaci = $baza->selectDB($upit);
            $red = mysqli_fetch_assoc($podaci);

            $sql = "INSERT INTO dnevnik_rada(`vrijeme`,`radnja`,`korisnici_id`)"
            . " VALUES('".date('Y/m/d H/i/s', strtotime(" + ".$xmldata->pomakvremena." hours"))."','Korisniku ".$red["kor_ime"]." je dodjeljeno pravo _".$red["naziv"]."_', '".$_SESSION["idKorisnika"]."')";
            
            $baza->updateDB($sql);
            header("Location: korisnici.php");
        }
    }
}

if(isset($_GET["unazadi"])){
    if(strpos($_GET["unazadi"], "<") !==false || strpos($_GET["unazadi"], ">") !==false || strpos($_GET["unazadi"], "'") !==false || strpos($_GET["unazadi"], '"') !==false){
        $poruka = "Unazadi ne smije sadržavati znakove <, >, ', "."'"."!<br>";
    }
    else if(!ctype_digit($_GET["unazadi"])){
        $poruka = "Unazadi smije sadržavati samo brojeve!<br>";
    } 
    else{
        $upit = "SELECT * FROM korisnici WHERE id=".$_GET["unazadi"];
        $podaci = $baza->selectDB($upit);
        if(mysqli_num_rows($podaci)==1){
            $upit = "SELECT * FROM korisnici INNER JOIN uloga ON uloga_id=uloga.id WHERE korisnici.id=".$_GET["unazadi"];
            $podaci = $baza->selectDB($upit);
            $red = mysqli_fetch_assoc($podaci);

            $sql = "INSERT INTO dnevnik_rada(`vrijeme`,`radnja`,`korisnici_id`)"
            . " VALUES('".date('Y/m/d H/i/s', strtotime(" + ".$xmldata->pomakvremena." hours"))."','Korisniku ".$red["kor_ime"]." je oduzeto pravo _".$red["naziv"]."_', '".$_SESSION["idKorisnika"]."')";
            $baza->updateDB($sql);

            $upit = "UPDATE korisnici SET uloga_id=uloga_id+1 WHERE id=".$_GET["unazadi"];
            $podaci = $baza->updateDB($upit);
            
            header("Location: korisnici.php");
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Projekt</title>
    <link rel="stylesheet" href="../css/opcenito.css">
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
                        echo "<li><a href='dnevnik.php?stranica=1'>Dnevnik</a></li>";
                        echo "<li><a href='blokirani_korisnici.php'>Blokirani korisnici</a></li>";
                        echo "<li><a href='kategorije.php'>Kategorije</a></li>";
                        echo "<li><a href='vijesti_recenzija.php'>Vijesti za recenziju</a></li>";
                        echo "<li><a href='postavke.php'>Postavke sustava</a></li>";
                    }
                    if($_SESSION["uloga"]<4){
                        echo "<li class='desno'><a href='../index.php?odjava=1'>Odjava</a></li>";
                    }
                    ?>
                </ul>
            </nav>
            <h1>Upravljanje korisnicima</h1>
        </header>

        <?php if($poruka!=""){ ?>
        <div id="greske">
            <?php
                echo $poruka;
            ?>
        </div>
        <?php }?>

        <table>
            <thead>
                <td>Korisničko ime</td>
                <td>Status</td>
                <td>Uloga</td>
                <td>Dodaj više pravo</td>
                <td>Smanji pravo</td>
            </thead>
            <?php
            $upit = "SELECT korisnici.kor_ime, korisnici.status, uloga.naziv, korisnici.id FROM korisnici INNER JOIN uloga ON uloga.id=korisnici.uloga_id";

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
                if($_SESSION["idKorisnika"]!=$red["id"]){
                    $korisnik= "<tr><td>".$red["kor_ime"]."</td>"
                    . "<td>".$red["status"]."</td>"
                    . "<td>".$red["naziv"]."</td>";
                    if($red["naziv"]!="Administrator"){
                        $korisnik .= "<td><a href='korisnici.php?unaprijedi=".$red["id"]."'>Dodaj više pravo korisniku</a></td>";
                    }
                    else{
                        $korisnik .= "<td>Korisnik već ima najviša prava u sustavu</td>";
                    }
                    if($red["naziv"]=="Registrirani korisnik"){
                        $korisnik .= "<td>Korisnik već ima najniža registrirana prava sustava</td></tr>";
                    }
                    else if($red["naziv"]=="Neregistrirani korisnik"){
                        $korisnik .= "<td>Korisnik ima najmanja moguća prava sustava</td></tr>";
                    }
                    else{
                        $korisnik .= "<td><a href='korisnici.php?unazadi=".$red["id"]."'>Smanji pravo korisniku</a></td></tr>";
                    }
                    echo $korisnik;
                }                
            }
            ?>
        </table>

        <?php
        for($trenutnaStranica =1; $trenutnaStranica<=$brojStranica; $trenutnaStranica++){
            echo "<a class='stranica' href='korisnici.php?stranica=".$trenutnaStranica."'>".$trenutnaStranica."</a>";
        }
        echo "<br><div id='trenutno'>Trenutna stranica: ".$_GET["stranica"]."</div>";
        ?>

        <footer>

        </footer>
    
</body>
</html>

<?php

$baza ->zatvoriDB();
?>