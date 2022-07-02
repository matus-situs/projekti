<?php
require_once("../vanjske_datoteke/baza.class.php");

$baza = new Baza();
$baza ->spojiDB();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Projekt</title>
    <link rel="stylesheet" href="../css/tablice.css">
</head>
<body>
    <header>
        <h1>Popis korisnika</h1>
    </header>

    <table>
        <thead>
            <td>Korisničko ime</td>
            <td>Lozinka</td>
            <td>Uloga</td>
        </thead>
        <?php
        $upit = "SELECT korisnici.kor_ime, korisnici.lozinka, uloga.naziv FROM korisnici INNER JOIN uloga ON korisnici.uloga_id=uloga.id";
        $podaci = $baza->selectDB($upit);
        while($red = mysqli_fetch_assoc($podaci)){
            echo "<tr><td>".$red["kor_ime"]."</td>"
                . "<td>".$red["lozinka"]."</td>"
                . "<td>".$red["naziv"]."</td></tr>";
        }
        ?>
    </table>

    <footer></footer>
</body>
</html>

<?php

$baza ->zatvoriDB();
?>