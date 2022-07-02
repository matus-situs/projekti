<?php
require_once("../vanjske_datoteke/baza.class.php");

$baza = new Baza();
$baza ->spojiDB();

session_start();
$poruka="";

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

if(isset($_POST["razlog"])){
    $brojgresaka=0;
    if(empty($_POST["razlog"])){
        $poruka .= "Razlog ne smije biti prazan!<br>";
        $brojgresaka++;
    }
    if(strpos($_POST["razlog"], "<") !==false || strpos($_POST["razlog"], ">") !==false || strpos($_POST["razlog"], "'") !==false || strpos($_POST["razlog"], '"') !==false){
        $poruka .= "Razlog ne smije sadržavati znakove <, >, ', "."'"."!<br>";
        $brojgresaka++;
    }
    if(strpos($_POST["vijest"], "<") !==false || strpos($_POST["vijest"], ">") !==false || strpos($_POST["vijest"], "'") !==false || strpos($_POST["vijest"], '"') !==false){
        $poruka .= "Vijest ne smije sadržavati znakove <, >, ', "."'"."!<br>";
        $brojgresaka++;
    }
    if(strpos($_POST["autor"], "<") !==false || strpos($_POST["autor"], ">") !==false || strpos($_POST["autor"], "'") !==false || strpos($_POST["autor"], '"') !==false){
        $poruka .= "Autor ne smije sadržavati znakove <, >, ', "."'"."!<br>";
        $brojgresaka++;
    }
    if(!ctype_digit($_POST["autor"])){
        $poruka .= "Autor smije sadržavati samo broj!<br>";
        $brojgresaka++;
    }
    if(!ctype_digit($_POST["vijest"])){
        $poruka .= "Vijest smije sadržavati samo broj!<br>";
        $brojgresaka++;
    }
    if($brojgresaka==0){
        $upit="SELECT * FROM vijesti WHERE id=".$_POST["vijest"];
        $podaci = $baza->selectDB($upit);
        $red = mysqli_fetch_assoc($podaci);

        $upit = "INSERT INTO blokirani_u_kategoriji(kategorija_id, blokiran_korisnik_id) VALUES ('".$red["kategorija"]."','".$_POST["autor"]."')";
        $baza->updateDB($upit);

        $upit="INSERT INTO odbijeno(blokirani_korisnik, razlog, datum, vijest) VALUES('".$_POST["autor"]."', '".$_POST["razlog"]."', '".$_POST["datum"]."', '".$_POST["vijest"]."')";
        $baza->updateDB($upit);

        $upit="SELECT * FROM korisnici WHERE id=".$_POST["autor"];
        $podaci = $baza->selectDB($upit);
        $red = mysqli_fetch_assoc($podaci);
        $korime=$red["kor_ime"];
        $upit="SELECT * FROM kategorija INNER JOIN vijesti ON vijesti.kategorija=kategorija.id WHERE vijesti.id=".$_POST["vijest"];
        $podaci = $baza->selectDB($upit);
        $red = mysqli_fetch_assoc($podaci);
        $kategorija=$red["naziv"];
        $sql = "INSERT INTO dnevnik_rada(`vrijeme`,`radnja`,`korisnici_id`)"
                . " VALUES('".date('Y/m/d H/i/s', strtotime(" + ".$xmldata->pomakvremena." hours"))."','Korisnik ".$korime." je blokiran u kategoriji _".$kategorija."_', '".$_SESSION["idKorisnika"]."')";
        
        $baza->updateDB($sql);
        header("Location: odbijeno.php");
    }

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Projekt</title>
    <script src="../javascript/msitaric_odbijeno.js"></script>
    <link rel="stylesheet" href="../css/opcenito.css">
    <link rel="stylesheet" href="../css/stranicenje.css">
    <link rel="stylesheet" href="../css/tablice.css">
    
    <link rel="stylesheet" href="../css/greske.css">
    <?php
    if(isset($_GET["vijest"]) && isset($_GET["autor"])){
        echo '<link rel="stylesheet" href="../css/popupforma.css">';
    }    
    else{
        echo '<link rel="stylesheet" href="../css/skrivenpopup.css">';
    }
    ?>
</head>
<body>
    <header>
            
            <nav>
                <ul>
                    <li><a href='../index.php'>Početna</a></li>
                    <?php
                    if($_SESSION["uloga"]==2){
                        echo "<li><a href='vijestimoderator.php'>Vijesti za recenziju</a></li>";
                        echo "<li><a href='statistika.php'>Statistika</a></li>";
                    }
                    if($_SESSION["uloga"]<4){
                        echo "<li class='desno'><a href='../index.php?odjava=1'>Odjava</a></li>";
                    }
                    ?>
                </ul>
            </nav>
            <h1>Odbijene vijesti</h1>
        </header>

        <?php if($poruka!=""){ ?>
            <div id="greske">
            <?php
                echo $poruka;
            ?>
            </div>
            <?php }?>

        <div class="formaPopup" id="vijestForm">
            <form method="POST" id="razlogOdbijanja" action="<?php echo $_SERVER["PHP_SELF"];?>">
                
            
                
                <label for="razlog">Razlog blokiranja</label>
                <input type="text" name="razlog">
                <label for="razlog">Do kada je korisnik blokiran</label>
                <input type="date" name="datum">
                <input type="hidden" name="vijest" value="<?php
                echo $_GET["vijest"];
                ?>">
                <input type="hidden" name="autor" value="<?php
                echo $_GET["autor"];
                ?>">
                <input type="submit" name="blokiraj" class="gumb" value="Blokiraj korisnika">
                <button type="button" class="gumb odustani" onclick="zatvoriBlokiranje();">Odustani</button>
            </form>
        </div>

        <table>
            <thead>
                <td>Naslov vijesti</td>
                <td>Sadržaj</td>
                <td>Autor</td>
                <td>Blokiraj autora vjesti</td>
            </thead>
            <?php
            $upit="SELECT vijesti.id, vijesti.naslov, vijesti.sadrzaj, vijesti.autor, korisnici.kor_ime, vijesti.kategorija FROM vijesti INNER JOIN blokirani_u_kategoriji ON blokirani_u_kategoriji.blokiran_korisnik_id=vijesti.autor AND blokirani_u_kategoriji.kategorija_id=vijesti.kategorija"
            ." INNER JOIN korisnici ON korisnici.id=vijesti.autor"
            ." WHERE status_vijesti=2";
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
                    echo "<tr><td>".$red["naslov"]."</td><td>".$red["sadrzaj"]."</td><td>".$red["kor_ime"]."</td>";
                        echo "<td>Već je korisnik blokiran u kategoriji</td></tr>";
                }

            $upit = "SELECT vijesti.id, vijesti.naslov, vijesti.sadrzaj, vijesti.autor, korisnici.kor_ime, vijesti.kategorija FROM vijesti 
            INNER JOIN odbijeno ON odbijeno.blokirani_korisnik=vijesti.autor 
            INNER JOIN korisnici ON korisnici.id=vijesti.autor
            WHERE status_vijesti=2 AND vijesti.autor NOT IN (SELECT blokirani_u_kategoriji.blokiran_korisnik_id FROM blokirani_u_kategoriji) AND vijesti.kategorija NOT IN (SELECT blokirani_u_kategoriji.kategorija_id FROM blokirani_u_kategoriji)";
            $podaci = $baza->selectDB($upit);
            
            
            while ($red = mysqli_fetch_assoc($podaci)){
                echo "<tr><td>".$red["naslov"]."</td><td>".$red["sadrzaj"]."</td><td>".$red["kor_ime"]."</td>";
                echo "<td><a href='odbijeno.php?autor=".$red["autor"]."&vijest=".$red["id"]."'>Blokiraj autora</a></td></tr>";
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