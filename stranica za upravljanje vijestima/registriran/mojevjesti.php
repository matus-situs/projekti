<?php
require_once("../vanjske_datoteke/baza.class.php");

$baza = new Baza();
$baza ->spojiDB();

session_start();
$poruka="";
$kreiranomsg="";

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

$krivoIspunjeno = array();
$azuriranje = array();
if(isset($_POST["naslov"])){
    $dozvoljeneEkstenzijeSlika = array("png", "jpg", "jpeg");
    $dozvoljenaEkstenzijaAudia = "mp3";
    $dozvoljeneEkstenzijeVidea = array("mp4", "avi","mpg", "m4v");
    $slika = $_FILES["slika"]["name"];
    $audio = $_FILES["audio"]["name"];
    $video = $_FILES["video"]["name"];

    array_push($krivoIspunjeno, $_POST["naslov"], $_POST["sadrzaj"], $_POST["izvor"], $_POST["kategorija"], $_POST["tagiranje"]);

    if(empty($_POST["naslov"])){
        $poruka .= "Naslov ne smije biti prazan!<br>";
    }
    if(strpos($_POST["naslov"], "<") !==false || strpos($_POST["naslov"], ">") !==false || strpos($_POST["naslov"], "'") !==false || strpos($_POST["naslov"], '"') !==false){
        $poruka .= "Naslov ne smije sadržavati znakove <, >, ', "."'"."!<br>";
    }
    if(empty($_POST["sadrzaj"])){
        $poruka .= "Sadržaj ne smije biti prazan<br>";
    }
    if(strpos($_POST["sadrzaj"], "<") !==false || strpos($_POST["sadrzaj"], ">") !==false || strpos($_POST["sadrzaj"], "'") !==false || strpos($_POST["sadrzaj"], '"') !==false){
        $poruka .= "Sadržaj ne smije sadržavati znakove <, >, ', "."'"."!<br>";
    }
    if(strpos($_POST["izvor"], "<") !==false || strpos($_POST["izvor"], ">") !==false || strpos($_POST["izvor"], "'") !==false || strpos($_POST["izvor"], '"') !==false){
        $poruka .= "Izvor ne smije sadržavati znakove <, >, ', "."'"."!<br>";
    }
    if($_POST["kategorija"]=="Bez kategorije"){
        $poruka .= "Kategorija mora biti odabrana!<br>";
    }
    if(empty($slika)){
        $poruka .= "Slika mora biti odabrana!<br>";
    }
    if(strpos($slika, "<") !==false || strpos($slika, ">") !==false || strpos($slika, "'") !==false || strpos($slika, '"') !==false){
        $poruka .= "Slika ne smije sadržavati znakove <, >, ', "."'"."!<br>";
    }
    
    $ekstenzija = pathinfo($slika, PATHINFO_EXTENSION);
    if (!in_array($ekstenzija, $dozvoljeneEkstenzijeSlika) && !empty($slika)) {
        $poruka .="Kriva ekstenzija slike, mora biti jpg, png ili jpeg!<br>";
    }
    else
    {
        if ($_FILES['slika']['error'] !== UPLOAD_ERR_OK && !empty($_FILES['slika']['name'])) {
            $poruka = "Slika je prevelika, odaberite manju sliku!<br>";
        }
    }

    if(strpos($_POST["tagiranje"], "<") !==false || strpos($_POST["tagiranje"], ">") !==false || strpos($_POST["tagiranje"], "'") !==false || strpos($_POST["tagiranje"], '"') !==false){
        $poruka .= "Tagiranje ne smije sadržavati znakove <, >, ', "."'"."!<br>";
    }

    if(strpos($audio, "<") !==false || strpos($audio, ">") !==false || strpos($audio, "'") !==false || strpos($audio, '"') !==false){
        $poruka .= "Audio naziv ne smije sadržavati znakove <, >, ', "."'"."!<br>";
    }
    
    $ekstenzija = pathinfo($audio, PATHINFO_EXTENSION);
    if ($ekstenzija != $dozvoljenaEkstenzijaAudia && !empty($audio)) {
        $poruka .="Kriva ekstenzija audia, mora biti mp3!<br>";
    }
    else
    {
        if ($_FILES['audio']['error'] !== UPLOAD_ERR_OK && !empty($_FILES['audio']['name'])) {
            $poruka = "Audio je prevelik, odaberite manji audio datoteku!<br>";
        }
    }

    if(strpos($video, "<") !==false || strpos($video, ">") !==false || strpos($video, "'") !==false || strpos($video, '"') !==false){
        $poruka .= "Video naziv ne smije sadržavati znakove <, >, ', "."'"."!<br>";
    }
    
    $ekstenzija = pathinfo($video, PATHINFO_EXTENSION);
    if (!in_array($ekstenzija, $dozvoljeneEkstenzijeVidea) && !empty($video)) {
        $poruka .="Kriva ekstenzija videa, mora biti mp4, avi, mpg ili m4v!<br>";
    }
    else
    {
        if ($_FILES['video']['error'] !== UPLOAD_ERR_OK && !empty($_FILES['video']['name'])) {
            $poruka = "Video je prevelik, odaberite manji video!<br>";
        }
    }

    if($poruka==""){
        $krivoIspunjeno = array();
        $datum = explode(" ", $_POST["datum"]);
        $datum = explode("/", $datum[0]);
        $dir="../materijali/vijest ".$datum[2].".".$datum[1].".".$datum[0].". - ".$_POST["naslov"]."/";

        if (!file_exists( $dir ) && !is_dir( $dir )) {
            mkdir($dir, 0777, true);
        } 

        $fajlslika = $dir.basename($_FILES["slika"]["name"]);
        if(file_exists($fajlslika)){
            $fajlslika=$dir.$datum[2].".".$datum[1].".".$datum[0].".".basename($_FILES["slika"]["name"]);
        }
        move_uploaded_file($_FILES["slika"]["tmp_name"], $fajlslika);
        chmod($fajlslika, 0777);
        
        if(!empty($_FILES["audio"]["name"])){
            $fajlaudio = $dir.basename($_FILES["audio"]["name"]);
            if(file_exists($fajlaudio)){
                $fajlaudio=$dir.$datum[2].".".$datum[1].".".$datum[0].".".basename($_FILES["audio"]["name"]);
            }
            move_uploaded_file($_FILES["audio"]["tmp_name"], $fajlaudio);
            chmod($fajlaudio, 0777);
        }
        else{
            $fajlaudio="";
        }

        
        if(!empty($_FILES["video"]["name"])){
            $fajlvideo = $dir.basename($_FILES["video"]["name"]);
            if(file_exists($fajlvideo)){
                $fajlvideo=$dir.$datum[2].".".$datum[1].".".$datum[0].".".basename($_FILES["video"]["name"]);
            }
            move_uploaded_file($_FILES["video"]["tmp_name"], $fajlvideo);
            chmod($fajlvideo, 0777);
        }
        else{
            $fajlvideo="";
        }
        $upit = "SELECT * FROM kategorija WHERE naziv='".$_POST["kategorija"]."'";
        $podaci = $baza->selectDB($upit);
        $red=mysqli_fetch_assoc($podaci);
        $kategorija=$red["id"];
        
        if($_POST["idvjesti"]>0){
            $upit="UPDATE vijesti SET naslov='".$_POST["naslov"]."',sadrzaj='".$_POST["sadrzaj"]."', izvor='".$_POST["izvor"]."', kategorija=".$kategorija.", tagiranje='".$_POST["tagiranje"]."', "
            ." slika='".$fajlslika."', audio='".$fajlaudio."', video='".$fajlvideo."', status_vijesti=3 WHERE id=".$_POST["idvjesti"];

            $baza->updateDB($upit);

            $sql ="INSERT INTO dnevnik_rada(`vrijeme`,`radnja`,`korisnici_id`)"
                            . " VALUES('".date('Y/m/d H/i/s', strtotime(" + ".$xmldata->pomakvremena." hours"))."','Ažurirana je vijest _".$_POST["naslov"]."_', '".$_SESSION["idKorisnika"]."')";
            $baza->updateDB($sql);
            $kreiranomsg="Uspješno ažurirana vjest!";

            echo "<script src='../javascript/msitaric_mojevijesti.js'>zatvoriNovaVijest();</script>";
        }else{
            $upit = "INSERT INTO vijesti(naslov, sadrzaj, datum_kreiranja, izvor, kategorija, autor, slika, tagiranje, audio, video, status_vijesti)"
            ." VALUES('".$_POST["naslov"]."', '".$_POST["sadrzaj"]."', '".$_POST["datum"]."', '".$_POST["izvor"]."', '".$kategorija."', '".$_SESSION["idKorisnika"]."', '".$fajlslika."',"
            ."'".$_POST["tagiranje"]."', '".$fajlaudio."', '".$fajlvideo."', 3)";
            $baza->updateDB($upit);

            $sql ="INSERT INTO dnevnik_rada(`vrijeme`,`radnja`,`korisnici_id`)"
                            . " VALUES('".date('Y/m/d H/i/s', strtotime(" + ".$xmldata->pomakvremena." hours"))."','Dodana je vijest _".$_POST["naslov"]."_', '".$_SESSION["idKorisnika"]."')";
            $baza->updateDB($sql);
            $kreiranomsg="Uspješno kreirana vjest!";

            echo "<script src='../javascript/msitaric_mojevijesti.js'>zatvoriNovaVijest();</script>";
        }
        
    }
}

