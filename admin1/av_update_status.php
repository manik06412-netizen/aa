<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/inc/config.php';
if (!isset($_SESSION["admin1_user"])) {
    header("Location: login.php");
    exit;
}
if (!isset($_SESSION["user"])) {
    $_SESSION["user"] = [
        "id"        => $_SESSION["admin1_user"]["id"] ?? 1,
        "full_name" => $_SESSION["admin1_user"]["full_name"] ?? "Admin",
        "email"     => $_SESSION["admin1_user"]["email"] ?? "",
        "photo"     => $_SESSION["admin1_user"]["photo"] ?? "no_image.png",
    ];
}
if (!isset($_SESSION["adm_id"])) {
    $_SESSION["adm_id"] = $_SESSION["admin1_user"]["id"] ?? 1;
}
?>

<?php
include("../config.php");
error_reporting(0);
session_start();


$s=$_GET['cat_upd'];

        $date = date("D M d Y");
	
	$mql = "update promo set status ='$_GET[status]',dat='$date' where id='$_GET[cat_upd]'";
	mysqli_query($con, $mql);
			

// Redirect to addcategory.php using JavaScript
echo '<script>window.location.href = "add_coupon.php";</script>';
    
	

?>
