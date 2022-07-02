<?php
require_once("../vanjske_datoteke/baza.class.php");

$baza = new Baza();
$baza ->spojiDB();

session_start();

if(!isset($_SESSION["uloga"]) || $_SESSION["uloga"]!=4){
    header("Location: ../index.php");
}
$poruka="";
$xmldata = simplexml_load_file("../xml/postavke.xml");
if($xmldata->onemoguceno==1){
    header("Location: nedostupno.html");
}
if(isset($_POST["email"])){
    $brojGresakaUnosa=0;
    if(empty($_POST["email"])){
        $poruka  .= "Molim unesite email!<br>";
        $brojGresakaUnosa++;
    }
    if(strpos($_POST["email"], "<") !==false || strpos($_POST["email"], ">") !==false || strpos($_POST["email"], "'") !==false || strpos($_POST["email"], '"') !==false){
        $poruka  .= "Email ne smije sadržavati znakove <, >, ', "."'"."!<br>";
        $brojGresakaUnosa++;
    }
    if($brojGresakaUnosa==0){
        $znakovi = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';   
        $duljina = strlen($znakovi);
        $novalozinka = '';
        for ($i = 0; $i < 6; $i++) {
            $novalozinka .= $znakovi[rand(0, $duljina - 1)];
        }

        $sol = md5($novalozinka.":".$_POST["email"]);
        $lozinka_hash = hash("sha256", $sol.":".$novalozinka);

        $email = $_POST["email"];
		$glavaPoruke = "Nova lozinka";
		$sadrzaj = "Vaša nova lozinka je: " . $novalozinka;
        mail($email, $glavaPoruke, $sadrzaj);
        $upit="UPDATE korisnici SET lozinka='".$novalozinka."', lozinka_hash='".$lozinka_hash."' WHERE email='".$_POST["email"]."'";
        $baza->updateDB($upit);
        $sql ="INSERT INTO dnevnik_rada(`vrijeme`,`radnja`,`korisnici_id`)"
                            . "VALUES('".date('Y/m/d H/i/s', strtotime(" + ".$xmldata->pomakvremena." hours"))."','Korisniku sa email adresom ".$_POST["email"]." je poslana nova lozinka.', '".$red["id"]."')";
            
        $baza->updateDB($sql);
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Projekt</title>
    <link rel="stylesheet" href="../css/opcenito.css">
    <link rel="stylesheet" href="../css/greske.css">
</head>
<body>
    <header>
            
            <nav>
                <ul>
                    <li><a href='../index.php'>Početna</a></li>
                    <?php
                    if($_SESSION["uloga"]==4){
                        echo "<li class='desno'><a href='../obrasci/prijava.php'>Prijava</a></li>";
                        echo "<li class='desno'><a href='../obrasci/registracija.php'>Registracija</a></li>";
                        echo "<li><a href='../neregistrirani/vijesti.php'>Vijesti</a></li>";
                    }
                    ?>
                </ul>
                <h1>Zaboravljena lozinka</h1>
            </nav>
        </header>
        <?php if($poruka!=""){ ?>
        <div id="greske">
            <?php
                echo $poruka;
            ?>
        </div>

        <?php } ?>
        <form method="POST" action="<?php echo $_SERVER["PHP_SELF"];?>">
                <label for="email">Unesite svoj email na kojime ste se regestrirali i na njega ćete dobiti novu lozinku</label>
                <input type="email" name="email" placeholder=" ldap@foi.hr">
                <input id="gumbPrijava" type="submit" value="Pošalji email">
        </form>


        <footer>

        </footer>
    
</body>
</html>

<?php

$baza ->zatvoriDB();
?>