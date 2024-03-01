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
	
function load_bePatient() {
	if(key_exists("go",$_SESSION)) {
		if($_SESSION['go'] && !key_exists("wait_ok",$_SESSION)) {
			$_SESSION['wait_ok'] = true;
			return "<script>
						window.addEventListener('load',bePatientv2(),false);
					</script>";
		}
		if($_SESSION['go'] && $_SESSION['wait_ok'])
			return "<script>
						window.addEventListener('load',showForm(),false);
					</script>";
	}
	return "";
}
?> 