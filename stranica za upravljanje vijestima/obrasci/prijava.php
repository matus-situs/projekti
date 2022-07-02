<?php
require_once("../vanjske_datoteke/baza.class.php");

$baza = new Baza();
$baza ->spojiDB();

$xmldata = simplexml_load_file("../xml/postavke.xml");
if($xmldata->onemoguceno==1){
    header("Location: ../nedostupno.html");
}

session_start();

if(!isset($_SERVER["HTTPS"])){
    $siguranLink = "https://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
    header("Location: ".$siguranLink);
}

if(!isset($_SESSION["uloga"])){
    $_SESSION["uloga"]=4;
}



$pokusajText="";
$poruka="";
$krivoIspunjeno = array();

if(isset($_POST["korime"])){
    $brojGresakaUnosa=0;
    array_push($krivoIspunjeno, $_POST["korime"], $_POST["lozinka"]);
    if(empty($_POST["korime"])){
        $poruka  .= "Molim unesite korisničko ime!<br>";
        $brojGresakaUnosa++;
    }
    if(strpos($_POST["korime"], "<") !==false || strpos($_POST["korime"], ">") !==false || strpos($_POST["korime"], "'") !==false || strpos($_POST["korime"], '"') !==false){
        $poruka  .= "Korisničko ime ne smije sadržavati znakove <, >, ', "."'"."!<br>";
        $brojGresakaUnosa++;
    }
    if(empty($_POST["lozinka"])){
        $poruka  .= "Molim unesite lozinku!<br>";
        $brojGresakaUnosa++;
    }
    if(strpos($_POST["lozinka"], "<") !==false || strpos($_POST["lozinka"], ">") !==false || strpos($_POST["lozinka"], "'") !==false || strpos($_POST["lozinka"], '"') !==false){
        $poruka  .= "Lozinka ne smije sadržavati znakove <, >, ', "."'"."!<br>";
        $brojGresakaUnosa++;
    }

    if($brojGresakaUnosa==0){
        $krivoIspunjeno = array();
        $sol = md5($_POST["lozinka"].":".$_POST["korime"]);
        $lozinka_hash = hash("sha256", $sol.":".$_POST["lozinka"]);
    
        $upit = "SELECT * FROM korisnici WHERE kor_ime='".$_POST["korime"]."'";
        $podaci = $baza->selectDB($upit);

        if(mysqli_num_rows($podaci)==1){
            $red = mysqli_fetch_assoc($podaci);
            $pokusaji=$red["broj_neuspjesnih_pokusaja"];
            $status=$red["status"];
            if($status=="blokiran"){
                $pokusajText="Korisnički račun je blokiran. Pričekajte da ga administrator odblokira.";
            }else if($status=="neaktiviran"){
                $pokusajText="Korisnički račun nije aktiviran. Molimo vas da ga prvo aktivirate.";
            } else {
                $upit2="SELECT * FROM korisnici WHERE kor_ime='".$_POST["korime"]."' AND lozinka_hash='".$lozinka_hash."'";
                $podaci2 = $baza->selectDB($upit2);
                if(mysqli_num_rows($podaci2)==0){
                    if($pokusaji<3) $pokusaji++;

                    if($pokusaji==3){
                        $pokusajText="3 neuspješna pokušaja prijave za redom. Korisnički račun je blokiran dok ga administrator ne odblokira.";
                        $sql = "UPDATE korisnici SET status='blokiran', broj_neuspjesnih_pokusaja='".$pokusaji."'  WHERE kor_ime='".$_POST["korime"]."'";
                        $baza->updateDB($sql);

                        $sql ="INSERT INTO dnevnik_rada(`vrijeme`,`radnja`,`korisnici_id`)"
                            . "VALUES('".date('Y/m/d H/i/s', strtotime(" + ".$xmldata->pomakvremena." hours"))."','Korisnik je blokiran zbog neuspješnih prijava.', '".$red["id"]."')";
                        $baza->updateDB($sql);
                    }
                    else{
                        $sql = "UPDATE korisnici SET broj_neuspjesnih_pokusaja='".$pokusaji."' WHERE kor_ime='".$_POST["korime"]."'";
                        $baza->updateDB($sql);
                        $pokusajText="Krivo upisana lozinka, imate još ".(3-$pokusaji)." pokusaja";
                    }
                }
        
                if(mysqli_num_rows($podaci2)==1){
                    if(!empty($_POST["zapamti"])){
                        setcookie("korisnik", $_POST["korime"]);
                    }

                    $_SESSION["uloga"] = $red["uloga_id"];
                    $_SESSION["idKorisnika"] = $red["id"];

                    $sql = "UPDATE korisnici SET broj_neuspjesnih_pokusaja='0'  WHERE kor_ime='".$_POST["korime"]."'";
                    $baza->updateDB($sql);

                    $sql ="INSERT INTO dnevnik_rada(`vrijeme`,`radnja`,`korisnici_id`)"
                        . "VALUES('".date('Y/m/d H/i/s', strtotime(" + ".$xmldata->pomakvremena." hours"))."','Korisnik se prijavio na racun.', '".$red["id"]."')";
                    $baza->updateDB($sql);

                    $_SESSION["korisnik"] = $_POST["korime"];

                    ini_set('session.gc_maxlifetime', $xmldata->vrijemesesije*60);
                    session_set_cookie_params($xmldata->vrijemesesije*60);
                    session_start();
    
                    header("Location: ../index.php");
                }          
            }
        }
        else if(mysqli_num_rows($podaci)==0){
            $pokusajText="Traženi korisnički račun ne postoji.";
        }
    }


       
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Projekt</title>
        <link rel="stylesheet" href="../css/opcenito.css">
        <link rel="stylesheet" href="../css/prijava.css">
        <link rel="stylesheet" href="../css/greske.css">
        <script src="../javascript/msitaric_prijava.js"></script>
    </head>
    <body>
        <header>
            
            <nav>
                <ul>
                    <li><a href="../index.php">Početna</a></li>
                    <?php
                    if($_SESSION["uloga"]==4){
                        echo "<li class='desno'><a href='registracija.php'>Registracija</a></li>";
                        echo "<li><a href='../neregistrirani/vijesti.php'>Vijesti</a></li>";
                    }
                    ?>
                </ul>
            </nav>
            <h1>Prijava</h1>
        </header>

        <?php if($poruka!="" || $pokusajText!=""){ ?>

        <div id="greske">
            <?php
                echo $pokusajText."<br>";
                echo $poruka;
            ?>
        </div>

        <?php } ?>

        <form  method="POST" id="prijavaForm" action="<?php echo $_SERVER["PHP_SELF"];?>">
            <div>
            <label for="korime">Korisničko ime</label>
            <input type="text" name=korime id="korime" value="<?php
            if(!empty($_COOKIE["korisnik"])){
                echo $_COOKIE["korisnik"];
            }
            if(!empty($krivoIspunjeno)) echo $krivoIspunjeno[0];
            ?>"/>
            </div>

            <div>
            <label for="lozinka">Lozinka</label>
            <input type="password" name=lozinka id="lozinka" value="<?php
            if(!empty($krivoIspunjeno)) echo $krivoIspunjeno[1];
            ?>"/>
            </div>

            <div>
            <input type="checkbox" name="zapamti" id="zapamti">Zapamti me
            </div>

            <div>
            <input id="gumbPrijava" type="submit" value="Prijava">
            </div>
            <a href="../php/zaboravljena.php">Zaboravljena lozinka?</a>
        </form>
        

        <footer>

        </footer>
    </body>
</html>

<?php

$baza ->zatvoriDB();
?>