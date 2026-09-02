<?php
include("inc/config.php");
error_reporting(0);
session_start();

$id = $_GET['cat_del'];
// sending query
mysqli_query($con,"DELETE FROM promo WHERE id = $id");
header("location:add_coupon.php");  

?>
