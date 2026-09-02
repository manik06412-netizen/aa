<?php
ob_start();
session_start();
error_reporting(0);
include("inc/config.php");
?>


<?php



	// // Delete from tbl_slider
	$statement = $pdo->prepare("DELETE FROM slider WHERE id=?");
	$statement->execute(array($_GET['slider_del']));

	header('location: sliders.php');
?>