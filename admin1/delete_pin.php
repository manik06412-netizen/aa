<?php
include("../dbconnect.php");
error_reporting(0);
session_start();


// sending query
mysqli_query($con,"DELETE FROM pinamount WHERE id = '".$_GET['cat_del']."'");
header("location:pinsave.php");  

?>
