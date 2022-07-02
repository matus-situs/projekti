<?php
require_once("vanjske_datoteke/baza.class.php");
$xmldata = simplexml_load_file("xml/postavke.xml");
if($xmldata->onemoguceno==1){
    header("Location: nedostupno.html");
}

session_start();

if(!isset($_SESSION["uloga"])){
    $_SESSION["uloga"]=4;
}

if(isset($_GET["prihvati"]) && !(strpos($_GET["prihvati"], ">") !==false || strpos($_GET["prihvati"], "<") !==false || strpos($_GET["prihvati"], "'>'") || strpos($_GET["prihvati"], '"') !==false)){
    $trajanje = $xmldata->trajanjekolacica + $xmldata->pomakvremena;
    setcookie("uvjetikoristenja", "true", time()+(3600*24*$trajanje));
    header("Location: index.php");
}
if(isset($_GET["odjava"])){
    $_SESSION["uloga"]=4;
    $baza = new Baza();
    $baza ->spojiDB();

    $upit = "SELECT * FROM korisnici WHERE kor_ime='".$_SESSION["korisnik"]."'";
    unset($_SESSION["korime"]);
    
    $podaci = $baza->selectDB($upit);
    $red = mysqli_fetch_assoc($podaci);

    $sql ="INSERT INTO dnevnik_rada(`vrijeme`,`radnja`,`korisnici_id`)"
        . "VALUES('".date('Y/m/d H/i/s', strtotime(" + ".$xmldata->pomakvremena." hours"))."','Korisnik se odjavio sa racuna.', '".$red["id"]."')";
    $baza->updateDB($sql);

    $baza ->zatvoriDB();
    session_destroy();

    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Projekt</title>
        <link rel="stylesheet" href="css/opcenito.css">
        <link rel="stylesheet" href="css/index.css">
        <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
        <script  src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"  integrity="sha256-6XMVI0zB8cRzfZjqKcD01PBsAy3FlDASrlC8SxCpInY="
            crossorigin="anonymous"></script>
    </head>
    <body>
        <header>
            
            <nav>
                <ul>
                    <li><a href='dokumentacija.html'>Dokumentacija</a></li>
                    <li><a href='o_autoru.html'>O autoru</a></li>
                    <?php
                    if($_SESSION["uloga"]==4){
                        echo "<li class='desno'><a href='obrasci/registracija.php'>Registracija</a></li>";
                        echo "<li class='desno'><a href='obrasci/prijava.php'>Prijava</a></li>";
                        echo "<li><a href='neregistrirani/vijesti.php'>Vijesti</a></li>";
                    }

                    if($_SESSION["uloga"]==3){
                        echo "<li><a href='registriran/mojevjesti.php'>Moje vjesti</a></li>";
                        echo "<li><a href='registriran/blokiranekategorije.php'>Blokirane kategorije</a></li>";
                        echo "<li><a href='registriran/registriran_statistika.php'>Statistika</a></li>";
                    }

                    if($_SESSION["uloga"]==2){
                        echo "<li><a href='moderator/vijestimoderator.php'>Recenziraj</a></li>";
                        echo "<li><a href='moderator/odbijeno.php'>Odbijene vijesti</a></li>";
                        echo "<li><a href='moderator/statistika.php'>Statistika</a></li>";
                    }

                    if($_SESSION["uloga"]==1){
                        echo "<li><a href='admin/dnevnik.php?stranica=1'>Dnevnik</a></li>";
                        echo "<li><a href='admin/blokirani_korisnici.php'>Blokirani korisnici</a></li>";
                        echo "<li><a href='admin/kategorije.php'>Kategorije</a></li>";
                        echo "<li><a href='admin/korisnici.php'>Korisnici</a></li>";
                        echo "<li><a href='admin/vijesti_recenzija.php'>Vijesti za recenziju</a></li>";
                        echo "<li><a href='admin/postavke.php'>Postavke sustava</a></li>";
                    }
                    if($_SESSION["uloga"]<4){
                        echo "<li class='desno'><a href='index.php?odjava=1'>Odjava</a></li>";
                    }
                    ?>
                </ul>
            </nav>
            <h1>Početna</h1>
        </header>

        <div id="poruke">
            <?php
            if(!empty($_SESSION["uspjeh"])){
                echo $_SESSION["uspjeh"];
                unset($_SESSION["uspjeh"]);
            }

            if(!empty($_SESSION["isteklo"])){
                echo $_SESSION["isteklo"];
                unset($_SESSION["isteklo"]);
            }
            ?>
        </div>

        <?php
            if(empty($_COOKIE["uvjetikoristenja"])){
                ?>

        <div id="dnoekrana">
        <div id="kolacici">
            <form method='GET' action='<?php echo$_SERVER["PHP_SELF"]?>'>
            <label for='prihvati'>Kao novi korisnik molimo vas da prihvatite naše Uvjete korištenja i kolačiće</label>
            <input type='submit' name='prihvati' value='Prihvaćam uvjete korištenja'></form>  
        </div>
        </div>
            <?php }?>
        
        <footer>

        </footer>
    </body>
</html>