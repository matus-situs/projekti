<?php
require_once("../vanjske_datoteke/baza.class.php");

$baza = new Baza();
$baza ->spojiDB();

session_start();

if(!isset($_SESSION["uloga"]) || $_SESSION["uloga"]!=1){
    header("Location: ../index.php");
}

$xmldata = simplexml_load_file("../xml/postavke.xml");

$poruka="";

if(isset($_POST["gumbPromjeni"])){
    if($xmldata->onemoguceno==0){
        $xmldata->onemoguceno=1;
        $xmldata->asXML('../xml/postavke.xml');
    }
}
if(isset($_POST["gumbSpremi"])){
        $brojGresakaUnosa=0;
    if(empty($_POST["rezultatipostranici"])){
        $poruka  .= "Rezultati po stranici polje ne smije biti prazno!<br>";
        $brojGresakaUnosa++;
    }
    if(strpos($_POST["rezultatipostranici"], "<") !==false || strpos($_POST["rezultatipostranici"], ">") !==false || strpos($_POST["rezultatipostranici"], "'") !==false || strpos($_POST["rezultatipostranici"], '"') !==false){
        $poruka  .= "Rezultati po stranici polje ne smije sadržavati znakove <, >, ', "."'"."!<br>";
        $brojGresakaUnosa++;
    }
    if(!ctype_digit($_POST["rezultatipostranici"])){
        $poruka  .= "Rezultati po stranici polje smije sadržavati samo brojeve!<br>";
        $brojGresakaUnosa++;
    }
    if(empty($_POST["trajanjekolacica"])){
        $poruka  .= "Trajanje kolačića polje ne smije biti prazno!<br>";
        $brojGresakaUnosa++;
    }
    if(strpos($_POST["trajanjekolacica"], "<") !==false || strpos($_POST["trajanjekolacica"], ">") !==false || strpos($_POST["trajanjekolacica"], "'") !==false || strpos($_POST["trajanjekolacica"], '"') !==false){
        $poruka  .= "Trajanje kolačića polje ne smije sadržavati znakove <, >, ', "."'"."!<br>";
        $brojGresakaUnosa++;
    }
    if(!ctype_digit($_POST["trajanjekolacica"])){
        $poruka  .= "Trajanje kolačića polje smije sadržavati samo brojeve!<br>";
        $brojGresakaUnosa++;
    }
    if(empty($_POST["brojprijava"])){
        $poruka  .= "Broj prijava polje ne smije biti prazno!<br>";
        $brojGresakaUnosa++;
    }
    if(strpos($_POST["brojprijava"], "<") !==false || strpos($_POST["brojprijava"], ">") !==false || strpos($_POST["brojprijava"], "'") !==false || strpos($_POST["brojprijava"], '"') !==false){
        $poruka  .= "Broj prijava polje ne smije sadržavati znakove <, >, ', "."'"."!<br>";
        $brojGresakaUnosa++;
    }
    if(!ctype_digit($_POST["brojprijava"])){
        $poruka  .= "Broj prijava polje smije sadržavati samo brojeve!<br>";
        $brojGresakaUnosa++;
    }
    if(empty($_POST["vrijemesesije"])){
        $poruka  .= "Vrijeme trajanja sesije polje ne smije biti prazno!<br>";
        $brojGresakaUnosa++;
    }
    if(strpos($_POST["vrijemesesije"], "<") !==false || strpos($_POST["vrijemesesije"], ">") !==false || strpos($_POST["vrijemesesije"], "'") !==false || strpos($_POST["vrijemesesije"], '"') !==false){
        $poruka  .= "Vrijeme trajanja sesije polje ne smije sadržavati znakove <, >, ', "."'"."!<br>";
        $brojGresakaUnosa++;
    }
    if(!ctype_digit($_POST["vrijemesesije"])){
        $poruka  .= "Vrijeme trajanja sesije polje smije sadržavati samo brojeve!<br>";
        $brojGresakaUnosa++;
    }
    if(empty($_POST["vrijemeaktivacije"])){
        $poruka  .= "Vrijeme za aktivaciju polje ne smije biti prazno!<br>";
        $brojGresakaUnosa++;
    }
    if(strpos($_POST["vrijemeaktivacije"], "<") !==false || strpos($_POST["vrijemeaktivacije"], ">") !==false || strpos($_POST["vrijemeaktivacije"], "'") !==false || strpos($_POST["vrijemeaktivacije"], '"') !==false){
        $poruka  .= "Vrijeme za aktivaciju polje ne smije sadržavati znakove <, >, ', "."'"."!<br>";
        $brojGresakaUnosa++;
    }
    if(!ctype_digit($_POST["vrijemeaktivacije"])){
        $poruka  .= "Vrijeme za aktivaciju polje smije sadržavati samo brojeve!<br>";
        $brojGresakaUnosa++;
    }
    if($brojGresakaUnosa==0){
        $xmldata->rezultatipostranici=$_POST["rezultatipostranici"];
        $xmldata->trajanjekolacica=$_POST["trajanjekolacica"];
        $xmldata->brojprijava=$_POST["brojprijava"];
        $xmldata->vrijemesesije=$_POST["vrijemesesije"];
        $xmldata->vrijemeaktivacije=$_POST["vrijemeaktivacije"];
        $xmldata->onemoguceno=0;
        $xmldata->asXML('../xml/postavke.xml');
        $sql = "INSERT INTO dnevnik_rada(`vrijeme`,`radnja`,`korisnici_id`)"
            . " VALUES('".date('Y/m/d H/i/s', strtotime(" + ".$xmldata->pomakvremena." hours"))."','Korisnik je izmjenio postavke sustava', '".$_SESSION["idKorisnika"]."')";
            $baza->updateDB($sql);
    }
}   

