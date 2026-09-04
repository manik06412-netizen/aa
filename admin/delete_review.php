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

include "../dbconnect.php";

// Get the review ID and the product ID from the query parameters
$id = $_GET['id'];
$product_id = $_GET['product_id'];

// Delete the review based on the review ID
$query = "DELETE FROM cust_reviews WHERE uid='$id'";
$result = mysqli_query($con, $query);

// Redirect to the `get_reviews.php` page with the selected product ID
header("Location:reviews.php");
exit;

?>
