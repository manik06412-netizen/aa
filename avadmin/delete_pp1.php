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
