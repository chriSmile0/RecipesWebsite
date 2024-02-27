<?php 

$questions_bans_id = [];

function session() {
	$http_only = true;
	$same_site = "Strict";
	$maxlifetime = 1440;
	$secure = false;
	$domain = "/";//parse_url($_SERVER['HTTP_HOST'], PHP_URL_HOST);

	/*
	 * Need to do this as you can't update the settings of a session
	 * while it is open. So check if one is open, close it if needed
	 * then update the values and start it again.
	*/
	/*if (session_id()) {
		//session_write_close();
		//session_destroy();
		echo "nothing \n";
	}
	else {
		echo "new session\n";
	}*/

	/*session_set_cookie_params([
		'lifetime' => $maxlifetime,
		'path' => '/',
		'domain' => $domain,
		'secure' => $secure,
		'httponly' => $http_only,
		'samesite' => $same_site
	]);*/
	//if(session_id()==="") {
	if(session_status()==PHP_SESSION_ACTIVE) {
		echo "existe deja \n";
	}
	else if(session_status() ==PHP_SESSION_DISABLED) {
		echo "disable \n";
	}
	else {
		session_start();
		session_regenerate_id();
		$_SESSION['id'] = session_id();
		//$_SESSION['params'] = session_get_cookie_params();
		$_SESSION['cpt_question'] = 3;
	}
	/*}
	else {
		echo "existe deja \n";
	}*/
	//var_dump($_SESSION);
}


function no_script() : string {
	return "<noscript id=\"js-check-container\">
	<meta http-equiv=\"refresh\" content=\"0; url=LOVE_JS.html\" />
	<!--<span id=\"js-check-text\">Please activate Javascript!</span>-->
	</noscript>\n";
}

function bot_question(string $v) : string {
	$rtn = "";
	try {
		$bdd = new PDO('sqlite:' . dirname(__FILE__) . '/_database.db');
		$sql = "SELECT question from QandA where id = :id";
		$sql_global = "SELECT max(id) from QandA";
		$id = 1;
		foreach($bdd->query($sql_global) as $row)  // once normally
			$id = intval($row[0]);
		$random_choice = 1;
		$cpt = 0;
		$brk = 0;
		while(1) {
			$random_choice = rand(1,$id);
			if(!in_array($random_choice,$GLOBALS['questions_bans_id'])) {
				array_push($GLOBALS['questions_bans_id'],$random_choice);
				break;
			}
			if($cpt > 2*$id) {
				$brk = 1;
				break;
			}
			$cpt++;
		}
		if($brk == 1) // if enough question this is not necessary because max it's 3 question per user
			$random_choice = 1;
		$stmt = $bdd->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]); // NOT NECESSARY HERE
		$stmt->execute([':id'=> $random_choice]);
		$res = $stmt->fetchAll();
		$rtn = $res[0]['question'].$v;
	}
	catch (PDOException $e) {
		var_dump($e->getMessage());
	}
	return $rtn;
}


// thanks to RobotWood -> digininja
/*for($i = 0; $i < 2; $i++)
	bot_question("");
var_dump($GLOBALS['questions_bans_id']);*/
//var_dump(in_array())
//var_dump($GLOBALS['questions_bans_id']);

//session();
//$_SESSION['cpt_question']--;
//var_dump($_SESSION);

?>