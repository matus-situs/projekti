<?php
require_once("baza.class.php");
$baza = new Baza();
$baza->spojiDB();

if(!isset($_GET["page"])){
    $_GET["page"]=1;	
}
if(!isset($_GET["per_page"])){
    $_GET["per_page"]=5;	
}

function validateDate($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

$poruka="";
$upitJela = "SELECT meal.id as mealID, meal.title as mealTitle, meal.Category_id, category.title as categoryTitle FROM meal INNER JOIN category ON Category_id=category.id";

if(isset($_GET["filtriraj"])){
	if(!ctype_digit($_GET["per_page"])){
		$poruka.="Broj rezultata po stranici smije biti samo broj!<br>";
	}
	if($_GET["per_page"]<=0){
		$poruka.="Broj rezultata po stranici smije biti minimalno 1!<br>";
		$_GET["per_page"]=1;	
	}
	if(strpos($_GET["tag"], "<") !==false || strpos($_GET["tag"], ">") !==false || strpos($_GET["tag"], "'") !==false || strpos($_GET["tag"], '"') !==false){
		$poruka.="Tag ne smije sadržavati znakove <, >, ', "."'"."!<br>";
	}
	if(strpos($_GET["lang"], "<") !==false || strpos($_GET["lang"], ">") !==false || strpos($_GET["lang"], "'") !==false || strpos($_GET["lang"], '"') !==false){
		$poruka.="Jezik ne smije sadržavati znakove <, >, ', "."'"."!<br>";
	}
	if(strpos($_GET["c"], "<") !==false || strpos($_GET["c"], ">") !==false || strpos($_GET["c"], "'") !==false || strpos($_GET["c"], '"') !==false){
		$poruka.="Kategorija ne smije sadržavati znakove <, >, ', "."'"."!<br>";
	}
	if(!validateDate($_GET["diff_time"])){
		$poruka.="Krivi format datuma!<br>";
	}
	if(strpos($_GET["diff_time"], "<") !==false || strpos($_GET["diff_time"], ">") !==false || strpos($_GET["diff_time"], "'") !==false || strpos($_GET["diff_time"], '"') !==false){
		$poruka.="Datum ne smije sadržavati znakove <, >, ', "."'"."!<br>";
	}
	if($poruka==""){		
		$upitJela = "SELECT DISTINCT meal.id as mealID, meal.title as mealTitle, meal.Category_id, category.title as categoryTitle FROM meal INNER JOIN category ON Category_id=category.id"
		." INNER JOIN meal_has_tag ON meal.id=Meal_id WHERE";
		if($_GET["tag"]!="sve"){
			$upitJela.=" Tag_id=".$_GET["tag"];
			switch($_GET["c"]){
				case "sve":break;
				case "null":$upitJela.=" AND category.title='NULL'";break;
				case "!null":$upitJela.=" AND category.title!='NULL'";break;
				default: $upitJela.=" AND meal.Category_id=".$_GET["c"];
			}
			if(isset($_GET["diff_time"])){
				$upitJela.=" AND creation_date > ".$_GET["diff_time"];
			}
		}
		else{
			$g=false;
			switch($_GET["c"]){
				case "sve":$g=true;break;
				case "null":$upitJela.=" category.title='NULL'";break;
				case "!null":$upitJela.=" category.title!='NULL'";break;
				default: $upitJela.=" meal.Category_id=".$_GET["c"];
			}
			if(isset($_GET["diff_time"]) && !$g){
				$upitJela.=" AND creation_date >= '".$_GET["diff_time"]."'";
			}
			if(isset($_GET["diff_time"]) && $g){
				$upitJela.=" creation_date >= '".$_GET["diff_time"]."'";
			}
		}
	}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Jela svijeta</title>
	<link rel="stylesheet" href="stil.css">
</head>
<body>
	<h1>Popis jela</h1>

	<?php  if($poruka!=""){ ?>
        <div id="greske">
            <?php
                echo $poruka;
            ?>
        </div>
    <?php }?>
	<div id="form">
	<form method='GET'>
		<div>
		<label for="per_page">Broj rezultata po stranici:</label>
		<input type="number" name="per_page" value="<?php echo $_GET["per_page"] ?>">
		</div>
		<div>
		<label for="tag">Tagovi:</label>
		<select name='tag'>
			<option value="sve">Svi tagovi</option>
                <?php
                $upit="SELECT * FROM tag";
                $podaci = $baza->selectDB($upit);
                while ($red = mysqli_fetch_assoc($podaci)){
					echo "<option value='".$red["id"]."'>".$red["title"]."</option>";					                  
                }      
                ?>                   
        </select>
		</div>
		<div>
		<label for="lang">Jezik:</label>
		<select name="lang">
			<option value="hr">Hrvatski</option>
		</select>
		</div>
		<div>
		<label for="c">Kategorija</label>
		<select name="c">
			<option value="sve">Sve kategorije</option>
			<option value="null">Bez kategorije</option>
			<option value="!null">Sa kategorijom</option>
			<?php
			$upit="SELECT * FROM category";
			$podaci = $baza->selectDB($upit);
			while ($red = mysqli_fetch_assoc($podaci)){
				if($red["title"]!="NULL"){
					echo "<option value='".$red["id"]."'>".$red["title"]."</option>";
				}									                  
			}  
			?>
		</select>
		</div>
		<div>
		<label>Podaci za prikaz:</label><br>
		<input type="checkbox" name="category" <?php if(isset($_GET["category"]) && $_GET["category"]=="on") echo "checked" ?>><label for="category">Kategorija jela</label><br>
		<input type="checkbox" name="ingredients" <?php if(isset($_GET["ingredients"]) && $_GET["ingredients"]=="on") echo "checked" ?>><label for="ingredients">Sastojci jela</label><br>
		<input type="checkbox" name="tags" <?php if(isset($_GET["tags"]) && $_GET["tags"]=="on") echo "checked" ?>><label for="tags">Tagovi jela</label><br>
		</div>
		<div>
		<label for="diff_time">Vrati sve kreirano nakon:</label>
		<input type="date" name="diff_time" value="<?php if(isset($_GET["diff_time"])) echo $_GET["diff_time"] ?>"><br>
		</div>
		<input type='submit' name='filtriraj' class="gumb" value='Filtriraj' />
	</form>
	</div>

	<table>
		<thead>
			<td>Naziv jela</td>
			<?php if(isset($_GET["category"]) && $_GET["category"]=="on") echo "<td>Kategorija</td>"; ?>
			<?php if(isset($_GET["ingredients"]) && $_GET["ingredients"]=="on") echo "<td>Sastojci</td>"; ?>	
			<?php if(isset($_GET["tags"]) && $_GET["tags"]=="on") echo "<td>Tagovi</td>"; ?>
		</thead>
		<?php
		$rezultatiPoStranici = $_GET["per_page"];
        $jela = $baza->selectDB($upitJela);
            
        $brojRezultata = mysqli_num_rows($jela);
        $brojStranica = ceil($brojRezultata/$rezultatiPoStranici);
            
        $trenutnaStranica = $_GET["page"];

        $rezultatiStranice = ($trenutnaStranica-1)*$rezultatiPoStranici;

        $upitJela .= " LIMIT ".$rezultatiStranice.",".$rezultatiPoStranici;

		$jela = $baza->selectDB($upitJela);

		while($jelo=mysqli_fetch_assoc($jela)){
			echo "<tr><td>".$jelo["mealTitle"]."</td>";
			
			if(isset($_GET["category"]) && $_GET["category"]=="on"){
				if($jelo["categoryTitle"]=="NULL"){
					echo "<td>-</td>";
				}
				else{
					echo "<td>".$jelo["categoryTitle"]."</td>";
				}
			}
			
			if(isset($_GET["ingredients"]) && $_GET["ingredients"]=="on"){
				$upitSastojaka = "SELECT ingredient.title FROM ingredient INNER JOIN meal_has_ingredient ON ingredient.id=Ingredient_id INNER JOIN meal ON Meal_id=meal.id WHERE meal.id=".$jelo["mealID"];
				$sastojci = $baza->selectDB($upitSastojaka);
				echo "<td>";
				while($sastojak=mysqli_fetch_assoc($sastojci)){
					echo $sastojak["title"]."<br>";
				}
				echo "</td>";
			}
			
			if(isset($_GET["tags"]) && $_GET["tags"]=="on"){
				$upitTagova = "SELECT tag.title FROM tag INNER JOIN meal_has_tag ON tag.id=Tag_id INNER JOIN meal ON Meal_id=meal.id WHERE meal.id=".$jelo["mealID"];
				$tagovi = $baza->selectDB($upitTagova);
				echo "<td>";
				while($tag=mysqli_fetch_assoc($tagovi)){
					echo $tag["title"]."<br>";
				}
				echo "</td>";
			}		
		}
		?>
	</table>

	<?php
		for($trenutnaStranica =1; $trenutnaStranica<=$brojStranica; $trenutnaStranica++){
            echo "<a class='stranica' href='index.php?per_page=".$_GET["per_page"];
			if(isset($_GET["tag"])){
				echo "&tag=".$_GET["tag"];
			}
			if(isset($_GET["lang"])){
				echo "&lang=".$_GET["lang"];
			}
			if(isset($_GET["c"])){
				echo "&c=".$_GET["c"];
			}
			if(isset($_GET["category"])){
				echo "&category=on";
			}
			if(isset($_GET["ingredients"])){
				echo "&ingredients=on";
			}
			if(isset($_GET["tags"])){
				echo "&tags=on";
			}
			echo "&diff_time=".$_GET["diff_time"];
						
			echo "&filtriraj=Filtriraj&page=".$trenutnaStranica."'>".$trenutnaStranica."</a>"; 
        }
        echo "<br> <div id='trenutno'>Trenutna stranica: ".$_GET["page"]."</div>";
    ?>
	
</body>
</html>
<?php
$baza->zatvoriDB();
?>