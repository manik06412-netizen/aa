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
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('inc/config.php');
$date = date("d-m-Y"); 

if (isset($_POST['product_id']) && isset($_POST['price_id']) && isset($_POST['status_of'])) {
    
    $productId = mysqli_real_escape_string($con, $_POST['product_id']);
    $priceId = mysqli_real_escape_string($con, $_POST['price_id']);
    $statusOf = mysqli_real_escape_string($con, $_POST['status_of']);

    // Prepare and execute a SELECT statement
    $stmt = $con->prepare("SELECT * FROM prd_stock WHERE pd_code = ? AND price_id = ?");
    $stmt->bind_param("si", $productId, $priceId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {

        $preview_stock = $row['prev_stock'];
        $new_stock = $row['new_stock'];
        $total = $row['total'];
        $reason = $row['reason'];
         $note = $row['note']; 

if($statusOf=='InStock'){
    $note='stock Activated';
}else{
    $note='stock Inactivated';
}
        $insert = mysqli_query($con,"INSERT INTO prd_stock  VALUES (null, '$productId', $priceId, $preview_stock, $new_stock, $total, '$reason', '$date', '$note', '$statusOf')");

        if ($insert) {
            echo json_encode(['status' => 1]);
        } else {
            echo json_encode(['status' => 2]);
        }
    } else {
        echo json_encode(['status' => 3]);
    }
    $con->close();
} else {
    echo json_encode(['status' => 4]);
}
?>