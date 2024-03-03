<?php 

/**
 * [BRIEF]	Create a table for known all IP connects on the website
 * 			[WHY] : 
 * 				- For statistics for the scaling of the website
 * @param 	void
 * @example create_connected_table()
 * @author	chriSmile0
 * @return 	/ 
*/
function create_connected_table() {
	try {
		$bdd = new PDO('sqlite:' . dirname(__FILE__) . '/../db/,database.db');
		$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "CREATE TABLE IF NOT EXISTS Connected (
				id INTEGER PRIMARY KEY AUTOINCREMENT,
				ip VARCHAR(32) NOT NULL,  
				format VARCHAR(3) CHECK(format LIKE 'ip6' OR format LIKE 'ip4'), /*useless ? */
				visits INTEGER NOT NULL,
				date_time DATETIME NOT NULL,
				CONSTRAINT CHK_IP CHECK((ip LIKE '%.%.%.%' AND format LIKE 'ip4') OR (ip LIKE '%::%' AND format LIKE 'ip6')) /*MAYBE CREATE MORE COMPLEX CHECK FOR CHECK THE FORMAT*/
			)";
		$bdd->exec($sql);
		$bdd = null;
	}
	catch (PDOException $e) {
		var_dump($e->getMessage());
	}
}

/**
 * [BRIEF]	Insert the IP of the user, add the date_time of the visits and the 
 * 			the number of visits(1)
 * @param 	void 
 * @example connected_IP()
 * @author	chriSmile0
 * @return 	/
*/
function connected_IP() { // insert 
	$ip_to_add = $_SESSION['ip'];
	$ip_v = ip_version($ip_to_add);
	if(($ip_v !== NULL) && (!!!check_IP($ip_to_add,1))) {
		try {
			$bdd = new PDO('sqlite:' . dirname(__FILE__) . '/../db/,database.db');
			$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // ERRMODE_WARNING | ERRMODE_EXCEPTION | ERRMODE_SILENT
			$sql = "INSERT INTO Connected (ip,visits,date_time)
			VALUES (:ip,1,datetime('now','localtime'))";
			$stmt = $bdd->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
			$stmt->execute([':ip'=> $ip_to_add]);
			//"INSERT INTO Connected (ip,visits,date_time) VALUES (::1,1,datetime('now','localtime'))";
		}
		catch (PDOException $e) {
			var_dump($e->getMessage());
		}
	}
}

