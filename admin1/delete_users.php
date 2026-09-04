<?php
include("../config.php");

session_start();


// sending query
mysqli_query($con,"DELETE FROM user WHERE user_id = '".$_GET['cat_del']."'");
header("location:allusers.php");  



?>
