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
session_start();
error_reporting(0);
include ("../dbconnect.php");

if (isset($_POST['Standard'])) {
    $selectedStandard = $_POST['Standard'];
    $query = "SELECT DISTINCT currency_code FROM countries WHERE country_name = '$selectedStandard'";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode(['status' => true, 'res' => $row['currency_code']]);
    } else {
        echo json_encode(['status' => false, 'res' => 'Currency code not found']);
    }
} else {
    echo json_encode(['status' => false, 'res' => 'No country selected']);
}
?>
