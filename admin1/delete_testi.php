<?php
include("../dbconnect.php");
error_reporting(0);
session_start();


// sending query
mysqli_query($con,"DELETE FROM testi WHERE id = '".$_GET['cat_del']."'");
echo "<script>window.location.href='testimonial.php';</script>";

?>
