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
    <link rel="stylesheet" href="../css/vijesti.css">
</head>
<body>
    <header>
            
            <nav>
                <ul>
                    <li><a href='../index.php'>Početna</a></li>
                    <?php
                    if($_SESSION["uloga"]==4){
                        echo "<li class='desno'><a href='../obrasci/registracija.php'>Registracija</a></li>";
                        echo "<li class='desno'><a href='../obrasci/prijava.php'>Prijava</a></li>";
                    }
                    if($_SESSION["uloga"]<4){
                        echo "<li class='desno'><a href='../index.php?odjava=1'>Odjava</a></li>";
                    }
                    ?>
                </ul>
            </nav>
            <h1>Vijesti</h1>
        </header>

        <form method='GET'>
            <label for='sort'>Uzlazno/silazno:</label>
            <select id='sort' name='sort'>
                <option value='asc'>Uzlazno</option>
                <option value='desc'>Silazno</option>
            </select>
            <label for="kriterij">Kriterij</label>
            <select id="kriterij" name="kriterij">
                <option value="naslov">Naslov</option>
                <option value="broj_pregleda">Broj pregleda</option>
            </select>

            <label for='tag'>Prema kriteriju:</label>
            <select id='tag' name='tag'>
                <?php
                $tagovi = array();
                $upit="SELECT vijesti.tagiranje FROM vijesti WHERE vijesti.status_vijesti=1";
                $podaci = $baza->selectDB($upit);
                while ($red = mysqli_fetch_assoc($podaci)){
                    $temp = explode(";", $red["tagiranje"]);
                    foreach($temp as $vrijednost){
                        if(!in_array($vrijednost, $tagovi, true)){
                            array_push($tagovi, $vrijednost);
                        }
                    }
                }
                for($i=0;$i<sizeof($tagovi)-1; $i++){
                    echo "<option value='".$tagovi[$i]."'>".$tagovi[$i]."</option>";
                }
                
                
                ?>                   
            </select>

            <input type='submit' name='filtriraj' value='Filtriraj' />
        </form>

        <table>
            <thead>
                <td>Naslov vijesti</td>
                <td>Slika</td>
                <td>Broj pregleda</td>
                <td>Datum vijesti</td>
            </thead>

        <?php
        $upit="SELECT * FROM vijesti WHERE status_vijesti=1";

        if(isset($_GET["sort"]) && isset($_GET["kriterij"])){
            $upit = "SELECT * FROM vijesti WHERE vijesti.tagiranje LIKE '%".$_GET["tag"]."%' AND status_vijesti=1 ORDER BY vijesti.".$_GET["kriterij"]." ".$_GET["sort"];
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
        if($brojRezultata>0){
            while ($red = mysqli_fetch_assoc($podaci)){
                echo "<tr><td><a class='naslov' href='detaljnije.php?vijest=".$red["id"]."'>".$red["naslov"]."</td>"
                ."<td><img src='".$red["slika"]."' height='50'></td>"
                ."<td>".$red["broj_pregleda"]."</td>"
                ."<td>".$red["datum_kreiranja"]."</td></tr>";
            }
        }
        else{
            echo "<tr><td colspan='4'>Niste napisali nijednu vijest</td></tr>";
        }
        
        
        ?>

        </table>

        <?php
        for($trenutnaStranica =1; $trenutnaStranica<=$brojStranica; $trenutnaStranica++){
            if(!isset($_GET["sort"]) && !isset($_GET["tip"])){
                echo "<a href='vijesti.php?stranica=".$trenutnaStranica."'>".$trenutnaStranica."</a>";
            }
            else{
                echo "<a href='vijesti.php?stranica=".$trenutnaStranica."&sort=".$_GET["sort"]."&tip=".$_GET["kriterij"]."&tag=".$_GET["tag"]."'>".$trenutnaStranica."</a>";
            }            
        }
        echo "<br>Trenutna stranica: ".$_GET["stranica"];
        ?>


        <footer>

        </footer>
    
</body>
</html>

<?php

$baza ->zatvoriDB();
?>