if(isset($_POST["gumbOsvjezi"])){
    $url="http://barka.foi.hr/WebDiP/pomak_vremena/pomak.php?format=xml";
    $pomak = file_get_contents($url);
    $pomak=explode("<brojSati>", $pomak);
    $xmldata->pomakvremena=substr($pomak[1], 0, 1);
    $xmldata->asXML('../xml/postavke.xml');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Projekt</title>
    <link rel="stylesheet" href="../css/opcenito.css">
    <link rel="stylesheet" href="../css/greske.css">
    <link rel="stylesheet" href="../css/postavke.css">
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
                        echo "<li><a href='vijesti_recenzija.php'>Vijesti za recenziju</a></li>";
                    }
                    if($_SESSION["uloga"]<4){
                        echo "<li class='desno'><a href='../index.php?odjava=1'>Odjava</a></li>";
                    }
                    ?>
                </ul>
            </nav>
            <h1>Postavke sustava</h1>
        </header>

        <?php if($poruka!=""){ ?>
        <div id="greska">
            <?php
                echo $poruka;
            ?>
        </div>
        <?php }?>

        <form method="POST" action="<?php echo $_SERVER["PHP_SELF"];?>">
        <div>
            <label for="rezultatipostranici">Broj prikazanih rezultata po stranici:</label>        
            <input type="text" name="rezultatipostranici" 
            <?php 
            echo "value='".$xmldata->rezultatipostranici."'";
            if($xmldata->onemoguceno==0){
                echo " disabled";
            }
            ?>>
            </div>

            <div>
            <label for="trajanjekolacica">Trajanje kolačića u satima:</label>        
            <input type="text" name="trajanjekolacica" 
            <?php 
            echo "value='".$xmldata->trajanjekolacica."'";
            if($xmldata->onemoguceno==0){
                echo " disabled";
            }
            ?>>
            </div>

            <div>
            <label for="brojprijava">Maksimalan broj prijava prije nego li korisnik bude blokiran:</label>        
            <input type="text" name="brojprijava" 
            <?php 
            echo "value='".$xmldata->brojprijava."'";
            if($xmldata->onemoguceno==0){
                echo " disabled";
            }
            ?>>
            </div>

            <div>
            <label for="vrijemesesije">Vrijeme trajanja sesije u minutama:</label>        
            <input type="text" name="vrijemesesije" 
            <?php 
            echo "value='".$xmldata->vrijemesesije."'";
            if($xmldata->onemoguceno==0){
                echo " disabled";
            }
            ?>>
            </div>

            <div>
            <label for="vrijemeaktivacije">Vrijeme potrebno za aktivaciju računa:</label>        
            <input type="text" name="vrijemeaktivacije" 
            <?php 
            echo "value='".$xmldata->vrijemeaktivacije."'";
            if($xmldata->onemoguceno==0){
                echo " disabled";
            }
            ?>>
            </div>

            <div>
            <label for="pomakvremena">Pomak virtualnog vremena:</label>
            <input type="text" <?php echo "value='".$xmldata->pomakvremena."'"?> disabled>
            <?php
            if($xmldata->onemoguceno==0){
                echo "<input id='gumb' name='gumbPromjeni' type='submit' value='Izmjeni postavke'>";
            }
            else{
                echo "<input id='gumb' name='gumbSpremi' type='submit' value='Spremi promjene'>";
            }
            ?>    
            </div>        
        </form>
        
        <div id="pomak">Nakon upisivanja broja sati pomaka vremena na stranici i klikom na dodaj, molim da se vratite na ovu stranicu i kliknete na gumb osvježi.</div>
        <form method="POST" action="<?php echo $_SERVER["PHP_SELF"];?>">
            <input name='gumbOsvjezi' type='submit' value='Osvježi stranicu'>
        </form>
        <a id='pomak' href="http://barka.foi.hr/WebDiP/pomak_vremena/vrijeme.html" target="_blank">Stranica za pomak lokalnog vremena</a>


        <footer>

        </footer>
    
</body>
</html>

<?php

$baza ->zatvoriDB();
?>