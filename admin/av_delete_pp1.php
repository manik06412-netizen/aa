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
include("../dbconnect.php");
session_start();

$id = $_GET['id']; 
$menu_del = $_GET['menu_del']; 

if ($id) {
    $sql = "DELETE FROM price WHERE id = '$id'";
    if (mysqli_query($con, $sql)) {
        // Redirect back to the original page with the menu_del parameter
        header("Location: add_price.php?prd_id=" . $menu_del);
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($con);
    }
} else {
    // Redirect back to the original page with an error message
    header("Location: add_price.php?prd_id=" . $menu_del . "&error=deletion_failed");
    exit();
}
?>
