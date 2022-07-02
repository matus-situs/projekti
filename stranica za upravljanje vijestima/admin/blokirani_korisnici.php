<?php
require_once("../vanjske_datoteke/baza.class.php");

$baza = new Baza();
$baza ->spojiDB();

$xmldata = simplexml_load_file("../xml/postavke.xml");
if($xmldata->onemoguceno==1){
    header("Location: ../nedostupno.html");
}

session_start();

if(!isset($_SESSION["uloga"]) || $_SESSION["uloga"]!=1){
    header("Location: ../index.php");
}

$poruka="";

if(isset($_GET["korisnik"])){
    if(strpos($_GET["korisnik"], "<") !==false || strpos($_GET["korisnik"], ">") !==false || strpos($_GET["korisnik"], "'") !==false || strpos($_GET["korisnik"], '"') !==false){
        $poruka = "Korisnik ne smije sadržavati znakove <, >, ', "."'"."!<br>";
    }
    else if(ctype_digit($_GET["korisnik"])){
        $poruka = "Korisnik smije sadržavati samo znakove!<br>";
    } 
    else{
        $upit = "SELECT korisnici.id, korisnici.kor_ime, kategorija.naziv FROM blokirani_u_kategoriji INNER JOIN korisnici ON korisnici.id=blokiran_korisnik_id INNER JOIN kategorija ON kategorija.id=kategorija_id WHERE kor_ime='".$_GET["korisnik"]."' AND kategorija_id=".$_GET["kategorija"];
        
        $podaci = $baza->selectDB($upit);
            $red = mysqli_fetch_assoc($podaci);
            $upit = "DELETE FROM blokirani_u_kategoriji WHERE blokiran_korisnik_id=".$red["id"]." AND kategorija_id=".$_GET["kategorija"];
            $podaci = $baza->updateDB($upit);
            $upit = "DELETE odbijeno FROM odbijeno INNER JOIN vijesti ON odbijeno.vijest=vijesti.id INNER JOIN kategorija ON kategorija.id=vijesti.kategorija WHERE kategorija.id=".$_GET["kategorija"]." AND odbijeno.blokirani_korisnik=".$red["id"];
            $podaci = $baza->updateDB($upit);

            $sql = "INSERT INTO dnevnik_rada(`vrijeme`,`radnja`,`korisnici_id`)"
            . " VALUES('".date('Y/m/d H/i/s', strtotime(" + ".$xmldata->pomakvremena." hours"))."','Korisnik ".$red["kor_ime"]." nije više blokiran u kategoriji _".$red["naziv"]."_', '".$_SESSION["idKorisnika"]."')";
            $baza->updateDB($sql);

            header("Location: blokirani_korisnici.php");
             
    }    
}

if(isset($_GET["odblokirajkorisnik"])){
    if(strpos($_GET["odblokirajkorisnik"], "<") !==false || strpos($_GET["odblokirajkorisnik"], ">") !==false || strpos($_GET["odblokirajkorisnik"], "'") !==false || strpos($_GET["odblokirajkorisnik"], '"') !==false){
        $poruka = "odblokirajkorisnik ne smije sadržavati znakove <, >, ', "."'"."!<br>";
    }
    else if(!ctype_digit($_GET["odblokirajkorisnik"])){
        $poruka = "odblokirajkorisnik smije sadržavati samo znakove!<br>";
    } 
    else{
        $upit = "SELECT * FROM korisnici WHERE id=".$_GET["odblokirajkorisnik"];
        $podaci = $baza->selectDB($upit);
        
        $red = mysqli_fetch_assoc($podaci);
        $upit="UPDATE korisnici SET status='aktiviran', broj_neuspjesnih_pokusaja=0 WHERE id=".$_GET["odblokirajkorisnik"];
        $podaci = $baza->updateDB($upit);

        $sql = "INSERT INTO dnevnik_rada(`vrijeme`,`radnja`,`korisnici_id`)"
            . " VALUES('".date('Y/m/d H/i/s', strtotime(" + ".$xmldata->pomakvremena." hours"))."','Korisnik ".$red["kor_ime"]." nije više blokiran', '".$_SESSION["idKorisnika"]."')";
        $baza->updateDB($sql);

        header("Location: blokirani_korisnici.php");
             
    }    
}

