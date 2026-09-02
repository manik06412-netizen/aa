<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/inc/config.php';
if (!isset($_SESSION["admin1_user"])) {
    header("Location: login.php");
    exit;
}

if (!isset($_REQUEST['slider_del'])) {
    header('Location: sliders.php');
    exit;
} else {
    $id = $_REQUEST['slider_del'];
    $id = mysqli_real_escape_string($con, $id);

    // Get the image path to delete the file
    $query = mysqli_query($con, "SELECT banner_img FROM slider WHERE id='$id'");
    if ($query && mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);
        $image_path = __DIR__ . "/../" . $row['banner_img'];
        
        if (file_exists($image_path) && !empty($row['banner_img'])) {
            unlink($image_path);
        }
    }

    // Delete from DB
    $del = mysqli_query($con, "DELETE FROM slider WHERE id='$id'");
    if ($del) {
        $_SESSION['flash_success'] = 'Slider deleted successfully!';
    } else {
        $_SESSION['flash_error'] = 'Failed to delete slider.';
    }

    header('Location: sliders.php');
    exit;
}
?>
