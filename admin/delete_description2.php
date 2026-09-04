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
require_once('header.php'); // Include your database connection file

// Check if 'desc_id' and 'prd_id' are provided in the URL
if (isset($_GET['desc_id']) && isset($_GET['prd_id'])) {
    $desc_id = $_GET['desc_id'];
    $prd_id = $_GET['prd_id'];

    try {
        // Prepare SQL to delete the description
        $statement = $pdo->prepare("DELETE FROM prd_description WHERE id = ?");
        $statement->execute(array($desc_id));

        // Set a success message in session and redirect to the main page
        $_SESSION['success_message'] = 'Description deleted successfully.';
        header("Location: update_desc.php?prd_id=$prd_id"); // Replace 'description_page.php' with your actual page
        exit;
    } catch (Exception $e) {
        // Set an error message in session if an exception occurs
        $_SESSION['error_message'] = 'Error occurred while deleting the description: ' . $e->getMessage();
        header("Location: update_desc.php?prd_id=$prd_id");
        exit;
    }
} else {
    // Redirect back if desc_id or prd_id is not set
    $_SESSION['error_message'] = 'Invalid request. Please try again.';
    header("Location: update_desc.php"); // Replace with your actual description page
    exit;
}
