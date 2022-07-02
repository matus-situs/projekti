<?php
require_once("../vanjske_datoteke/baza.class.php");

$baza = new Baza();
$baza ->spojiDB();

session_start();
$poruka="";

$xmldata = simplexml_load_file("../xml/postavke.xml");
if($xmldata->onemoguceno==1){
    header("Location: ../nedostupno.html");
}

if(!isset($_SESSION["uloga"]) || $_SESSION["uloga"]!=1){
    header("Location: ../index.php");
}

if(!isset($_GET["stranica"])){
    $_GET["stranica"]=1;
}

if(isset($_POST["nazivkategorije"])){
    $brojGresakaUnosa=0;
    
    if(empty($_POST["nazivkategorije"])){
        $poruka  .= "Naziv kategorije ne smije biti prazno!<br>";
        $brojGresakaUnosa++;
    }
    if(strpos($_POST["nazivkategorije"], "<") !==false || strpos($_POST["nazivkategorije"], ">") !==false || strpos($_POST["nazivkategorije"], "'") !==false || strpos($_POST["nazivkategorije"], '"') !==false){
        $poruka  .= "Naziv kategorije ne smije sadržavati znakove <, >, ', "."'"."!<br>";
        $brojGresakaUnosa++;
    }

    if($brojGresakaUnosa==0){
        $upit="SELECT * from kategorija WHERE naziv='".$_POST["nazivkategorije"]."'";
        $podaci = $baza->updateDB($upit);
        if(mysqli_num_rows($podaci)>0){
            $poruka="Već postoji upisana kategorija!";
        } else{
            $upit = "INSERT INTO kategorija(`naziv`) VALUES ('".$_POST["nazivkategorije"]."')";
            $podaci = $baza->updateDB($upit);

            $upit ="INSERT INTO dnevnik_rada(`vrijeme`,`radnja`,`korisnici_id`)"
                            . " VALUES('".date('Y/m/d H/i/s', strtotime(" + ".$xmldata->pomakvremena." hours"))."','Unesena je kategorija _".$_POST["nazivkategorije"]."_', '".$_SESSION["idKorisnika"]."')";
          
            $baza->updateDB($upit);
            header("Location: kategorije.php");
    }
}

    
}
if(isset($_GET["kategorijabrisi"])){
    $brojGresakaUnosa=0;
    
    if(empty($_GET["kategorijabrisi"])){
        $poruka  .= "Naziv kategorije ne smije biti prazno!<br>";
        $brojGresakaUnosa++;
    }
    if(strpos($_GET["kategorijabrisi"], "<") !==false || strpos($_GET["kategorijabrisi"], ">") !==false || strpos($_GET["kategorijabrisi"], "'") !==false || strpos($_GET["kategorijabrisi"], '"') !==false){
        $poruka  .= "Naziv kategorije ne smije sadržavati znakove <, >, ', "."'"."!<br>";
        $brojGresakaUnosa++;
    }

    if($brojGresakaUnosa==0){
        $upit = "SELECT * FROM kategorija WHERE id=".$_GET["kategorijabrisi"];
        $podaci = $baza->selectDB($upit);
        $red = mysqli_fetch_assoc($podaci);

        $upit = "DELETE from korisnici_has_kategorija WHERE kategorija_id='".$_GET["kategorijabrisi"]."'";
        $baza->updateDB($upit);

        $upit = "DELETE from kategorija WHERE id='".$_GET["kategorijabrisi"]."'";
        $baza->updateDB($upit);

        $sql ="INSERT INTO dnevnik_rada(`vrijeme`,`radnja`,`korisnici_id`)"
                            . "VALUES('".date('Y/m/d H/i/s', strtotime(" + ".$xmldata->pomakvremena." hours"))."','Obrisana je kategorija _".$red["naziv"]."_', '".$_SESSION["idKorisnika"]."')";
            
        $baza->updateDB($sql);

    header("Location: kategorije.php");
    }
}

