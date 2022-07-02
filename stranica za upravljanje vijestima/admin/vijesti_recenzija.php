<?php
require_once("../vanjske_datoteke/baza.class.php");

$baza = new Baza();
$baza ->spojiDB();

session_start();

if(!isset($_SESSION["uloga"]) || $_SESSION["uloga"]!=1){
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

if(isset($_POST["popisRecenzenta"])){
    $brojGresakaUnosa=0;
    
    if(empty($_POST["popisRecenzenta"])){
        $poruka  .= "Naziv recenzenta ne smije biti prazno!<br>";
        $brojGresakaUnosa++;
    }
    if(strpos($_POST["popisRecenzenta"], "<") !==false || strpos($_POST["popisRecenzenta"], ">") !==false || strpos($_POST["popisRecenzenta"], "'") !==false || strpos($_POST["popisRecenzenta"], '"') !==false){
        $poruka  .= "Naziv recenzenta ne smije sadržavati znakove <, >, ', "."'"."!<br>";
        $brojGresakaUnosa++;
    }
    if(strpos($_POST["vijest"], "<") !==false || strpos($_POST["vijest"], ">") !==false || strpos($_POST["vijest"], "'") !==false || strpos($_POST["vijest"], '"') !==false){
        $poruka  .= "Vijest ne smije sadržavati znakove <, >, ', "."'"."!<br>";
        $brojGresakaUnosa++;
    }
    if(!ctype_digit($_POST["vijest"])){
        $poruka  .= "Vijest smije sadržavati samo brojeve.<br>";
        $brojGresakaUnosa++;
    }


    if($brojGresakaUnosa==0){
        $upit = "SELECT korisnici.kor_ime FROM korisnici INNER JOIN korisnici_has_kategorija ON korisnici_id=korisnici.id INNER JOIN kategorija ON kategorija.id=kategorija_id "
        ."INNER JOIN vijesti ON vijesti.kategorija=kategorija.id "
        ."WHERE uloga_id='2' AND kor_ime='".$_POST["popisRecenzenta"]."' AND vijesti.id=".$_POST["vijest"];
        $podaci = $baza->selectDB($upit);
        echo $upit;
        if($podaci==0){
            $poruka="Recenzent nema pravo pisati u kategoriji ili ne postoji!";
        }
        else{
                $upit = "SELECT * FROM korisnici WHERE kor_ime='".$_POST["popisRecenzenta"]."'";
                
                $podaci = $baza->selectDB($upit);
                $red = mysqli_fetch_assoc($podaci);
    
                $upit = "INSERT INTO recenzija(vijest, recenzent) VALUES (".$_POST["vijest"].", ".$red["id"].")";
                $baza->updateDB($upit);
    
                $upit = "SELECT * FROM vijest WHERE id=".$_POST["vijest"];
                $podaci = $baza->selectDB($upit);
                $red = mysqli_fetch_assoc($podaci);
    
                $sql = "INSERT INTO dnevnik_rada(`vrijeme`,`radnja`,`korisnici_id`)"
                . " VALUES('".date('Y/m/d H/i/s', strtotime(" + ".$xmldata->pomakvremena." hours"))."','Dodjeljeno je recenzentu ".$_POST["popisRecenzenta"]." vijest _".$red["naslov"]."_', '".$_SESSION["idKorisnika"]."')";
                $baza->updateDB($sql);
                header("Location: vijesti_recenzija.php");        
        }
    }      
}

$upit = "SELECT korisnici.kor_ime FROM korisnici WHERE uloga_id='2'";
$podaci = $baza->updateDB($upit);
$jsonPodaci = "[";
while ($red = mysqli_fetch_assoc($podaci)){
    $jsonPodaci .= '"'.$red["kor_ime"].'",';
}
$jsonPodaci = rtrim($jsonPodaci, ",");
$jsonPodaci .= "]";
file_put_contents("../json/searchRecenzenti.json", $jsonPodaci);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Projekt</title>
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script  src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"  integrity="sha256-6XMVI0zB8cRzfZjqKcD01PBsAy3FlDASrlC8SxCpInY="
            crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <script src="../javascript/msitaric_vijesti_recenzija.js"></script>
    <script type="text/javascript" src="../javascript/msitaric_vijesti_recenzija_jquery.js"></script>
    <link rel="stylesheet" href="../css/opcenito.css">
    <link rel="stylesheet" href="../css/stranicenje.css">
    <link rel="stylesheet" href="../css/tablice.css">
    <link rel="stylesheet" href="../css/greske.css">
    <link rel="stylesheet" href="../css/vijesti_recenzija.css">
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
                        echo "<li><a href='korisnici.php'>Korisnici</a></li>";
                        echo "<li><a href='postavke.php'>Postavke sustava</a></li>";
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
                <td>Odaberite recenzenta</td>
            </thead>
        <?php
        $upit="SELECT vijesti.id, vijesti.naslov FROM vijesti LEFT JOIN recenzija ON vijesti.id=recenzija.vijest WHERE recenzent IS null";

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
        if($podaci==NULL){
            echo "<tr><td colspan='2'>Sve vijesti imaju recenzenta</td></tr>";
        }
        else{
            $i=0;
        
            while ($red = mysqli_fetch_assoc($podaci)){            
                echo "<tr><td>".$red["naslov"]."</td>"
                ."<td><form method='POST' id='dodavanjeRecenzentaForm".$i."' action='".$_SERVER["PHP_SELF"]."'>"
                ."<input type='text' class='popisRecenzenta' name='popisRecenzenta'>"
                ."<input type='hidden' class='vijest' name='vijest' value='".$red["id"]."'>"
                ."<input type='submit' id='dodajRecenzenta".$i."' value='Dodaj Recenzenta' onclick='unos_recenzenta(dodavanjeRecenzentaForm".$i.")'>"
                ."</form></td></tr>";

                $i++;
            }
        }
        
        ?>
        </table>

        <?php
        for($trenutnaStranica =1; $trenutnaStranica<=$brojStranica; $trenutnaStranica++){
            echo "<a class='stranica' href='vijesti_recenzija.php?stranica=".$trenutnaStranica."'>".$trenutnaStranica."</a>";
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