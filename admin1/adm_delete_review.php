<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/inc/config.php';
if (!isset($_SESSION["admin1_user"])) {
    header("Location: login.php");
    exit;
}
if (!isset($_SESSION["adm_id"])) {
    $_SESSION["adm_id"] = $_SESSION["admin1_user"]["id"] ?? 1;
}
if (!isset($_SESSION["user"])) {
    $_SESSION["user"] = [
        "id"        => $_SESSION["admin1_user"]["id"] ?? 1,
        "full_name" => $_SESSION["admin1_user"]["full_name"] ?? "Admin",
        "email"     => $_SESSION["admin1_user"]["email"] ?? "",
        "photo"     => $_SESSION["admin1_user"]["photo"] ?? "no_image.png",
    ];
}
?>
<?php 

include "../dbconnect.php";
$id=$_GET['id'];
$query="delete from cust_reviews where uid='$id'";
$result=mysqli_query($con,$query);
if($result){
    echo "<script>alert('delete successfully');window.location.href='productreview.php';</script>";
}
else{
    echo "<script>alert('failed to deleted');window.location.href='productreview.php';</script>";
}
?>
