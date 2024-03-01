<?php 
$questions_bans_id = [];

function no_script() : string {
	return "<noscript id=\"js-check-container\">
	<meta http-equiv=\"refresh\" content=\"0; url=home/LOVE_JS.html\" />
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

function noBot() {
	if(!!!key_exists("go",$_SESSION)) {
		$bot_question_rep = bot_question("");
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
		<script src=\"no_bot.js\"></script>";
	}
	else {
		header("Location: index_viewers.php");
	}
	return "";
}
// thanks to RobinWood -> digininja
?>