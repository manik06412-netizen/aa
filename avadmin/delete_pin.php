<?php
ob_start();
session_start();
error_reporting(0);
include("inc/config.php");


mysqli_query($con,"DELETE FROM pinamount WHERE id = '".$_GET['cat_del']."'");
header("location:shipping_costs.php");  

?>
