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
include "../config.php";

// Deleting from the slider table if 'bid' is set
if(isset($_GET['bid'])){
    $idb = $_GET['bid'];
    $del_query = "DELETE FROM slider WHERE id='$idb'";
    $res_del = mysqli_query($con, $del_query);

    if($res_del){
        echo "<script>alert('Deleted successfully from slider');window.location.href='content_banner.php';</script>";
    } else {
        echo "<script>alert('Sorry, something went wrong');window.location.href='content_banner.php';</script>";
    }
}

// Deleting from the topbar table if 'tid' is set
if(isset($_GET['tid'])){
    $ids = $_GET['tid'];
    $del_query1 = "DELETE FROM topbar WHERE id='$ids'";
    $res_del1 = mysqli_query($con, $del_query1);

    if($res_del1){
        echo "<script>alert('Deleted successfully from topbar');window.location.href='web_settings.php?tab=content-section';</script>";
    } else {
        echo "<script>alert('Sorry, something went wrong');window.location.href='web_settings.php?tab=content-section';</script>";
    }
}
?>
