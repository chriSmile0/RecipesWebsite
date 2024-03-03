<?php require_once('___.php');?>
<?php require 'header.php'; ?>
<?php 

/**
 * [BRIEF]	Sanitize input element
 * @param 	$data	the data was in the input fields
 * @example test_input2("'=1 ' Hello")
 * @author	chriSmile0
 * @return	array|string	the content return by the htmlspecialchars 
*/
function test_input2($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

/**
 * [BRIEF]	A db to access just by the website administrator
 * 			With Question and Answer to the noBot system
 * @param 	void
 * @example create_private_db_table()
 * @author	chriSmile0
 * @return 	/
 * @version 1.0 -> update possible for update 'answer' possibilities
*/
function create_private_db_table() {
	try {
		$bdd = new PDO('sqlite:' . dirname(__FILE__) . '/_database.db');
		$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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

/**
 * [BRIEF]	(@see create_private_db_table()) insert Q and "oui"/"non" answer
 * @param 	string	$q	the question to insert	
 * @param 	string 	$a 	the answer to insert
 * @example insert_q_and_a("Es tu un humain","oui")
 * @author	chriSmile0
 * @return 	/
*/
function insert_q_and_a(string $q, string $a) {
	try {
		$bdd = new PDO('sqlite:' . dirname(__FILE__) . '/_database.db');
		$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "INSERT INTO QandA (question,answer)VALUES (:q,:a)";
		$stmt = $bdd->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
		$stmt->execute([':q'=> $q,':a'=>$a]);
	}
	catch (PDOException $e) {
		var_dump($e->getMessage());
	}
}

/**
 * [BRIEF]	Search quesiton in the table of QandA to response to the JS post request
 * 			if it's found but bad response we launch a counter, when this counter
 * 			is 0 you are banned (add to BANLIST)
 * @param 	string	$q	
 * @param 	string	$a 
 * @example search_q_and_a("Es tu un humain","oui")
 * @author	chriSmile0
 * @return 	array	empty or array with boolean and $_SESSIONS details -> $_SESSIOn NOT NECESSARY 
 * @version 1.0 -> (@see create_private_db_table() version update) + update return format
*/
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
		if(sizeof($res) == 1) {
			if($a == $res[0]['answer']) {
				$_SESSION['go'] = true;
				return [true,NULL,$_SESSION];
			}
			else {
				if(key_exists("cpt_question",$_SESSION))
					$_SESSION['cpt_question'] = $_SESSION['cpt_question'] - 1;
				else 
					$_SESSION['cpt_question'] = 2;
				return [false,$_SESSION['cpt_question'],bot_question(),$_SESSION];
			}
		}
		else {
			return [false,-1];
		}
	}
	return array();
}

/**
 * [BRIEF]	ANSWER JS_REQUEST
*/
if(isset($_POST['q']) && isset($_POST['a'])) {
	echo json_encode(search_q_and_a($_POST['q'],$_POST['a']));
}

/**
 * [BRIEF] ANSWER JS_REQUEST
*/
if(isset($_POST['session'])) {
	if($_POST['session'] === "ban") {
		ban_IP();
		session_destroy();
		$_SESSION = array();
		$my_json = json_encode($_SESSION);
		echo $my_json;
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