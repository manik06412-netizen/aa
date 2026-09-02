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
