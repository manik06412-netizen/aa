<?php
ob_start();
session_start();
error_reporting(0);
include("inc/config.php");


// sending query
mysqli_query($con,"DELETE FROM btype WHERE id = '".$_GET['cat_del']."'");
header("location:add_measurements.php");  

?>
