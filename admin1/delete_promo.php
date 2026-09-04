<?php
include("../config.php");
error_reporting(0);
session_start();


// sending query
mysqli_query($con,"DELETE FROM promo WHERE id = '".$_GET['cat_del']."'");
header("location:coupon.php");  

?>