if(!isset($_GET["stranica"])){
    $_GET["stranica"]=1;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Projekt</title>
    <link rel="stylesheet" href="../css/opcenito.css">
    <link rel="stylesheet" href="../css/blokirani_korisnici.css">
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
    </header>

    <h1>Popis blokiranih korisnika</h1>

    <h3>Korisnici blokiran u kategoriji</h3>

    <div id="greska">
            <?php
                echo $poruka;
            ?>
    </div>

    <table>
        <thead>
            <td>Korisnično ime</td>
            <td>Razlog</td>
            <td>Datum do kada je korisnik blokiran</td>
            <td>Deblokiranje</td>
        </thead>

    <?php
    $upit = "SELECT korisnici.kor_ime, odbijeno.razlog, odbijeno.datum, kategorija.id FROM korisnici"
                    ." INNER JOIN odbijeno ON korisnici.id=odbijeno.blokirani_korisnik"
                    ." INNER JOIN vijesti ON vijesti.id=odbijeno.vijest"
                    ." INNER JOIN kategorija ON kategorija.id=vijesti.kategorija";

    $rezultatiPoStranici = $xmldata->rezultatipostranici;
    $podaci = $baza->selectDB($upit);
    $brojRezultata = mysqli_num_rows($podaci);

    if($brojRezultata>0){
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
            echo "<tr><td>".$red["kor_ime"]."</td>";
            if(empty($red["razlog"])){
                echo "<td>-</td>";
            }else{
                echo "<td>".$red["razlog"]."</td>";
            }
            if(empty($red["datum"])){
                echo "<td>-</td></tr>";
            }else{
                echo "<td>".$red["datum"]."</td>";
            }
            echo "<td><a href='blokirani_korisnici.php?korisnik=".$red["kor_ime"]."&kategorija=".$red["id"]."'>Deblokiraj</a></td></tr>";
        }

        echo "</table>";
        
        for($trenutnaStranica =1; $trenutnaStranica<=$brojStranica; $trenutnaStranica++){
            echo "<a class='stranica' href='blokirani_korisnici.php?stranica=".$trenutnaStranica."'>".$trenutnaStranica."</a>";
        }
        echo "<br> <div id='trenutno'>Trenutna stranica: ".$_GET["stranica"]."</div>";   
    } else{
        echo "<tr><td colspan=4><div id='prazno'>Nema podataka</div></td></tr>";
        echo "</table>";
    }
    ?>   
    

    <h3>Blokirani korisnici zbog previše neuspješnih prijava</h3>
    <table>
        <thead>
            <td>Korisnično ime</td>
            <td>Deblokiranje</td>
        </thead>
        <?php
    $upit = "SELECT * FROM korisnici WHERE korisnici.status='blokiran' AND korisnici.broj_neuspjesnih_pokusaja='3'";

    $podaci = $baza->selectDB($upit);
    $brojRezultata = mysqli_num_rows($podaci);

    if($brojRezultata>0){
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
            echo "<tr><td>".$red["kor_ime"]."</td>";            
            echo "<td><a href='blokirani_korisnici.php?odblokirajkorisnik=".$red["id"]."'>Odblokiraj</a></td></tr>";
        }

        echo "</table>";
        
        for($trenutnaStranica =1; $trenutnaStranica<=$brojStranica; $trenutnaStranica++){
            echo "<a href='kategorije.php?stranica=".$trenutnaStranica."'>".$trenutnaStranica."</a>";
        }
        echo "<br>Trenutna stranica: ".$_GET["stranica"];

    } else{
        echo "<tr><td colspan=2><div id='prazno'>Nema podataka</div></td></tr>";
        echo "</table>";
    }
    ?>

    <footer>

    </footer>    
</body>
</html>

<?php

$baza ->zatvoriDB();
?>