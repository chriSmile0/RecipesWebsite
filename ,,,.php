<?php require_once('___.php');?>
<?php require 'header.php'; ?>
<?php 

function test_input2($data) {
	// XSS 
    /*$data = htmlspecialchars($data); // not enough */
	$trim_data = trim($data);
    $data = stripslashes($trim_data);
	// $GLOBALS["___mysqli_ston"]; ? 
	//$data = (isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"],  $data) : "Error\n";
	$data = htmlspecialchars($data);
    return $data;
}

function addslashes_home(string $s) : string {
	$len = strlen($s)-1;
	//$d_len = 2*$len;
	$new = "";
	//alloc 
	$cpt = 0;
	$j = 0;
	for($j; $j < $len; $j++) 
		$cpt += $s[$j] == ("'");
	
	$j += $cpt;
	for($i = $len; $i >= 0 && $j >= 0; $i--,$j--) {
		if(($s[$i]=="'")) {
			$new[$j] = "'";
			$new[$j-1] = "\\";
			$j--;
		}
		else {
			$new[$j] = $s[$i];
		}
	}
	return $new;
}

function create_private_db_table() {
	try {
		$bdd = new PDO('sqlite:' . dirname(__FILE__) . '/_database.db');
		//$bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // ERRMODE_WARNING | ERRMODE_EXCEPTION | ERRMODE_SILENT
		//$bdd->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		$sql = "CREATE TABLE IF NOT EXISTS QandA (
				id INTEGER PRIMARY KEY AUTOINCREMENT ,
				question VARCHAR(100) NOT NULL,
				answer VARCHAR(3) NOT NULL,
				CONSTRAINT CHK_ANSWER CHECK(answer LIKE 'oui' OR answer LIKE 'non')
			)";
		$bdd->exec($sql);
		$bdd = null;
	}
	catch (PDOException $e) {
		var_dump($e->getMessage());
	}
}

function insert_q_and_a(string $q, string $a) {
	//check $q and $a ? 
	echo "insert \n";
	try {
		$bdd = new PDO('sqlite:' . dirname(__FILE__) . '/_database.db');
		$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // ERRMODE_WARNING | ERRMODE_EXCEPTION | ERRMODE_SILENT
		var_dump($q);
		//$q = test_input($q);
		//$q = $bdd->quote($q);
		var_dump($q);
		//$q = addcslashes()
		$sql = "INSERT INTO QandA (question,answer)
		VALUES (:q,:a)";
		//Reqûete préparer ici avec .prepare();
		$stmt = $bdd->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
		$stmt->execute([':q'=> $q,':a'=>$a]);
	}
	catch (PDOException $e) {
		var_dump($e->getMessage());
	}
}

function search_q_and_a(string $q, string $a) : array {
	if(($a === "oui") || ($a === "non")) {
		$q = test_input2($q); // to comment if use this only with php
		$q = preg_replace(['/_/'],['\''],$q);
		$q = test_input2($q); // to comment if use this only with php
		$bdd = new PDO('sqlite:' . dirname(__FILE__) . '/_database.db');
		$sql = "SELECT * from QandA WHERE question = :q ";
		$stmt = $bdd->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
		$stmt->execute([':q'=> $q]);
		$res = $stmt->fetchAll();
		$size = sizeof($res);
		//var_dump($_SESSION);
		if($size == 1) {
			if($a == $res[0]['answer']) {
				//echo "GOOD LETS GO YOUR NOT A ROBOT";
				return [true,NULL];
			}
			else {
				//echo "NEW QUESTION FOR YOU , essai restant = " . strval($_SESSION['cpt_question']-1);
				if(empty($_SESSION)) {
					//setSession();
					$_SESSION['cpt_question'] = 3;
					$ip = "";
					if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
						$ip = $_SERVER['HTTP_CLIENT_IP'];
					} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
						$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
					} else {
						$ip = $_SERVER['REMOTE_ADDR'];
					}
					$_SESSION['ip'] = $ip;
					$_SESSION['id'] = session_id();
				}
				$_SESSION['cpt_question'] = $_SESSION['cpt_question']-1;
				return [false,$_SESSION['cpt_question'],bot_question(""),$_SESSION];
			}
		}
		else {
			//echo "ERROR : QUESTION is UNIQUE\n"; // UPDATE TABLE CONSTRAINT
			return [false,-1];
		}
		//return [$_SESSION];
	}
	return array();
	//empty for the moment 
	//search in a specific database with 100 questions and response 
	//NOT INFINITE TEST 
	//MAX 1 TRY PER QUESTION AND 3 Q MAX IF NOT GOOD ANSWER AT THE END OF 
	//THESE QUESTIONS WE UPDATE THE BAN LIST IP AND SIGNIFICANT HAS A ROBOT 
}

if(isset($_REQUEST['q']) && isset($_REQUEST['a'])) {
	$q = $_REQUEST['q'];
	$a = $_REQUEST['a'];
	//echo $q;
	// 
	//session();
	// MISE À JOUR DU COMPTEUR ET MAINTIENT DU COMPTEUR POUR CHAQUE TENTATIVE 
	// ENTRE LE JS ET LE PHP !!! 
	//search_q_and_a($q,$a,$cpt);
	//session();
	$response = search_q_and_a($q,$a);
	$myArr = $response;
	$myJSON = json_encode($myArr);
  	echo $myJSON;
}



if(isset($_REQUEST['session'])) {
	//BAN_IP 
	ban_IP($_SESSION['ip']);
	session_destroy();
	$_SESSION = array();
	$my_json = json_encode($_SESSION);
	echo $my_json;
}


/*
if(isset($_REQUEST['bot'])) {
	//SEARCH IP AND BAN 
}*/
/*create_private_db_table();
insert_q_and_a("Y'a t'il des oeufs dans une pâte à crèpes ?","oui");*/
//var_dump(search_q_and_a("Y'a t'il des oeufs dans une pâte à crèpes ?","oui",3));

//insert_q_and_a("Y'a","oui");
//var_dump(addslashes_home("Y'a t'il du pain"));
//insert_q_and_a("Des oeufs dans une omelette ?","Oui");
//var_dump(search_q_and_a("Des oeufs dans une omelette ?","non"));
//var_dump(ip4toip6("127.0.0.1"));
//create_ban_table(); ok 

//"CREATE TABLE IF NOT EXISTS Bans (id INTEGER PRIMARY KEY AUTOINCREMENT,ip4 VARCHAR(16), ip6 VARCHAR(32), )";

?>