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
</head>
<body>
    <header>
            
            <nav>
                <ul>
                    <li><a href='../index.php'>Početna</a></li>
                    <?php
                    if($_SESSION["uloga"]==2){
                        echo "<li><a href='vijestimoderator.php'>Vijesti za recenziju</a></li>";
                        echo "<li><a href='odbijeno.php'>Odbijene vjesti</a></li>";
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
        <table>
            <thead>
                <td>Broj prihvaćenih vijesti</td>
                <td>Broj odbijenih vjesti</td>
            </thead>

        <?php
        $upit = "SELECT COUNT(vijesti.status_vijesti) FROM vijesti WHERE status_vijesti=1";
        $podaci = $baza->selectDB($upit);
        $red=mysqli_fetch_assoc($podaci);
        echo "<tr><td>".$red["COUNT(vijesti.status_vijesti)"]."</td>";

        $upit = "SELECT COUNT(vijesti.status_vijesti) FROM vijesti WHERE status_vijesti=2";
        $podaci = $baza->selectDB($upit);
        $red=mysqli_fetch_assoc($podaci);
        echo "<td>".$red["COUNT(vijesti.status_vijesti)"]."</td></tr>";
        
        ?>
        </table>
        </div>

        <div id="gumbovi">
        <button id="printBtn" name="printBtn" onclick="isprintajStatistiku('isprintaj')">Isprintaj podatke</button>
        <button id="PDFBtn" name="PDFBtn" onclick="generirajPDF('isprintaj')">Skini PDF podataka</button>
        </div>

        <footer>

        </footer>
    
</body>
</html>

<?php

$baza ->zatvoriDB();
?>