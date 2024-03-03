<?php 
// FONCTIONNE SI ON TOUT LES DROITS SUR LE DOSSIER ET SUR LES FICHIERS CONCERNÉS
/**								 ________________			 _				  _				 _
 * 				/\				|				 \			| \				 / |	[[]]	| \				 |
 * 		   	   /  \				|				  \			|  \			/  |	[[]]	|  \			 |
 * 		  	  /    \			|				   \		|	\		   /   |	[[]]	|	\			 |
 * 		 	 /		\			|				    \		|	 \		  /	   |	[[]]	|	 \			 |
 * 			/		 \			|					 \		|	  \		 /     |	[[]]	|	  \			 |
 *     	   /		  \			|					  \		|	   \	/	   |	[[]]	|	   \		 |
 * 	  	  /			   \		|					  \		|		\  /	   |	[[]]	|		\		 |
 * 	 	 /				\		|					  \		|		 \/		   |	[[]]	|		 \		 |
 * 		/****************\		|					  \		|		  		   |	[[]]	|		  \		 |
 * 	   /******************\		|				   	 /		|				   |	[[]]	|		   \	 |
 * 	  /					   \	|				  	/		|				   |	[[]]	|			\	 |
 * 	 /						\	|				   /		|				   |	[[]]	|			 \	 |
 * 	/						 \	|				  /			|				   |	[[]]	|			  \	 |
 * /						  \	|________________/			|				   |	[[]]	|			   \_|	
*/

/**
 * [BRIEF]	Insertion of the differents elements in the corresponding columns
 * 			for create a row in recipe table
 * @param	string	$name			The name of the recipe
 * @param	string 	$ingredients	The list of ingredients (I,I)
 * @param	string 	$description	Description of the recipe
 * @param	string 	$preparation	The list of steps (S,S)
 * @param	string 	$image			The image of the result of the recipe
 * @param	string	$price			The price of all ingredients
 * @param 	int		$nbr_people		For how many people the recipe is 
 * @param 	string 	$author			The author of the recipe (you)
 * @example insert_recette("","",....,0|2|4|6|8|10,"")
 * @author	chriSmile0
 * @return 	/
*/
function insert_recette(string $name, string $ingredients, string $description,
						string $preparation, string $image, string $price,
						int $nbr_people, string $author) {
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
/**
* [BRIEF]	Insertion of the differents elements in the corresponding columns
* 			for create a row in ingredient table
* @param	string	$name			The name of the recipe
* @param	string 	$description	Description of the recipe
* @param	string 	$image			The image of the result of the recipe
* @param	float	$price			The price of the ingredient in'unite' or 'kg'
* @example	insert_ingredient("","",....,0|2|4|6|8|10,"")
* @author	chriSmile0
* @return 	/
*/
function insert_ingredient(string $name, string $image, string $description, 
							float $price, string $type) {

	try {
		$bdd = new PDO('sqlite:' . dirname(__FILE__) . '/database.db');
		$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // ERRMODE_WARNING | ERRMODE_EXCEPTION | ERRMODE_SILENT
		$description_ = $bdd->quote($description);
		$sql = "INSERT INTO Ingredients (name, image, description, price, type)
		VALUES ('$name','$image',$description_,'$price','$type')";
		//Reqûete préparer ici avec .prepare();
		$bdd->exec($sql);
	}
	catch (PDOException $e) {
		var_dump($e->getMessage());
	}
}

/**
 * [BRIEF]	Creation of the table for store the differents admin recipe
 * @param 	void
 * @example create_table_recette()
 * @author	chriSmile0
 * @return 	/
*/
function create_table_recette() {
	try {
		$bdd = new PDO('sqlite:' . dirname(__FILE__) . '/database.db');
		$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // ERRMODE_WARNING | ERRMODE_EXCEPTION | ERRMODE_SILENT
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


/**
 * [BRIEF]	Creation of the table for store the differents admin ingredients
 * @param 	void
 * @example create_table_ingredients()
 * @author	chriSmile0
 * @return 	/
*/
function create_table_ingredients() {
	try {
		$bdd = new PDO('sqlite:' . dirname(__FILE__) . '/database.db');
		$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // ERRMODE_WARNING | ERRMODE_EXCEPTION | ERRMODE_SILENT
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

/**
 * [BRIEF]	Display in the html content each column on the corresponding recipe
 * 			$row
 * @param 	$row	The table row
 * @example display_recipe($row)
 * @author	chriSmile0
 * @return 	string	the content to diplay in the index.php file 
*/
function display_recipe($row) {
	$Output = "<aside><div></div>";
	$name = "<h4>".$row['name']."</h4>";
	$ingredients = explode(",",$row['ingredients']);
	$out_ingredients = "<h5>Ingrédients</h5>\n<ul>";
	foreach($ingredients as $ingre) {
		$out_ingredients .= "<li>".$ingre."</li>";
	}
	$out_ingredients .= "</ul>";
	$img = "<img src=\"../imgs/".$row['image']."\">";
	$descro = "<p>".$row["description"]."</p>";
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

/**
 * [BRIEF]	Display in the html content each column on the corresponding ingredients
 * 			$row
 * @param 	$row	The table row
 * @example display_ingredient($row)
 * @author	chriSmile0
 * @return 	string	the content to diplay in the index.php file 
*/
function display_ingredient($row) {
	$Output = "<aside><div></div>";
	$name = "<h4>".$row['name']."</h4>";
	$img = "<img src=\"../imgs/".$row['image']."\">";
	$descro = "<p>".$row["description"]."</p>";

	$details = "<ul class=\"details\">";
	$details .= "<li>Prix:".$row['price']. " ".$row['type']."</li>";
	$details .= "</ul>";
	$Output .= "$name\n$img\n$descro\n<h5>Informations</h5>$details";
	$Output .= "</aside>";
	return $Output;
}


/**
 * [BRIEF]	(@see display_recipe) for all recipes in target table
 * @param 	void
 * @example display_all_recipes_()
 * @author	chriSmile0
 * @return 	/
*/
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

/**
 * [BRIEF]	(@see display_ingredient) for all recipes in target table
 * @param 	void
 * @example display_all_ingredients()
 * @author	chriSmile0
 * @return 	/
*/
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

/**
 * [BRIEF]
 * @param 
 * @example 
 * @author	chriSmile0
 * @return 
*/
?> 