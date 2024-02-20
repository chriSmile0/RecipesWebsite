<?php 
// FONCTIONNE SI ON TOUT LES DROITS SUR LE DOSSIER ET SUR LES FICHIERS CONCERNÉS
function insert_recette(string $name, string $ingredients, string $description,
						string $preparation, string $image, string $price,
						int $nbr_people, string $author) {
	/*$name = "La Choucroute";
	$ingredients = "Choux,Saucisse";
	$description = "Un plat typique ...";
	$preparation = "Faire cuire le choux,Faire cuire les pommes de terre ";
	$image = "choucroute";
	$price = "20€";
	$nbr_people = 5;
	$author = "moi";*/
	// MAYBE ADD QUANTITY IN INGREDIENTS
	try {
		$bdd = new PDO('sqlite:' . dirname(__FILE__) . '/database.db');
		$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // ERRMODE_WARNING | ERRMODE_EXCEPTION | ERRMODE_SILENT
		$sql = "INSERT INTO Recette (name, ingredients, description, preparation, image, price, nbr_people, author)
		VALUES ('$name','$ingredients','$description','$preparation','$image','$price','$nbr_people','$author')";
		//Reqûete préparer ici avec .prepare();
		$bdd->exec($sql);
	}
	catch (PDOException $e) {
		var_dump($e->getMessage());
	}
	
}

function insert_ingredient(string $name, string $image, string $description, 
							float $price, string $type) {

	try {
		$bdd = new PDO('sqlite:' . dirname(__FILE__) . '/database.db');
		$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // ERRMODE_WARNING | ERRMODE_EXCEPTION | ERRMODE_SILENT
		//ESCAPE INFOS !!!
		$description_ = $bdd->quote($description);
		//CHECK INFOS 

		$sql = "INSERT INTO Ingredients (name, image, description, price, type)
		VALUES ('$name','$image',$description_,'$price','$type')";
		//Reqûete préparer ici avec .prepare();
		$bdd->exec($sql);
		echo "insert ok \n";
	}
	catch (PDOException $e) {
		var_dump($e->getMessage());
	}
}

function create_table_recette() {
	try {
		$bdd = new PDO('sqlite:' . dirname(__FILE__) . '/database.db');
		//$bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // ERRMODE_WARNING | ERRMODE_EXCEPTION | ERRMODE_SILENT
		//$bdd->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		$sql = "CREATE TABLE IF NOT EXISTS Recette (
				id INTEGER PRIMARY KEY AUTOINCREMENT ,
				name VARCHAR(30) NOT NULL,
				ingredients VARCHAR(200) NOT NULL,
				description TEXT,
				preparation TEXT,
				image VARCHAR(30),
				price int,
				nbr_people int,
				author VARCHAR(50),
				CONSTRAINT CHK_IMG CHECK(image LIKE '%.jpg')
			)";
			
			$bdd->exec($sql);
			$bdd = null;
	}
	catch (PDOException $e) {
		var_dump($e->getMessage());
	}
}



function create_table_ingredients() {
	try {
		$bdd = new PDO('sqlite:' . dirname(__FILE__) . '/database.db');
		//$bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // ERRMODE_WARNING | ERRMODE_EXCEPTION | ERRMODE_SILENT
		//$bdd->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		$sql = "CREATE TABLE IF NOT EXISTS Ingredients (
				id INTEGER PRIMARY KEY AUTOINCREMENT ,
				name VARCHAR(30) NOT NULL,
				image VARCHAR(30),
				description TEXT,
				price real,
				type VARCHAR(30),
				CONSTRAINT CHK_type CHECK(type = 'kg' OR type = 'piece')
			)";
			
		$bdd->exec($sql);
		$bdd = null;
			
	}
	catch (PDOException $e){
		var_dump($e->getMessage());
	}
}