if(isset($_POST["popisKorisnika"])){
    $brojGresakaUnosa=0;
    
    if(empty($_POST["popisKorisnika"])){
        $poruka  .= "Naziv korisnika ne smije biti prazno!<br>";
        $brojGresakaUnosa++;
    }
    if(strpos($_POST["popisKorisnika"], "<") !==false || strpos($_POST["popisKorisnika"], ">") !==false || strpos($_POST["popisKorisnika"], "'") !==false || strpos($_POST["popisKorisnika"], '"') !==false){
        $poruka  .= "Naziv korisnika ne smije sadržavati znakove <, >, ', "."'"."!<br>";
        $brojGresakaUnosa++;
    }
    if($brojGresakaUnosa==0){
        $upit = "SELECT korisnici.kor_ime FROM korisnici WHERE uloga_id='2' AND kor_ime='".$_POST["popisKorisnika"]."'";
        $podaci = $baza->selectDB($upit);
        if(mysqli_num_rows($podaci)==0){
            $poruka="Ne postoji uneseni korisnik!";
        }
        else{
            $upit = "SELECT * FROM korisnici INNER JOIN korisnici_has_kategorija ON korisnici.id=korisnici_has_kategorija.korisnici_id "
                ."WHERE korisnici_has_kategorija.kategorija_id=".$_POST["kategorijazadodat"]." && korisnici.kor_ime='".$_POST["popisKorisnika"]."' && korisnici.uloga_id='2'";

            $podaci = $baza->selectDB($upit); 
            if(mysqli_num_rows($podaci)==0){
                $upit = "SELECT * FROM korisnici WHERE kor_ime='".$_POST["popisKorisnika"]."'";
            
                $podaci = $baza->selectDB($upit);
                $red = mysqli_fetch_assoc($podaci);

                $upit = "INSERT INTO korisnici_has_kategorija(korisnici_id, kategorija_id) VALUES (".$red["id"].", ".$_POST["kategorijazadodat"].")";
                $baza->updateDB($upit);

                $upit = "SELECT * FROM kategorija WHERE id=".$_POST["kategorijazadodat"];
                $podaci = $baza->selectDB($upit);
                $red = mysqli_fetch_assoc($podaci);

                $sql = "INSERT INTO dnevnik_rada(`vrijeme`,`radnja`,`korisnici_id`)"
                . " VALUES('".date('Y/m/d H/i/s', strtotime(" + ".$xmldata->pomakvremena." hours"))."','Dodjeljeno je korisniku ".$_POST["popisKorisnika"]." kategorija _".$red["naziv"]."_', '".$_SESSION["idKorisnika"]."')";
                $baza->updateDB($sql);
                header("Location: kategorije.php");
            }
            else{
            $poruka="Korisnik kojeg ste pokušali unjeti je već unesen za odaberenu kategoriju!";
            }
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
file_put_contents("../json/searchKorisnici.json", $jsonPodaci);
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
    <script src="../javascript/msitaric_kategorije.js"></script>
    <script type="text/javascript" src="../javascript/msitaric_kategorije_jquery.js"></script>
    <link rel="stylesheet" href="../css/opcenito.css">
    <link rel="stylesheet" href="../css/stranicenje.css">
    <link rel="stylesheet" href="../css/tablice.css">
    <link rel="stylesheet" href="../css/greske.css">
</head>
<body>
    <header>
            
            <nav>
                <ul>
                    <li><a href='../index.php'>Početna</a></li>
                    <?php
                    if($_SESSION["uloga"]==1){
                        echo "<li><a href='dnevnik.php'>Dnevnik</a></li>";
                        echo "<li><a href='blokirani_korisnici.php'>Blokirani korisnici</a></li>";
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
            <h1>Kategorije</h1>
        </header>

        <?php if($poruka!=""){ ?>
        <div id="greske">
            <?php
            echo $poruka;
            ?>
        </div>
        <?php }?>

        <form method="POST" id="kategorijeForm" action="<?php echo $_SERVER["PHP_SELF"];?>">
            <label for="nazivkategorije">Unesite naziv nove kategorije</label>
            <input type="text" id="nazivkategorije" name="nazivkategorije" maxlength="45"/>
            <input type="submit" id="unoskategorije" value="Unesi kategoriju">
        </form>

        <table>
            <thead>
                <td>Naziv kategorije</td>
                <td>Brisanje kategorije</td>
                <td>Pridruži korisnika</td>
                <td>Korisnici kategorije</td>
            </thead>
        <?php
        $upit="SELECT * FROM kategorija";

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
            $upit = "SELECT korisnici.kor_ime FROM korisnici INNER JOIN korisnici_has_kategorija ON korisnici.id=korisnici_has_kategorija.korisnici_id "
            ."WHERE korisnici_has_kategorija.kategorija_id=".$red["id"];

            $podaci2 = $baza->selectDB($upit);
            
            echo "<tr><td>".$red["naziv"]."</td><td><a href='kategorije.php?kategorijabrisi=".$red["id"]."'>Obriši kategoriju</a></td>"
                ."<td><form method='POST' id='dodavanjeKorisnikaForm".$i."' action='".$_SERVER["PHP_SELF"]."'>"
                ."<input type='text' class='popisKorisnika' name='popisKorisnika'>"
                ."<input type='hidden' class='kategorijazadodat' name='kategorijazadodat' value='".$red["id"]."'>"
                ."<input type='submit' id='dodajkorisnika".$i."' value='Dodaj korisnika' onclick='unos_korisnika(dodavanjeKorisnikaForm".$i.")'>"
                ."</form></td>";

            
            if(mysqli_num_rows($podaci2)==0){
                echo "<td>Nema korisnika u kategoriji</td></tr>";
            } else{
                echo "<td>";
                while($korisnik = mysqli_fetch_assoc($podaci2)){
                    echo $korisnik["kor_ime"].", ";
                }
                echo "</td></tr>";
            }

            $i++;
        }
        ?>
        </table>

        <?php
        for($trenutnaStranica =1; $trenutnaStranica<=$brojStranica; $trenutnaStranica++){
            echo "<a class='stranica' href='kategorije.php?stranica=".$trenutnaStranica."'>".$trenutnaStranica."</a>";
        }
        echo "<br> <div id='trenutno'>Trenutna stranica: ".$_GET["stranica"]."</div>";
        ?>

        <footer>

        </footer>
    
</body>
</html>

<?php

$baza ->zatvoriDB();
?>