<?php 
//THANKS TO ROBIN_WOOD -> digininja

/**
 * [UTILS_PREGS (PHP)]
 * [NAME]	-> preg_match("/^[a-zA-Z-é ]{1,50}$/",$name)
 * [EMAIL]	-> !filter_var($email,FILTER_VALIDATE_EMAIL)
*/

/**
 * [UTILS_REGEX (JS/PHP)]
 * regexEmail 			= /^[a-zA-Z0-9-_.]+@[a-z0-9-_.]+\.[a-z]{2,}$/;
 * regexFirstAndName 	=  /^[a-zA-Z- ]{1,50}$/;
 * regexMessage 		= /^[a-zA-Z0-9 '",áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ.-]{0,}$/;
*/

$nameErr = $typeErr = $imageErr = $priceErr = $nbrPeopleErr = $authorErr = "";
$ingreErr = $descroErr = $prepaErr = "";

$all_columns_names_recipe = [
	"name","type","image","ingredients","description","preparation","price","nbr_people","author"
];


function test_input($data) {
	// XSS 
    /*$data = htmlspecialchars($data); // not enough */
	$trim_data = trim($data);
    $data = stripslashes($trim_data);
	// $GLOBALS["___mysqli_ston"]; ? 
	//$data = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"],  $data) : "Error\n";
	$data = htmlspecialchars($data);
    return $data;
}

function test_select($select_elem, array $values) {
	if(in_array($select_elem,$values))
		return $select_elem;
	else 
		return NULL;

}


function parsing_textarea(string $label, string $content, string $separator) : bool {
	$rtn = true;
	if($label === "ingre") {
		$tab = explode($separator,$content);
		$itemErr = "";
		if(empty($tab)) {
			$rtn = false;
			$itemErr .= "Format = Ingrédient1,Ingrédient2";
		}
		foreach($tab as $item) {
			if(strlen($item)>30) {
				$itemErr .= "Un ingrédient ne fait pas plus de 30 caractères";
				break;
			}
			else {
				//TEST_INPUT
				if(!preg_match("/^[0-9a-zA-Z- é]{1,30}$/",$item)) {
            		$itemErr .= "Espace et tiret autorisés ainsi que les majuscules"; 
					break;
				}
			}
		}
		if($itemErr !== "") {
			$GLOBALS['ingreErr'] = $itemErr;
			$rtn = false;
		}
	}
	else if($label === "prepa") {
		$tab = explode($separator,$content);
		$itemErr = "";
		if(empty($tab)) {
			$rtn = false;
			$itemErr .= "Format = Ingrédient1,Ingrédient2";
		}
		foreach($tab as $item) {
			if(strlen($item)>30) {
				$itemErr .= "Un ingrédient ne fait pas plus de 30 caractères";
				break;
			}
			else {
				//TEST_INPUT
				if(!preg_match("/^[0-9a-zA-Z- é]{1,300}$/",$item)) {
            		$itemErr .= "Espace et tiret autorisés ainsi que les majuscules 300 caractères par étape max"; 
					break;
				}
			}
		}
		if($itemErr !== "") {
			$GLOBALS['prepaErr'] = $itemErr;
			$rtn = false;
		}	
	}
	else if($label === "descro") {
		$rtn = true;
	}
	else {
		$rtn = true;
	}
	return $rtn;
}

function image_upload($img) {
	$rtn = true;
	$uploaded_name = $img[ 'name' ];
	$uploaded_ext  = substr( $uploaded_name, strrpos( $uploaded_name, '.' ) + 1);
	$uploaded_size = $img[ 'size' ];
	$uploaded_type = $img[ 'type' ];
	$uploaded_tmp  = $img[ 'tmp_name' ];

	// Where are we going to be writing to?
	$target_path   =  'subs_imgs/';
	//$target_file   = basename( $uploaded_name, '.' . $uploaded_ext ) . '-';
	$target_file_origin = $uploaded_name;
	$target_file   =  md5( uniqid() . $uploaded_name ) . '.' . $uploaded_ext;
	$temp_file     = ( ( ini_get( 'upload_tmp_dir' ) == '' ) ? ( sys_get_temp_dir() ) : ( ini_get( 'upload_tmp_dir' ) ) );
	$temp_file    .= md5( uniqid() . $uploaded_name ) . '.' . $uploaded_ext;

	// Is it an image?
	if( ( strtolower( $uploaded_ext ) == 'jpg' || strtolower( $uploaded_ext ) == 'jpeg' || strtolower( $uploaded_ext ) == 'png' ) &&
		( $uploaded_size < 100000 ) &&
		( $uploaded_type == 'image/jpeg' || $uploaded_type == 'image/png' ) &&
		getimagesize( $uploaded_tmp ) ) {

		// Strip any metadata, by re-encoding image (Note, using php-Imagick is recommended over php-GD)
		if( $uploaded_type == 'image/jpeg' ) {
			$img = imagecreatefromjpeg( $uploaded_tmp );
			imagejpeg( $img, $temp_file);
		}
		else {
			$img = imagecreatefrompng( $uploaded_tmp );
			imagepng( $img, $temp_file);
		}
		imagedestroy( $img );

		if(!rename( $temp_file,$target_path . $target_file)) {
			$rtn = false;
			$GLOBALS['imageErr'] = "L'image n'a pas était enregistrée";
		}
		
		// Delete any temp files
		if( file_exists( $temp_file ) )
			unlink( $temp_file );
		$rtn = $target_file;
	}
	else {
		// Invalid file
		$GLOBALS['imageErr'] = "Format d'image en .jpg ou .png" ;
		$rtn = false;
	}
	return $rtn;
}
function create_table_recette_viewers() {
	try {
		$bdd = new PDO('sqlite:' . dirname(__FILE__) . '/database.db');
		//$bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // ERRMODE_WARNING | ERRMODE_EXCEPTION | ERRMODE_SILENT
		//$bdd->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		$sql = "CREATE TABLE IF NOT EXISTS ViewersRecette (
				id INTEGER PRIMARY KEY AUTOINCREMENT ,
				name VARCHAR(50) NOT NULL,
				type VARCHAR(10) NOT NULL,
				image VARCHAR(30) NOT NULL,
				ingredients TEXT,
				preparation TEXT,
				description TEXT,
				price real NOT NULL,
				nbr_people int NOT NULL,
				author VARCHAR(30),
				CONSTRAINT CHK_IMG CHECK(image LIKE '%.jpg' OR image LIKE '%.png')
			)";
			
			$bdd->exec($sql);
			$bdd = null;
	}
	catch (PDOException $e) {
		var_dump($e->getMessage());
	}
}

