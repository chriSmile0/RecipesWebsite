<?php require 'header.php'; ?>
<?php 
if(!empty($_SESSION)) {
	if($_SESSION['go']) {
		update_visits();
		header("Location: index_viewers.php");
	}
}
?>