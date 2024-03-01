<?php 

function create_connected_table() {
	try {
		$bdd = new PDO('sqlite:' . dirname(__FILE__) . '/,database.db');
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

function connected_IP() { // insert 
	$ip_to_add = $_SESSION['ip'];
	$ip_v = ip_version($ip_to_add);
	if(($ip_v !== NULL) && (!!!check_IP($ip_to_add,1))) {
		try {
			$bdd = new PDO('sqlite:' . dirname(__FILE__) . '/,database.db');
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


function update_visits() {
	$ip_to_update = $_SESSION['ip'];
	$ip_v = ip_version($ip_to_update);
	if(($ip_v !== NULL) && (!!!check_IP($ip_to_update,1))) {
		try {
			$bdd = new PDO('sqlite:' . dirname(__FILE__) . '/,database.db');
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

function create_ban_table() {
	try {
		$bdd = new PDO('sqlite:' . dirname(__FILE__) . '/,database.db');
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

function ban_IP() { // insert 
	$ip_to_ban = $_SESSION['ip'];
	$ip_v = ip_version($ip_to_ban);
	if($ip_v !== NULL) {
		try {
			$bdd = new PDO('sqlite:' . dirname(__FILE__) . '/,database.db');
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

function ip_version(string $ip) {
	return (filter_var($ip,FILTER_VALIDATE_IP,FILTER_FLAG_IPV4)) ? "ip4" : ((filter_var($ip,FILTER_VALIDATE_IP,FILTER_FLAG_IPV6)) ? "ip6" : NULL);
}

function check_IP(string $ip, int $type) : bool { // check if ip is ban 
	//type 1 = ban
	//type 2 = connected
	if(($ip_v = ip_version($ip)) && ($ip_v !== NULL)) {
		try {
			$bdd = new PDO('sqlite:' . dirname(__FILE__) . '/,database.db');
			$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "";
			if($type == 1)
				$sql = "SELECT ip from Bans where ip = :ip";
			else if($type == 2) {
				$sql = "SELECT ip from Connected where ip = :ip";
			}
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

function ban_page($ip) {
	return die("<h3>Banned on the $ip IP address</h3>\n<button onclick=\"window.location.assign('index_meli.php')\">Mes recettes</button>");
}

?>