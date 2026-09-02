
<?php
include("../config.php");
error_reporting(0);
session_start();


$s=$_GET['cat_upd'];

        $date = date("D M d Y");
	
	$mql = "update pin set status ='$_GET[status]' where id='$_GET[cat_upd]'";
	mysqli_query($con, $mql);
			

// Redirect to addcategory.php using JavaScript
echo '<script>window.location.href = "pinupdate.php";</script>';
    
	

?>
