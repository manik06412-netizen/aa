<?php
ob_start();
session_start();
error_reporting(0);
include("inc/config.php");

mysqli_query($con,"DELETE FROM res_category WHERE c_id = '".$_GET['cat_del']."'");
header("location:add_category.php");  

?>
