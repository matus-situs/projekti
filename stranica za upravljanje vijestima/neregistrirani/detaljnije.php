<?php
require_once("../vanjske_datoteke/baza.class.php");

$baza = new Baza();
$baza ->spojiDB();

session_start();

if(!isset($_SESSION["uloga"]) || $_SESSION["uloga"]!=4){
    header("Location: ../index.php");
}

$xmldata = simplexml_load_file("../xml/postavke.xml");
if($xmldata->onemoguceno==1){
    header("Location: nedostupno.html");
}

$red="";

if(!isset($_GET["vijest"])){
    header("Location: vijesti.php");
}
else{
    if(strpos($_GET["vijest"], "<") !==false || strpos($_GET["vijest"], ">") !==false || strpos($_GET["vijest"], "'") !==false || strpos($_GET["vijest"], '"') !==false || !ctype_digit($_GET["vijest"])){
        header("Location: vijesti.php");
    }
    $upit = "SELECT * FROM vijesti INNER JOIN korisnici ON autor=korisnici.id INNER JOIN kategorija ON kategorija.id=vijesti.kategorija WHERE vijesti.id=".$_GET["vijest"];
    $podaci=$baza->selectDB($upit);
    $red = mysqli_fetch_assoc($podaci);
    $sql="UPDATE vijesti SET broj_pregleda=broj_pregleda+1 WHERE id=".$_GET["vijest"];
    $baza->updateDB($sql);
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Projekt</title>
    <link rel="stylesheet" href="../css/opcenito.css">
    <link rel="stylesheet" href="../css/detaljnije.css">
</head>
<body>
    <header>
            <nav>
                <ul>
                    <li><a href='../index.php'>Početna</a></li>                
                    <li class="desno"><a href='../obrasci/registracija.php'>Registracija</a></li>
                    <li class="desno"><a href='../obrasci/prijava.php'>Prijava</a></li>
                    <li><a href='vijesti.php'>Vijesti</a></li>
                    
                </ul>
            </nav>
        </header>

        <div id="datumkreiranja"><?php echo $red["datum_kreiranja"];?></div>

        <h1><?php echo $red["naslov"];?></h1>

        <div id="kategorija"><?php echo $red["naziv"];?></div>

        <img src="<?php echo $red["slika"]?>" width="40%">

        <div id="sadrzaj"><?php echo $red["sadrzaj"];?></div>

        <div id="autor">Autor vijesti: <?php echo $red["kor_ime"];?></div>

        <div id="tagovi">Tagovi: <?php echo $red["tagiranje"];?></div>

        <?php
        if($red["audio"]!=""){
        ?>
        <audio controls>
            <source src="<?php echo $red["audio"];?>" type="audio/mp3">
        </audio>
        <?php
        }
        ?>

        <?php
        if($red["video"]!=""){
        ?>
        <video controls width="30%">
            <source src="<?php echo $red["video"];?>" type="video/mp4" >
        </video>
        <?php
        }
        ?>

        <footer>

        </footer>
    
</body>
</html>

<?php

$baza ->zatvoriDB();
?>