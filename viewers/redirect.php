<?php require '../inc/header.php'; ?>
<?php 
if(!empty($_SESSION)) {
	if($_SESSION['go']) {
		update_visits();
		header("Location: ./");
	}
}
?>