<?php require_once ',_.php';?>
<?php 
	session_start();
	$ip = "";//setSession();
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} 
	else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} 
	else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	$_SESSION['ip'] = $ip;
	$_SESSION['id'] = session_id();
	if(check_IP($ip,1) === true) 
		ban_page($ip);
	
?> 