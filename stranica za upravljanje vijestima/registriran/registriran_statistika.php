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
if(!isset($_GET["stranica"])){
    $_GET["stranica"]=1;
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Projekt</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="../javascript/msitaric_registriran_statistika.js"></script>
    <link rel="stylesheet" href="../css/opcenito.css">
    <link rel="stylesheet" href="../css/tablice.css">
    <link rel="stylesheet" href="../css/ispis.css">
    <link rel="stylesheet" href="../css/stranicenje.css">
</head>
<body>
    <header>
            
            <nav>
                <ul>
                    <li><a href='../index.php'>Početna</a></li>
                    <?php
                    if($_SESSION["uloga"]==3){
                        echo "<li><a href='mojevjesti.php'>Moje vjesti</a></li>";
                        echo "<li><a href='blokiranekategorije.php'>Blokirane kategorije</a></li>";
                    }
                    if($_SESSION["uloga"]<4){
                        echo "<li class='desno'><a href='../index.php?odjava=1'>Odjava</a></li>";
                    }
                    ?>
                </ul>
            </nav>
            <h1>Statistika</h1>
        </header>
        <div id="isprintaj">
        <table id="registriranistatistika">
            <thead>
                <td>Naslov vijesti</td>
                <td>Broj pregleda</td>
            </thead>
            <?php
            $upit = "SELECT korisnici.id, vijesti.naslov, vijesti.broj_pregleda FROM vijesti INNER JOIN korisnici ON korisnici.id=vijesti.autor WHERE vijesti.status_vijesti=1 AND korisnici.id=".$_SESSION["idKorisnika"];
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

            if(mysqli_num_rows($podaci)==0){
                echo "<tr><td>Niste napisali još nijednu vijest</td></tr>";
            }
            else{
                while($red=mysqli_fetch_assoc($podaci)){
                    echo "<tr><td>".$red["naslov"]."</td><td>".$red["broj_pregleda"]."</td></tr>";
                }
            }
            
            ?>
        </table>
        </div>
        <?php
        for($trenutnaStranica =1; $trenutnaStranica<=$brojStranica; $trenutnaStranica++){
            echo "<a class='stranica' href='kategorije.php?stranica=".$trenutnaStranica."'>".$trenutnaStranica."</a>";
        }
        echo "<br> <div id='trenutno'>Trenutna stranica: ".$_GET["stranica"]."</div>";
        ?>
        <button id="printBtn" name="printBtn" onclick="isprintajStatistiku('isprintaj')">Isprintaj podatke</button>
        <button id="PDFBtn" name="PDFBtn" onclick="generirajPDF('isprintaj')">Skini PDF podataka</button>
        

        <footer>

        </footer>
    
</body>
</html>

<?php

$baza ->zatvoriDB();
?>