<?php
include("../config.php");
error_reporting(0);
session_start();


// sending query
mysqli_query($con,"DELETE FROM faq WHERE id = '".$_GET['cat_del']."'");
header("location:qa.php");  

?>
