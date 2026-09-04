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
require_once('header.php'); // Include your database connection here

// Check if the ID is set and delete the product
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Prepare the delete query
        $stmt = $pdo->prepare("DELETE FROM dishes WHERE d_id = ?");
        $stmt->execute([$id]);

        // Check if the product was successfully deleted
        if ($stmt->rowCount() > 0) {
            $_SESSION['success_message'] = 'Product deleted successfully.';
        } else {
            $_SESSION['error_message'] = 'Product could not be deleted.';
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = 'Error occurred while deleting the product: ' . $e->getMessage();
    }
} else {
    $_SESSION['error_message'] = 'Invalid product ID.';
}

// Redirect back to the category page after deletion
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
