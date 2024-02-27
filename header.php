<?php require_once ',_.php';?>
<?php 
	if(session_status() == PHP_SESSION_ACTIVE)
		echo "active \n";
	session_start();
	if(session_id()=="") {
		$ip = "";//"127.0.0.1";
		$_SESSION['id'] = session_id();
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) 
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) 
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else 
			$ip = $_SERVER['REMOTE_ADDR'];
		
		$check_ip = "NOTBAN";//check_IP($ip);
		if($check_ip === "BAN") {
			//ban_page();
		}
		else {
			//OK GO TO CONNECT 
		}
		//echo "inactive \n";
		//check IP here 
		$_SESSION['cpt_question'] = 3;
		$_SESSION['active'] = true;
	}
	else {
		$ip = "";//"127.0.0.1";
		//$_SESSION['id'] = session_id();
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) 
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) 
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else 
			$ip = $_SERVER['REMOTE_ADDR'];
		
		$check_ip = "NOTBAN";//check_IP($ip);
		if($check_ip === "BAN") {
			//REJECT CONNECTION
			/*echo "die \n";*/
			//ban_page();
		}
		else {
			//OK GO TO CONNECT 
		
		}
		//$_SESSION['active'] = false;
	}
	/*else {
		//session_destroy();
		$_SESSION['error'] = true;
	}
	$_SESSION['id'] = session_id();*/
	
	
	//session_destroy();
//var_dump($_SESSION);
//var_dump($_SERVER);
?> 