<?php
require_once("../vanjske_datoteke/baza.class.php");

$baza = new Baza();
$baza ->spojiDB();

session_start();

if(!isset($_SESSION["uloga"]) || $_SESSION["uloga"]!=2){
    header("Location: ../index.php");
}

$xmldata = simplexml_load_file("../xml/postavke.xml");
if($xmldata->onemoguceno==1){
    header("Location: nedostupno.html");
}

if(!isset($_GET["stranica"])){
    $_GET["stranica"]=1;
}
$poruka="";

if(isset($_POST["komentar"])){
    $greske=0;
    if(strpos($_POST["nedostatci"], "<") !==false || strpos($_POST["nedostatci"], ">") !==false || strpos($_POST["nedostatci"], "'") !==false || strpos($_POST["nedostatci"], '"') !==false){
        $poruka.="Polje 'nedostatci' ne smije sadržavati znakove <, >, ', ".'"'."!<br>";
        $greske++;
    }
    if(empty($_POST["komentar"])){
        $poruka.="Polje 'komentar' ne smijebiti prazno!<br>";
        $greske++;
    }
    if(strpos($_POST["komentar"], "<") !==false || strpos($_POST["komentar"], ">") !==false || strpos($_POST["komentar"], "'") !==false || strpos($_POST["komentar"], '"') !==false){
        $poruka.="Polje 'komentar' ne smije sadržavati znakove <, >, ', ".'"'."!<br>";
        $greske++;
    }
    if(strpos($_POST["cinjenicnegreske"], "<") !==false || strpos($_POST["cinjenicnegreske"], ">") !==false || strpos($_POST["cinjenicnegreske"], "'") !==false || strpos($_POST["cinjenicnegreske"], '"') !==false){
        $poruka.="Polje 'činjenične greške' ne smije sadržavati znakove <, >, ', ".'"'."!<br>";
        $greske++;
    }
    if(strpos($_POST["gramatickegreske"], "<") !==false || strpos($_POST["gramatickegreske"], ">") !==false || strpos($_POST["gramatickegreske"], "'") !==false || strpos($_POST["gramatickegreske"], '"') !==false){
        $poruka.="Polje 'gramatićke greške' ne smije sadržavati znakove <, >, ', ".'"'."!<br>";
        $greske++;
    }

    $nedostatakmaterijala=false;
    if(isset($_POST["nedostatakmaterijala"])){
        $nedostatakmaterijala=true;
    }
    $nedostatakizvora=false;
    if(isset($_POST["nedostatakizvora"])){
        $nedostatakizvora=true;
    }


    if($greske==0){
        $status=0;
        switch($_POST["odabir"]){
            case "recenzija":$status=3;break;
            case "prihvacena":$status=1;break;
            case "odbijena":$status=2;break;
            case "dorada":$status=4;break;
            default: $status="error";
        }
        if($status=="error"){
            $poruka .= "Došlo je do greške pri odabiru statusa vijesti.<br>";
        }
        else{
            $upit="UPDATE recenzija SET nedostatci='".$_POST["nedostatci"]."', komentar='".$_POST["komentar"]."', cinjenicne_pogreske='".$_POST["cinjenicnegreske"]."',"
            ." gramaticke_pogreske='".$_POST["gramatickegreske"]."', nedostatak_materijala='".$nedostatakmaterijala."', nedostatak_izvora='".$nedostatakizvora."'"
            ." WHERE id=".$_POST["recenzijaid"]." AND recenzent=".$_SESSION["idKorisnika"];
            $baza->updateDB($upit);

            $upit = "UPDATE vijesti INNER JOIN recenzija ON vijesti.id=recenzija.vijest SET vijesti.status_vijesti=".$status." WHERE recenzija.id=".$_POST["recenzijaid"]." AND recenzent=".$_SESSION["idKorisnika"];
            $baza->updateDB($upit);

            if($status==4){
                $upit="UPDATE vijesti INNER JOIN recenzija ON recenzija.vijest=vijesti.id SET verzija=verzija+1 WHERE recenzija.id=".$_POST["recenzijaid"]." AND recenzent=".$_SESSION["idKorisnika"];
                $baza->updateDB($upit);
            }

            $upit ="SELECT * FROM vijesti INNER JOIN recenzija ON recenzija.vijest=vijesti.id WHERE recenzija.id=".$_POST["recenzijaid"]." AND recenzent=".$_SESSION["idKorisnika"];
            echo $upit;
            $podaci = $baza->selectDB($upit);
            $red= mysqli_fetch_assoc($podaci);

            $sql = "INSERT INTO dnevnik_rada(`vrijeme`,`radnja`,`korisnici_id`)"
                . " VALUES('".date('Y/m/d H/i/s', strtotime(" + ".$xmldata->pomakvremena." hours"))."','Dodana/ažurirana je recenzija za vijest _".$red["naslov"]."_', '".$_SESSION["idKorisnika"]."')";
            $baza->updateDB($sql);
            $poruka="Uspješno ažurirana recenzija!<br>";
            header("Location: vijestimoderator.php");
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
    <link rel="stylesheet" href="../css/greske.css">
    <link rel="stylesheet" href="../css/popupformamoderatorvijesti.css">
    <script src="../javascript/msitaric_vijestimoderator.js"></script>
</head>
<body>
    <header>
            
            <nav>
                <ul>
                    <li><a href='../index.php'>Početna</a></li>
                    <?php
                    if($_SESSION["uloga"]==2){
                        echo "<li><a href='odbijeno.php'>Odbijene vjesti</a></li>";
                        echo "<li><a href='statistika.php'>Statistika</a></li>";
                    }
                    if($_SESSION["uloga"]<4){
                        echo "<li class='desno'><a href='../index.php?odjava=1'>Odjava</a></li>";
                    }
                    ?>
                </ul>
            </nav>
            <h1>Vijesti za recenziju</h1>
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
                <td>Naslov vijesti</td>
                <td>Recenziraj/Ažuriraj</td>
            </thead>
        <?php
        $upit="SELECT vijesti.naslov, vijesti.sadrzaj, recenzija.* FROM vijesti INNER JOIN recenzija ON vijesti.id=recenzija.vijest "
        ."WHERE recenzent=".$_SESSION["idKorisnika"]." AND status_vijesti=3";

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
        
            $i=0;
        
            while ($red = mysqli_fetch_assoc($podaci)){            
                echo "<tr><td>".$red["naslov"]."</td>"
                ."<td><button class='otvoriRecenzijuForm' onclick='otvoriRecenziju(\"recenziranjeForm".$i."\")'>Recenziraj vijest</button>"
                ."<div class='formaPopup' id='recenzijaForm'>"
                ."<form method='POST' id='recenziranjeForm".$i."' action='".$_SERVER["PHP_SELF"]."'>"
                ."<label for='naslovVijesti'>Naslov</label><input type='text' name='naslovVijesti' value='".$red["naslov"]."' disabled>"
                ."<label for='sadrzajVijesti'>Sadržaj</label><br><textarea name='sadrzajVijesti' rows='10' cols='77' disabled>".$red["sadrzaj"]."</textarea><br>"
                ."<label for='nedostatci'>Nedostatci</label><input type='text' name='nedostatci' value='".$red["nedostatci"]."'>"
                ."<label for='komentar'>Komentar</label><br><textarea name='komentar' rows='10' cols='77'>".$red["komentar"]."</textarea><br>"
                ."<label for='cinjenicnegreske'>Činjenične greške</label><input type='text' name='cinjenicnegreske' value='".$red["cinjenicne_pogreske"]."'>"
                ."<input type='hidden' name='recenzijaid' value='".$red["id"]."'>"
                ."<label for='gramatickegreske'>Gramatičke greške</label><input type='text' name='gramatickegreske' value='".$red["gramaticke_pogreske"]."'>"
                ."<label for='nedostatakmaterijala'>Nedostatak materijala</label><input type='checkbox' name='nedostatakmaterijala' value='".$red["nedostatak_materijala"]."'><br>"
                ."<label for='nedostatakizvora'>Nedostatak izvora</label><input type='checkbox' name='nedostatakizvora' value='".$red["nedostatak_izvora"]."'><br>"
                ."<label for='odabir'>Odaberite stanje vijesti</label><br>"
                ."<select id='odabir' name='odabir'><option value='recenzija'>Recenzija</option><option value='prihvacena'>Prihvati</option><option value='odbijena'>Odbij</option><option value='dorada'>Treba doraditi</option></select><br>"
                ."<input type='submit' id='recenziraj".$i."' value='Recenziraj' onclick='unos_recenzije(recenziranjeForm".$i.")'>"
                ."<button type='button' class='odustani' onclick='zatvoriRecenzija(\"recenziranjeForm".$i."\")'>Odustani</button>"
                ."</form></div></td></tr>";

                $i++;
            }
        
        
        ?>
        </table>

        <?php
        for($trenutnaStranica =1; $trenutnaStranica<=$brojStranica; $trenutnaStranica++){
            echo "<a class='stranica' href='vijestimoderator.php?stranica=".$trenutnaStranica."'>".$trenutnaStranica."</a>";
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