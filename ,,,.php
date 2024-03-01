<?php require_once('___.php');?>
<?php require 'header.php'; ?>
<?php 

function test_input2($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

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
				return [false,$_SESSION['cpt_question'],bot_question(""),$_SESSION];
			}
		}
		else {
			return [false,-1];
		}
	}
	return array();
}

if(isset($_REQUEST['q']) && isset($_REQUEST['a'])) {
	echo json_encode(search_q_and_a($_REQUEST['q'],$_REQUEST['a']));
}

if(isset($_REQUEST['session'])) {
	if($_REQUEST['session'] === "ban") {
		ban_IP();
		session_destroy();
		$_SESSION = array();
		$my_json = json_encode($_SESSION);
		echo $my_json;
	}
}
?>