<?php
require_once("../vanjske_datoteke/baza.class.php");
session_start();

$brojGresakaUnosa=0;
if(empty($_GET["kod"])){
    $_SESSION["isteklo"] = "Došlo je do greške. Provjerite da li je ispravan aktivacijski kod.<br>";
    $brojGresakaUnosa++;
}
if(strpos($_GET["kod"], "<") !==false || strpos($_GET["kod"], ">") !==false || strpos($_GET["kod"], "'") !==false || strpos($_GET["kod"], '"') !==false){
    $_SESSION["isteklo"] = "Došlo je do greške. Provjerite da li je ispravan aktivacijski kod.<br>";
    $brojGresakaUnosa++;
}

if($brojGresakaUnosa==0){

$baza = new Baza;
$baza -> spojiDB();

$xmldata = simplexml_load_file("../xml/postavke.xml");

$sada= new DateTime();
$poslano = new DateTime($red["vrijeme_slanja"]);
$i=$sada->diff($poslano);
$proslo = $i->format("%h");


if($proslo>=$xmldata->vrijemeaktivacije){
    $_SESSION["isteklo"]="Isteklo je trajanje aktivacijskog koda, molim da se ponovno registrirate";
    header("Location: ../index.php");
} else{
    $_SESSION["uspjeh"]="Uspješno je aktiviran korisnički račun";
}

if(!empty($_GET["kod"])){
    $sql = "UPDATE korisnici SET status='aktiviran', uloga_id='3' WHERE aktivacijski_kod='".$_GET["kod"]."'";
    $baza->updateDB($sql);    
}

$upit = "SELECT * FROM korisnici WHERE aktivacijski_kod='".$_GET["kod"]."'";
$podaci = $baza->selectDB($upit);
$red = mysqli_fetch_assoc($podaci);

$_SESSION["id"]=$red["id"];
$_SESSION["uvjeti"]=$red["uvijeti_koristenja"];
$_SESSION["idKorisnika"]=$red["id"];


$sql ="INSERT INTO dnevnik_rada(`vrijeme`,`radnja`,`korisnici_id`)"
        . "VALUES('".date('Y/m/d H/i/s', strtotime(" + ".$xmldata->pomakvremena." hours"))."','Aktiviran je korisnicki racun.', '".$red["id"]."')";
$baza->updateDB($sql);

header("Location: ../index.php");

$baza -> zatvoriDB();

} else{
    header("Location: ../index.php");
}

?>