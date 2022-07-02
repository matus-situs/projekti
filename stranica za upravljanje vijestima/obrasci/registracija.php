<?php
require_once("../vanjske_datoteke/baza.class.php");

$baza = new Baza();
$baza ->spojiDB();

$xmldata = simplexml_load_file("../xml/postavke.xml");
if($xmldata->onemoguceno==1){
    header("Location: ../nedostupno.html");
}

session_start();

if(!isset($_SESSION["uloga"])){
    $_SESSION["uloga"]=4;
}

$poruka="";
$uspjeh="";
$krivoIspunjeno = array();

if (isset($_POST["korime"])){  
    $brojGresakaUnosa=0;
    array_push($krivoIspunjeno, $_POST["ime"], $_POST["prezime"], $_POST["korime"], $_POST["lozinka"], $_POST["ponlozinka"]);
    if(empty($_POST["ime"])){
        $poruka  .= "Ime ne smije biti prazno!<br>";
        $brojGresakaUnosa++;
    }
    if(strpos($_POST["ime"], "<") !==false || strpos($_POST["ime"], ">") !==false || strpos($_POST["ime"], "'") !==false || strpos($_POST["ime"], '"') !==false){
        $poruka  .= "Ime ne smije sadržavati znakove <, >, ', "."'"."!<br>";
        $brojGresakaUnosa++;
    }
    if(empty($_POST["prezime"])){
        $poruka  .= "Prezime ne smije biti prazno!<br>";
        $brojGresakaUnosa++;
    }
    if(strpos($_POST["prezime"], "<") !==false || strpos($_POST["prezime"], ">") !==false || strpos($_POST["prezime"], "'") !==false || strpos($_POST["prezime"], '"') !==false){
        $poruka  .= "Prezime ne smije sadržavati znakove <, >, ', "."'"."!<br>";
        $brojGresakaUnosa++;
    }
    if(empty($_POST["korime"])){
        $poruka  .= "Korisničko ime ne smije biti prazno!<br>";
        $brojGresakaUnosa++;
    }
    if(strpos($_POST["korime"], "<") !==false || strpos($_POST["korime"], ">") !==false || strpos($_POST["korime"], "'") !==false || strpos($_POST["korime"], '"') !==false){
        $poruka  .= "Korisničko ime ne smije sadržavati znakove <, >, ', "."'"."!<br>";
        $brojGresakaUnosa++;
    }
    if(empty($_POST["lozinka"])){
        $poruka  .= "Lozinka ne smije biti prazna!<br>";
        $brojGresakaUnosa++;
    }
    if(strpos($_POST["lozinka"], "<") !==false || strpos($_POST["lozinka"], ">") !==false || strpos($_POST["lozinka"], "'") !==false || strpos($_POST["lozinka"], '"') !==false){
        $poruka  .= "Lozinka ne smije sadržavati znakove <, >, ', "."'"."!<br>";
        $brojGresakaUnosa++;
    }
    if(strlen($_POST["lozinka"])<6){
        $poruka  .= "Lozinka mora imati minimalno 6 znakova!<br>";
        $brojGresakaUnosa++;
    }
    if(strpos($_POST["ponlozinka"], "<") !==false || strpos($_POST["ponlozinka"], ">") !==false || strpos($_POST["ponlozinka"], "'") !==false || strpos($_POST["ponlozinka"], '"') !==false){
        $poruka  .= "Ponovljena lozinka ne smije sadržavati znakove <, >, ', "."'"."!<br>";
        $brojGresakaUnosa++;
    }
    if($_POST["lozinka"]!=$_POST["ponlozinka"]){
        $poruka  .= "Ponovljena lozinka nije ista!<br>";
        $brojGresakaUnosa++;
    }
    if(empty($_POST["uvjeti"])){
        $poruka = "Uvjeti moraju biti prihvaćeni!<br>";
        $brojGresakaUnosa++;
    }

    $captcha=$_POST["g-recaptcha-response"];
    $secret_key = "6LdaPnwgAAAAAD0VDikWkzB_iZQPqdqifRZpfKFm";
    $ip = $_SERVER['REMOTE_ADDR'];
    $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secret_key) .  '&response=' . urlencode($captcha);
    $odgovor = file_get_contents($url);
    $odgovor = json_decode($odgovor, true);
    if ($captcha=="") {
        $poruka .= "Došlo je do greške u reCAPTACHA.<br>";
        $brojGresakaUnosa++;
    }

    if($brojGresakaUnosa==0){
        $krivoIspunjeno = array();
        $sol = md5($_POST["lozinka"].":".$_POST["korime"]);
        $lozinka_hash = hash("sha256", $sol.":".$_POST["lozinka"]);

        $znakovi = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';   

        do{
            $duljina = strlen($znakovi);
            $aktivacijskiKod = '';
            for ($i = 0; $i < 6; $i++) {
                $aktivacijskiKod .= $znakovi[rand(0, $duljina - 1)];
            }
            $upit ="SELECT * FROM korisnici WHERE aktivacijski_kod='".$aktivacijskiKod."'";
            $podaci = $baza->selectDB($upit);
        }while(mysqli_num_rows($podaci)>0);      

        $upit = "SELECT * FROM korisnici WHERE kor_ime='".$_POST["korime"]."' || email='".$_POST["email"]."'";
        $podaci = $baza->selectDB($upit);

        if(mysqli_num_rows($podaci)>0){
			$poruka = "Korisničko ime/email je zauzet, molim unesite drugo korisničko ime/email!";
		}

		else{
			$sql ="INSERT INTO korisnici(`ime`, `prezime`, `kor_ime`, `lozinka`, `lozinka_hash`, `email`, `uloga_id`, `status`, `uvijeti_koristenja`, `broj_neuspjesnih_pokusaja`, `aktivacijski_kod`, `vrijeme_slanja`) "
                            . "VALUES('".$_POST["ime"]."','".$_POST["prezime"]."','".$_POST["korime"]."','".$_POST["lozinka"]."','".$lozinka_hash."','".$_POST["email"]
                            . "','4','neaktiviran','1','0','".$aktivacijskiKod."', '".date('Y/m/d H/i/s', strtotime(" + ".$xmldata->pomakvremena." hours"))."')";  
            $baza->updateDB($sql);

            $upit = "SELECT * FROM korisnici WHERE kor_ime='".$_POST["korime"]."'";
            $podaci = $baza->selectDB($upit);
            $red = mysqli_fetch_assoc($podaci);

            $sql ="INSERT INTO dnevnik_rada(`vrijeme`,`radnja`,`korisnici_id`)"
                            . "VALUES('".date('Y/m/d H/i/s', strtotime(" + ".$xmldata->pomakvremena." hours"))."','Registriran je racun i poslan aktivacijski email.', '".$red["id"]."')";
            
            $baza->updateDB($sql);

            $link = "https://barka.foi.hr/WebDiP/2021_projekti/WebDiP2021x098/php/msitaric_registracija.php?kod=" . $aktivacijskiKod;
		    $email = $_POST["email"];
		    $glavaPoruke = "Aktivacijski mail";
		    $sadrzaj = "Kliknite na link za aktivaciju vašeg računa. " . $link;
            mail($email, $glavaPoruke, $sadrzaj);
            $uspjeh="Uspješno kreiran račun, aktivirajte ga pomoću linka poslanog na vaš e-mail.";
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
        <link rel="stylesheet" href="../css/registracija.css">
        <script src="../javascript/msitaric_registracija.js"></script>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    </head>
    <body>
        <header>
            
            <nav>
                <ul>
                    <li><a href="../index.php">Početna</a></li>
                    <?php
                    if($_SESSION["uloga"]==4){
                        echo "<li class='desno'><a href='prijava.php'>Prijava</a></li>";
                        echo "<li><a href='../neregistrirani/vijesti.php'>Vijesti</a></li>";
                    }
                    ?>
                </ul>
            </nav>
        </header>
        <h1>Registracija</h1>

        <?php  if($poruka!=""){ ?>
        <div id="greske">
            <?php
                echo $poruka;
            ?>
        </div>
        <?php }?>

        <?php  if($uspjeh!=""){ ?>
        <div id="greske">
            <?php
                echo $uspjeh;
            ?>
        </div>
        <?php }?>

        <form method="POST" id="registracijaForm" action="<?php echo $_SERVER["PHP_SELF"];?>">
            <div>
            <label for="ime">Ime:</label>
            <input type="text" id="ime" name="ime" value="<?php
            if(!empty($krivoIspunjeno)) echo $krivoIspunjeno[0];
            ?>">
            </div>
            <div>
            <label for="prezime">Prezme:</label>
            <input type="text" id="prezime" name="prezime" value="<?php
            if(!empty($krivoIspunjeno)) echo $krivoIspunjeno[1];
            ?>">
            </div>

            <div>
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" placeholder=" ldap@foi.hr">
            </div>

            <div>
            <label for="korime">Korisničko ime:</label>
            <input type="text" id="korime" name="korime" maxlength="45" value="<?php
            if(!empty($krivoIspunjeno)) echo $krivoIspunjeno[2];
            ?>">
            </div>


            <div>
            <label for="lozinka">Lozinka:</label>
            <input type="password" id="lozinka" name="lozinka" value="<?php
            if(!empty($krivoIspunjeno)) echo $krivoIspunjeno[3];
            ?>">
            </div>

            <div>
            <label for="ponlozinka">Ponovljena lozinka:</label>
            <input type="password" id="ponlozinka" name="ponlozinka" value="<?php
            if(!empty($krivoIspunjeno)) echo $krivoIspunjeno[4];
            ?>">
            </div>

            <div>
            <input type="checkbox" name="uvjeti" id="uvjeti" value="uvjeti"> Uvijeti korištenja
            </div>

            <div>
            <input type="checkbox" name="zapamti" id="zapamti" value="zapamti"> Zapamti me<br>
            </div>

            <div>
            <div class="g-recaptcha" data-sitekey="6LdaPnwgAAAAAHDuV0tqZD9m4gNosMaoCjeSy3Nx"></div> 
            </div>

            <div>
            <input id="gumbRegistracija" type="submit" value="Registriraj se">
            </div>

        </form>


        <footer>

        </footer>
    </body>
</html>

<?php

$baza ->zatvoriDB();
?>