<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/inc/config.php';
if (!isset($_SESSION["admin1_user"])) {
    header("Location: login.php");
    exit;
}

if (!isset($_REQUEST['cat_del'])) {
    header('Location: add_category.php');
    exit;
} else {
    $id = $_REQUEST['cat_del'];
    $id = mysqli_real_escape_string($con, $id);

    // Get the image path to delete the file
    $query = mysqli_query($con, "SELECT fpath FROM res_category WHERE c_id='$id'");
    if ($query && mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);
        $image_path = __DIR__ . "/../" . $row['fpath'];
        
        if (file_exists($image_path) && !empty($row['fpath']) && strpos($row['fpath'], 'no_image') === false) {
            @unlink($image_path);
        }
    }

    // Delete from DB
    $del = mysqli_query($con, "DELETE FROM res_category WHERE c_id='$id'");
    if ($del) {
        $_SESSION['flash_success'] = 'Category deleted successfully!';
    } else {
        $_SESSION['flash_error'] = 'Failed to delete category.';
    }
    
    header('Location: add_category.php');
    exit;
}
?>
