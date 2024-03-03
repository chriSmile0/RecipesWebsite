<?php 
$questions_bans_id = [];

/**
 * [BRIEF]	Generate no_script element for say to user/developper to activate JS
 * @param 	string	$add_path	the path to add in the URL
 * @example no_script("../")
 * @author	chriSmile0
 * @return 	<noscript> tag with redirect URL
*/
function no_script(string $add_path) : string {
	return "<noscript id=\"js-check-container\">
	<meta http-equiv=\"refresh\" content=\"0; url=$add_path"."../home/LOVE_JS.html\" />
	</noscript>\n";
}


/**
 * [BRIEF]	Display a question of the _database for try to block simple
 * 			bot 
 * 			[COUNTER-QUESTION] : 
 * 				- CHATGPT-style RESPONSE	
 * 				- KNOWNS ALL QUESTIONS AND HAVE RESPONSE ON THE ATTACKER BOT
 * 			[COUNTER OF COUNTER-BOT] : 
 * 				- OBVIOUS QUESTION -> FAST RESPONSE -> CHATGPT-style TAKE MORE TIME OF THIS
 * 				- REPLACE QUESTION EACH WEEK (KNOWLEDGE COUNTER)
 * @param 	void
 * @example bot_question()
 * @author	chriSmile0
 * @return 	string	The random question choice in many question in the table QandA
 * @version 1.0	-> update soon 
*/
function bot_question() : string {
	$rtn = "";
	try {
		$bdd = new PDO('sqlite:' . dirname(__FILE__) . '/../../db/_database.db');
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
		$rtn = $res[0]['question'];
	}
	catch (PDOException $e) {
		var_dump($e->getMessage());
	}
	return $rtn;
}

/**
 * [BRIEF]	Display the HTML content for the noBot process with the bot_question
 * 			return 
 * @param 	string	$add_path	the path to add in the URL
 * @example noBot("../")
 * @author	chriSmile0
 * @return 	string	The HTML struct of the QandA process if the user is not identified
 * 					Or go in the index_viewers if he asnwered previously
*/
function noBot(string $add_path) {
	if(!!!key_exists("go",$_SESSION)) {
		$bot_question_rep = bot_question();
		return "<div id=\"NoBot\">
			<h4>Robot cuiseur?</h4>
			<div id=\"botQuestion\">
				<span id=\"question_php\" class=\"q_elems\">$bot_question_rep</span>
				<span id=\"question_error\" class=\"q_elems\"></span>
				<select id=\"select-bot\" class=\"q_elems\" value=\"\">
					<option value=\"oui\">Oui</option>
					<option value=\"non\">Non</option>
				</select>
				<button id=\"NoBotBtn\" class=\"q_elems\">Vérifier</button>
			</div>
			<span id=\"select-bot-hide\"></span>
		</div>
		<script src=\"$add_path"."no_bot.js\"></script>";
	}
	else {
		header("Location: ../");
	}
	return "";
}
// thanks to RobinWood -> digininja
/**
 * [BRIEF]
 * @param 
 * @example 
 * @author	chriSmile0
 * @return 
*/
?>