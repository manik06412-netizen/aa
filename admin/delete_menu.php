<?php
include("../config.php");
error_reporting(0);
session_start();


// sending query
mysqli_query($con,"DELETE FROM dishes WHERE d_id = '".$_GET['cat_del']."'");

echo "<script> window.location.href='all_menu.php';</script>";

?>
