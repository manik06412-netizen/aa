<?php
ob_start();
session_start();
error_reporting(0);
include("inc/config.php");

mysqli_query($con,"DELETE FROM topbar WHERE id = '".$_GET['cat_del']."'");
header("location:add_info.php");  

?>
