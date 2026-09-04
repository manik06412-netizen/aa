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
ob_start();
session_start();
error_reporting(0);
include("inc/config.php");
$category_name = trim($_GET['category_name']);
if(isset($_GET['del_id'])){
$p_id = trim($_GET['del_id']);

mysqli_query($con,"DELETE FROM dishes WHERE d_id = $p_id");
header("location:category_of_products_list.php");  

}

if(isset($_GET['status_id'])){
    $status_id = trim($_GET['status_id']);
    $sql=mysqli_query($con,"SELECT * FROM dishes WHERE d_id = $status_id");

    if($result = mysqli_fetch_array($sql)){
        $category=$result['category'];
        $cur_status = $result['status'];
        if($cur_status === 3){
            echo "<script>alert('this is scheduled product!');window.location.href='category_of_products_list.php';</script>";
        }else{
        $update_status = $cur_status == 1 ? 2 : 1;

        $update = mysqli_query($con,"UPDATE dishes set status = $update_status WHERE d_id = $status_id"); 
        header("location:category_of_products_list.php?category_name=$category");  
        }
    } 
}