function display_recipe($row) {
	// ($name,$ingredients,$description,$preparation,$image,$price,$nbr_people,$author)";
	$Output = "<aside><div></div>";
	// INGREDIENTS SEPARATR = ,
	$name = "<h4>".$row['name']."</h4>";
	$ingredients = explode(",",$row['ingredients']);
	$out_ingredients = "<h5>Ingrédients</h5>\n<ul>";
	foreach($ingredients as $ingre) {
		$out_ingredients .= "<li>".$ingre."</li>";
	}
	$out_ingredients .= "</ul>";
	$img = "<img src=\"imgs/".$row['image']."\">";
	$descro = "<p>".$row["description"]."</p>";

	// PARSING DES ETAPES 
	// ETAPES SEPARATOR = \n\n
	$etapes = explode(",",$row['preparation']);
	$out_etapes = "<h5>Étapes</h5>\n<ul>";
	foreach($etapes as $step) {
		$out_etapes .= "<li>".$step."</li>";
	}
	$out_etapes .= "</ul>";
	$details = "<ul class=\"details\">";
	$details .= "<li>Prix:<span style=\"margin-left:20%;\">".$row['price']."</span></li>";
	$details .= "<li>Convives:<span style=\"margin-left:10%;\">".$row['nbr_people']."</span></li>";
	$details .= "<li>Auteur:<span style=\"margin-left:10%;\">".$row['author']."</span></li>";
	$details .= "</ul>";
	$Output .= "$name\n$img\n$descro\n$out_ingredients\n$out_etapes
		<h5>Informations</h5>$details";//\n$out_etapes\n$details";
	$Output .= "</aside>";
	return $Output;
}

function display_ingredient($row) {
	// ($name,$image,$description, $price, $type)";
	$Output = "<aside><div></div>";
	// INGREDIENTS SEPARATR = ,
	$name = "<h4>".$row['name']."</h4>";
	$img = "<img src=\"imgs/".$row['image']."\">";
	$descro = "<p>".$row["description"]."</p>";

	// PARSING DES ETAPES 
	// ETAPES SEPARATOR = \n\n
	$details = "<ul class=\"details\">";
	$details .= "<li>Prix:".$row['price']. " ".$row['type']."</li>";
	$details .= "</ul>";
	$Output .= "$name\n$img\n$descro\n<h5>Informations</h5>$details";
	$Output .= "</aside>";
	return $Output;
}


function display_all_recipes() {
	try {
		$bdd = new PDO('sqlite:' . dirname(__FILE__) . '/database.db');
		$sql = "SELECT * from Recette";
		foreach ($bdd->query($sql) as $row) {
			echo display_recipe($row);
		}
	}
	catch(PDOEXCEPTION $e){
		var_dump($e->getMessage());
	}
}


function display_all_ingredients() {
	try {
		$bdd = new PDO('sqlite:' . dirname(__FILE__) . '/database.db');
		$sql = "SELECT * from Ingredients";
		foreach ($bdd->query($sql) as $row) {
			echo display_ingredient($row);
		}
	}
	catch(PDOEXCEPTION $e){
		var_dump($e->getMessage());
	}
}

/*create_table_recette();
// $name,$ingredients,$description,$preparation,$image,$price,$nbr_people,$author)";
$name = "La Choucroute";
$ingredients = "Choux,Saucisse";
$description = "Un plat typique ...";
$preparation = "Faire cuire le choux,Faire cuire les pommes de terre ";
$image = "choucroute.jpg";
$price = "20€";
$nbr_people = 5;
$author = "moi";
insert_recette($name,$ingredients,$description,$preparation,$image,$price,$nbr_people,$author);*/
//display_all_recipes();
/*create_table_ingredients();
insert_ingredient("Manioc","manioc.jpg","Ce tubercule issu des terres Américaines trouvent sa place dans les alimentation du monde",1.50,"kg");
insert_ingredient("Banane Plantain","banane_plantain.jpg","Une banane à cuire une fois très mure une fois d'en apprécier toute la saveur ",2.00,"kg");
insert_ingredient("Patate Douce","patate_douce.jpg","..",2.00,"kg");*/



?> 