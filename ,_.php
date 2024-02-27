<?php 
function create_ban_table() {
	try {
		$bdd = new PDO('sqlite:' . dirname(__FILE__) . '/,database.db');
		//$bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // ERRMODE_WARNING | ERRMODE_EXCEPTION | ERRMODE_SILENT
		//$bdd->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
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
		//var_dump($e);
		var_dump($e->getMessage());
	}
}

/*function test_ip(string $ip) { // validate IP address (IPV6)
	$rtn = NULL;
	$test = (filter_var($ip,FILTER_VALIDATE_IP,FILTER_FLAG_IPV4)) ? true : ((filter_var($ip,FILTER_VALIDATE_IP,FILTER_FLAG_IPV6)) ? true : false);
	if($test)
		$rtn = $ip;
	return $rtn;
}*/

function ban_IP() { // insert 
	$ip_to_ban = $_SESSION['ip'];
	$ip_v = ip_version($ip_to_ban);
	if($ip_v !== NULL) {
		try {
			$bdd = new PDO('sqlite:' . dirname(__FILE__) . '/,database.db');
			$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // ERRMODE_WARNING | ERRMODE_EXCEPTION | ERRMODE_SILENT
			//$q = test_input($q);
			//$q = $bdd->quote($q);
			//$q = addcslashes()
			$sql = "INSERT INTO Bans (ip)
			VALUES (:ip)";
			//Reqûete préparer ici avec .prepare();
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

function ip4toip6(string $ip) {
	/*byte[] octetBytes = new byte[4];

	for (int i = 0; i < 4; ++i) {
				octetBytes[i] = (byte) Integer.parseInt(octets[i]);
	}

	byte ipv4asIpV6addr[] = new byte[16];
	ipv4asIpV6addr[10] = (byte)0xff;
	ipv4asIpV6addr[11] = (byte)0xff;
	ipv4asIpV6addr[12] = octetBytes[0];
	ipv4asIpV6addr[13] = octetBytes[1];
	ipv4asIpV6addr[14] = octetBytes[2];
	ipv4asIpV6addr[15] = octetBytes[3];*/
	//return $octets;
	return NULL;
}
function check_IP(string $ip) { // check if ip is ban 
	//transform to IPV6
	if(($ip_v = ip_version($ip)) && ($ip_v !== NULL)) {
		try {
			$bdd = new PDO('sqlite:' . dirname(__FILE__) . '/,database.db');
			//$bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
			$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // ERRMODE_WARNING | ERRMODE_EXCEPTION | ERRMODE_SILENT
			$sql = "SELECT ip from Bans where ip = :ip";
			$stmt = $bdd->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
			$stmt->execute([':ip'=> $ip]);
			$res = $stmt->fetchAll();
			$size = sizeof($res);
			if($size == 1) {
				if($ip === $res[0]['ip']) {
					//echo "GOOD LETS GO YOUR NOT A ROBOT";
					return "BAN";
				}
				else {
					//echo "NEW QUESTION FOR YOU , essai restant = " . strval($_SESSION['cpt_question']-1);
					return "NOTBAN";
				}
			}
			else {
				return "NOTBAN";
			}
		}
		catch(PDOException $e) {
			var_dump($e->getMessage());
		}
	}
	else {
		//error 
	}
}
//var_dump(check_IP("127.0.0.1"));

function ban_page() {
	return die("<h3>Banned</h3>");
}
?>