if(isset($_GET["azuriraj"])){
    $upit="SELECT vijesti.*, kategorija.naziv FROM vijesti INNER JOIN kategorija ON vijesti.kategorija=kategorija.id WHERE vijesti.id=".$_GET["azuriraj"];
    $podaci = $baza->selectDB($upit);
    $red=mysqli_fetch_assoc($podaci);
    
    array_push($azuriranje, $red["naslov"], $red["sadrzaj"], $red["izvor"], $red["naziv"], $red["slika"], $red["tagiranje"], $red["audio"], $red["video"]);
    echo "<script src='../javascript/msitaric_mojevijesti.js'>NovaVijest()</script>";
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
    <link rel="stylesheet" href="../css/popupformamojevijesti.css">
    <link rel="stylesheet" href="../css/greske.css">
    <?php /*if(isset($_GET["azuriraj"])){ echo '<link rel="stylesheet" href="../css/azuriranje.css">';} 
    else echo '';*/?>
    <script src="../javascript/msitaric_mojevijesti.js"></script>
</head>
<body>
    <header>
            
            <nav>
                <ul>
                    <li><a href='../index.php'>Početna</a></li>
                    <?php
                    if($_SESSION["uloga"]==3){
                        echo "<li><a href='blokiranekategorije.php'>Blokirane kategorije</a></li>";
                        echo "<li><a href='registriran_statistika.php'>Statistika</a></li>";
                    }
                    if($_SESSION["uloga"]<4){
                        echo "<li class='desno'><a href='../index.php?odjava=1'>Odjava</a></li>";
                    }
                    ?>
                </ul>
            </nav>
            <h1>Moje vjesti</h1>
        </header>

        <?php if($kreiranomsg!=""){ ?>
            <div id="uspjesnoKreiranaVjest">
            <?php
                echo $kreiranomsg;
            ?>
            </div>
        <?php }?>
                
        <?php if($poruka!=""){ ?>
            <div id="greske">
            <?php
                echo $poruka;
            ?>
            </div>
            <?php }?>

            <?php if(isset($_GET["azuriraj"])){ ?>
                <button class="azurirajForm" onclick="NovaVijest()">Ažuriraj selektiranu vijest</button>
                <a href="mojevjesti.php" id="kreirajNovu">Želim kreirati novu vijest</a>
        <?php }else{?>

            <button class="otvoriNovaVijestForm" onclick="NovaVijest()">Kreiraj vijest</button>
        <?php } ?>
        <div class="formaPopup" id="vijestForm">
            <form method="POST" id="novaVijestForm" enctype="multipart/form-data" action="<?php echo $_SERVER["PHP_SELF"];?>">
                <h2>Kreacija vijesti</h2>

                

                <label for="naslov">Naslov</label>
                <input type="hidden" name="idvjesti" value="<?php
                if(isset($_GET["azuriraj"])) echo $_GET["azuriraj"]; else echo "0";;
                ?>">
                <input type="text" name="naslov" id="naslov" value="<?php 
                if(!empty($krivoIspunjeno)) echo $krivoIspunjeno[0];
                if(isset($_GET["azuriraj"])) echo $azuriranje[0];
                ?>">

                <label for="sadrzaj">Sadržaj</label>
                <textarea rows="20" cols="97" name="sadrzaj" id="sadrzaj"><?php 
                    if(!empty($krivoIspunjeno)) echo $krivoIspunjeno[1];
                    if(isset($_GET["azuriraj"])) echo $azuriranje[1];
                    ?></textarea>

                <input type="hidden" name="datum" id="datum" value="<?php echo date('Y/m/d H/i/s', strtotime(" + ".$xmldata->pomakvremena." hours"));?>">

                <label for="izvor">Izvor (opcionalno)</label>
                <input type="text" name="izvor" id="izvor" value="<?php 
                if(!empty($krivoIspunjeno)) echo $krivoIspunjeno[2];
                if(isset($_GET["azuriraj"])) echo $azuriranje[2];
                ?>">

                <label for="kategorija">Kategorija</label>
                <select id="kategorija" name="kategorija">
                    <?php
                        $upit = "SELECT * FROM kategorija LEFT JOIN blokirani_u_kategoriji ON kategorija.id=kategorija_id LEFT JOIN korisnici ON korisnici.id=blokiran_korisnik_id WHERE kategorija_id IS NULL";
                        $podaci = $baza->selectDB($upit);
                        while ($red = mysqli_fetch_assoc($podaci)){
                            if(!empty($krivoIspunjeno) && $red["naziv"]==$krivoIspunjeno[3]){
                                echo "<option value='".$red["naziv"]."' selected>".ucfirst($red["naziv"])."</option>";
                            }
                            else if(!empty($azuriranje) && $red["naziv"]==$azuriranje[3]){
                                echo "<option value='".$red["naziv"]."' selected>".ucfirst($red["naziv"])."</option>";
                            }
                            else{
                                echo "<option value='".$red["naziv"]."'>".ucfirst($red["naziv"])."</option>";
                            }                            
                        }
                    ?>
                    <option>Bez kategorije</option>
                </select><br>

                <label for="slika">Slika (png, jpg, jpeg, max 2MB)</label>
                <input type="file" name="slika" value="<?php
                if(isset($_GET["azuriraj"])) echo $azuriranje[4];
                ?>"><br>

                <label for="tagiranje">Tagiranje (opcionalno, svaki novai tag mora biti odvojen sa ;)</label>
                <input type="text" name="tagiranje" id="tagiranje" value="<?php 
                if(!empty($krivoIspunjeno)) echo $krivoIspunjeno[4];
                if(isset($_GET["azuriraj"])) echo $azuriranje[5];
                ?>">

                <label for="audio">Audio (opcionalno, samo mp3, max 2MB)</label><br>
                <input type="file" name="audio" id="audio" value="<?php
                if(isset($_GET["azuriraj"])) echo $azuriranje[6];
                ?>"><br>

                <label for="video">Video (opcionalno, mp4, avi, mpg, m4v, max 2MB)</label><br>
                <input type="file" name="video" id="video" value="<?php
                if(isset($_GET["azuriraj"])) echo $azuriranje[7];
                ?>"><br>
                
                <input type="submit" name="predajVijest" class="gumb" value="Predaj vijest">
                <button type="button" class="gumb odustani" onclick="zatvoriNovaVijest();">Odustani</button>
            </form>
        </div>

        <table>
            <thead>
                <td>Datum kreiranja vijesti</td>
                <td>Naslov vijesti</td>
                <td>Status vijesti</td>
                <td>Recenzija</td>
                <td>Verzija</td>
                <td>Ažuriranje</td>
            </thead>
            <?php
            $upit = "SELECT vijesti.id, vijesti.datum_kreiranja, vijesti.naslov, status_vijesti.naziv, recenzija.komentar, vijesti.verzija "
            ."FROM vijesti INNER JOIN status_vijesti ON status_vijesti.id=vijesti.status_vijesti LEFT JOIN recenzija ON recenzija.vijest=vijesti.id"
            ." WHERE autor=".$_SESSION["idKorisnika"];

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

            $podaci=$baza->selectDB($upit);
            if($brojRezultata>0){
                while($red=mysqli_fetch_assoc($podaci)){
                    if($red["naziv"]=="idenadoradu"){
                        $red["naziv"]="dorada";
                        echo "<tr class='reddorade'>";
                    }
                    else{
                        echo "<tr>";
                    }
                    echo "<td>".$red["datum_kreiranja"]."</td>"
                    ."<td>".$red["naslov"]."</td>"
                    ."<td>".$red["naziv"]."</td>"
                    ."<td>".$red["komentar"]."</td>"
                    ."<td>".$red["verzija"]."</td>";
                    if($red["naziv"]=="dorada"){
                        echo "<td><a class='dorada' href='mojevjesti.php?azuriraj=".$red["id"]."'>Ažuriraj</a></td></tr>";
                    }
                    else{
                        echo "<td>-</td></tr>";
                    }
                }
            }
            else{
                echo "<tr><td colspan='6'>Niste napisali nijednu vijest</td></tr>";
            }           

            ?>
        </table>

        <?php
        for($trenutnaStranica =1; $trenutnaStranica<=$brojStranica; $trenutnaStranica++){
            echo "<a class='stranica' href='mojevjesti.php?stranica=".$trenutnaStranica."'>".$trenutnaStranica."</a>";
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