function update_db(array $columns_name, array $to_insert) {
	if(array_combine($columns_name,$to_insert)) {
		$err = false; 
		$name_parse = test_input($to_insert[0]);
		if(!preg_match("/^[a-zA-Z- é]{1,50}$/",$name_parse)) {
            $GLOBALS['nameErr'] = "Espace et tiret autorisés ainsi que les majuscules";  
			$err = true;
		} 
		$type_parse = test_select($to_insert[1],["sucree","salee","mixte"]);
		if($type_parse === NULL) {
			$err = true;
			$GLOBALS['typeErr'] = "Pas d'autres options";
		}
		
		$ingre_parse = test_input($to_insert[3]);
		$true_parsing_ingre = parsing_textarea("ingre",$ingre_parse,",");
		$err =  !$true_parsing_ingre;
		$descro_parse = test_input($to_insert[4]);

		$prepa_parse = test_input($to_insert[5]);
		$true_parsing_steps = parsing_textarea("prepa",$prepa_parse,",");
		$err = !$true_parsing_steps;

		$price_parse = test_input($to_insert[6]);
		if($price_parse < 1 || $price_parse > 100) {
			$err = true;
			$GLOBALS['priceErr'] = "Prix entre 1 et 100€";
		}
		
		$nbr_people_parse = test_select($to_insert[7],[2,4,6,8,10]);
		if($nbr_people_parse === NULL) {
			$err = true;
			$GLOBALS['nbrPeopleErr'] = "2,4,6,8,10 personnes";
		}
	
		$author_parse = test_input($to_insert[8]);
		if(!preg_match("/^[a-zA-Z- é]{1,30}$/",$author_parse)) {
            $GLOBALS['authorErr'] = "Espace et tiret autorisés ainsi que les majuscules"; 
		}
		$image_parse = image_upload($to_insert[2]);
		echo "image_parse : $image_parse\n";
		if($image_parse === FALSE)
			$err = true;
	
		if($err === FALSE) {
			create_table_recette_viewers();
			$to_insert_ = [$name_parse,$type_parse,$image_parse,$ingre_parse,
							$descro_parse,$prepa_parse,$price_parse,
							$nbr_people_parse,$author_parse];
			try {
				$bdd = new PDO('sqlite:' . dirname(__FILE__) . '/database.db');
				$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // ERRMODE_WARNING | ERRMODE_EXCEPTION | ERRMODE_SILENT
				$sql = "INSERT INTO ViewersRecette 
						($columns_name[0], $columns_name[1], $columns_name[2], 
						$columns_name[3], $columns_name[4], $columns_name[5],
						$columns_name[6], $columns_name[7], $columns_name[8])
				VALUES 
						(:$columns_name[0],:$columns_name[1],:$columns_name[2],
						:$columns_name[3],:$columns_name[4],:$columns_name[5],
						:$columns_name[6],:$columns_name[7],:$columns_name[8])
				";
				$stmt = $bdd->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
				$re_cb = array_combine($columns_name,$to_insert_);
				$stmt->execute($re_cb);
			}
			catch (PDOException $e) {
				var_dump($e->getMessage());
			}
		}
	}
	else {
		var_dump("ERROR in form");
	}
}

if( isset( $_POST[ 'submitrecette' ] ) ) {
	/*update_db( //-->>>>>>>>< FONCTIONNNNEE !
		$all_columns_names_recipe,
		["Choucroute","oo",$_FILES['uploaded'],"Choux,saucisse","Un plat typique","Faire cuire le choux",20,4,"_________________________________________________________"]
		//"name","type","image","ingredients","description","preparation","price","nbr_people","author"
	);*/
	update_db(
		$all_columns_names_recipe,
		[$_POST['name'],$_POST['type_prepa'],$_FILES['uploaded'],
			$_POST['ingredients'],$_POST['prepa'],$_POST['description'],
			$_POST['price'],$_POST['convives'],$_POST['author']]
	);
}
?> 