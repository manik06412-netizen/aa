
<?php
include("../config.php");
error_reporting(0);
session_start();


$s=$_GET['cat_upd'];

        $date = date("D M d Y");
	
	$mql = "update promo set status ='$_GET[status]',dat='$date' where id='$_GET[cat_upd]'";
	mysqli_query($con, $mql);
			

// Redirect to addcategory.php using JavaScript
echo '<script>alert("status changed");window.location.href = "promocode.php";</script>';
    
	

?>
