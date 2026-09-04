<?php
include("../dbconnect.php");
error_reporting(0);
session_start();


// sending query
mysqli_query($con,"DELETE FROM deals_prot WHERE d_id = '".$_GET['cat_del']."'");
echo "<script>window.location.href='deals_view.php';</script>";

?>