/**
 * [BRIEF]	Update the visits of differents user with update the last connexion
 * 			with the 'now' time, (@see{ip_version/check_IP})
 * @param 	void 
 * @example update_visits()
 * @author	chriSmile0
 * @return 	/
*/
function update_visits() {
	$ip_to_update = $_SESSION['ip'];
	$ip_v = ip_version($ip_to_update);
	if(($ip_v !== NULL) && (!!!check_IP($ip_to_update,1))) {
		try {
			$bdd = new PDO('sqlite:' . dirname(__FILE__) . '/../db/,database.db');
			$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql_v = "SELECT visits from Connected where ip = :ip";
			$stmt = $bdd->prepare($sql_v, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
			$stmt->execute([':ip'=> $ip_to_update]);
			$res = $stmt->fetchAll();
			$size = sizeof($res); 
			if($size == 1) { 
				$sql_u = "UPDATE Connected SET visits = :val, date_time = datetime('now','localtime')"; // TRIGGER BETTER BUT NOT FOR THE MOMENT 
				$stmt = $bdd->prepare($sql_u, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
				$stmt->execute([':val'=> ($res[0]['visits'])+1]);
			}
			else if($size == 0) {
				connected_IP($ip_to_update);
			}
		}
		catch (PDOException $e) {
			var_dump($e->getMessage());
		}
	}
	else {
		var_dump("Error format or ban \n");
	}
}

/**
 * [BRIEF]	Create BANLIST for restrict access to the form access (update all DATABASEs)
 * 			[WHY] : 
 * 				- BOT blocking 
 * 				- User with bad intention
 * @param 	void
 * @example create_ban_table()
 * @author	chriSmile0
 * @return  /
*/
function create_ban_table() {
	try {
		$bdd = new PDO('sqlite:' . dirname(__FILE__) . '/../db/,database.db');
		$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "CREATE TABLE IF NOT EXISTS Bans (
				id INTEGER PRIMARY KEY AUTOINCREMENT,
				ip VARCHAR(32) NOT NULL,  
				format VARCHAR(3) CHECK(format LIKE 'ip6' OR format LIKE 'ip4'),
				CONSTRAINT CHK_IP CHECK((ip LIKE '%.%.%.%' AND format LIKE 'ip4') OR (ip LIKE '%::%' AND format LIKE 'ip6')) /*MAYBE CREATE MORE COMPLEX CHECK FOR CHECK THE FORMAT*/
			)";
		$bdd->exec($sql);
		$bdd = null;
	}
	catch (PDOException $e) {
		var_dump($e->getMessage());
	}
}

/**
 * [BRIEF]	(@see connected_IP)
 * @param 	void
 * @example ban_IP()
 * @author	chriSmile0
 * @return  /
*/
function ban_IP() { // insert 
	$ip_to_ban = $_SESSION['ip'];
	$ip_v = ip_version($ip_to_ban);
	if($ip_v !== NULL) {
		try {
			$bdd = new PDO('sqlite:' . dirname(__FILE__) . '/../db/,database.db');
			$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // ERRMODE_WARNING | ERRMODE_EXCEPTION | ERRMODE_SILENT
			$sql = "INSERT INTO Bans (ip)
			VALUES (:ip)";
			$stmt = $bdd->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
			$stmt->execute([':ip'=> $ip_to_ban]);
		}
		catch (PDOException $e) {
			var_dump($e->getMessage());
		}
	}
}

/**
 * [BRIEF]	CHECK THE VERSION AND THE CORRECT FORMAT OF THE IP 
 * @param 	string	$ip	the ip of user
 * @example ip_version("127.0.0.1")/ip_version("::1")/ip_version("256.0.0.1")
 * @author	chriSmile0
 * @return 	ip4/ip6/NULL
*/
function ip_version(string $ip) {
	return (filter_var($ip,FILTER_VALIDATE_IP,FILTER_FLAG_IPV4)) ? "ip4" : ((filter_var($ip,FILTER_VALIDATE_IP,FILTER_FLAG_IPV6)) ? "ip6" : NULL);
}

/**
 * [BRIEF]	Check the $ip with the ip_version and select the corresponding IP
 * 			in the database
 * @param 	string	$ip		the $ip to research	
 * @param 	int		$type	the $type of research (Bans=1,Connected=2)
 * @example check_IP("127.0.0.1",1)
 * @author	chriSmile0
 * @return 	bool true/false	if $ip is found return true, else return false
*/
function check_IP(string $ip, int $type) : bool { // check if ip is ban 
	if(($ip_v = ip_version($ip)) !== NULL) {
		try {
			$bdd = new PDO('sqlite:' . dirname(__FILE__) . '/../db/,database.db');
			$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "";
			if($type == 1)
				$sql = "SELECT ip from Bans where ip = :ip";
			else if($type == 2) 
				$sql = "SELECT ip from Connected where ip = :ip";
			
			$stmt = $bdd->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
			$stmt->execute([':ip'=> $ip]);
			$res = $stmt->fetchAll();
			$size = sizeof($res);
			if($size == 1) 
				return $ip === $res[0]['ip'];
			return false;
		}
		catch(PDOException $e) {
			var_dump($e->getMessage());
		}
	}
	return false;
}

/**
 * [BRIEF]	Redirect to the ban page information (create by die() function)
 * @param 	string	$ip	the $ip is banned
 * @example ban_page("127.0.0.1")
 * @author	chriSmile0
 * @return 	/
*/
function ban_page(string $ip) {
	return die("<h3>Banned on the $ip IP address</h3>\n<button onclick=\"window.location.assign('index_meli.php')\">Mes recettes</button>");
}

/**
 * [BRIEF]
 * @param 
 * @example 
 * @author	chriSmile0
 * @return 
*/
?>