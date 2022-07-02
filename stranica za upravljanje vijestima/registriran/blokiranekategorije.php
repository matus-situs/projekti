<?php
require_once("../vanjske_datoteke/baza.class.php");

$baza = new Baza();
$baza ->spojiDB();

session_start();

if(!isset($_SESSION["uloga"]) || $_SESSION["uloga"]!=3){
    header("Location: ../index.php");
}

$xmldata = simplexml_load_file("../xml/postavke.xml");
if($xmldata->onemoguceno==1){
    header("Location: nedostupno.html");
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Projekt</title>
    <link rel="stylesheet" href="../css/opcenito.css">
    <link rel="stylesheet" href="../css/tablice.css">
</head>
<body>
    <header>
            
            <nav>
                <ul>
                    <li><a href='../index.php'>Početna</a></li>
                    <?php
                    if($_SESSION["uloga"]==3){
                        echo "<li><a href='mojevjesti.php'>Moje vjesti</a></li>";
                        echo "<li><a href='registriran_statistika.php'>Statistika</a></li>";
                    }
                    if($_SESSION["uloga"]<4){
                        echo "<li class='desno'><a href='../index.php?odjava=1'>Odjava</a></li>";
                    }
                    ?>
                </ul>
            </nav>
            <h1>Blokirane kategorije</h1>
        </header>
        <table>
            <thead>
                <td>Blokirana kategorija</td>
                <td>Do kada traje blokada</td>
            </thead>
            <?php
            $upit = "SELECT kategorija.naziv, odbijeno.datum FROM blokirani_u_kategoriji INNER JOIN kategorija ON blokirani_u_kategoriji.kategorija_id=kategorija.id "
            ."INNER JOIN korisnici ON korisnici.id=blokirani_u_kategoriji.blokiran_korisnik_id INNER JOIN odbijeno ON odbijeno.blokirani_korisnik=korisnici.id"
            ." WHERE korisnici.id=".$_SESSION["idKorisnika"];
            $podaci = $baza->selectDB($upit);
            if(mysqli_num_rows($podaci)==0){
                echo "<tr><td colspan='2'>Niste blokirani u nijednoj kategoriji</td></tr>";
            }
            while($red=mysqli_fetch_assoc($podaci)){
                echo "<tr><td>".$red["naziv"]."</td><td>".$red["datum"]."</td></tr>";
            }
            ?>
        </table>


        <footer>

        </footer>
    
</body>
</html>

<?php

$baza ->zatvoriDB();
?>