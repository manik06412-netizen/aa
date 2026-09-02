<?php
/**
 * admin1/quick_edit_price.php
 * AJAX: Quick update a product's price inline
 */
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/inc/config.php';
header('Content-Type: application/json');
error_reporting(0);

if (!isset($_SESSION['admin1_user'])) {
    echo json_encode(['success'=>false,'message'=>'Unauthorized']); exit;
}

$d_id  = intval($_POST['d_id'] ?? 0);
$price = floatval($_POST['price'] ?? -1);

if (!$d_id || $price < 0) {
    echo json_encode(['success'=>false,'message'=>'Invalid data']); exit;
}

// Get product from dishes
$row = mysqli_fetch_assoc(mysqli_query($con, "SELECT rs_id, dish_name FROM dishes WHERE d_id = $d_id LIMIT 1"));
if (!$row) { echo json_encode(['success'=>false,'message'=>'Product not found']); exit; }

$rs_id = mysqli_real_escape_string($con, $row['rs_id']);
$pname = mysqli_real_escape_string($con, $row['dish_name']);
$price_val = round($price, 2);

// Check if entry exists in price table
$check = mysqli_fetch_assoc(mysqli_query($con, "SELECT id FROM price WHERE pcode = '$rs_id' LIMIT 1"));
if ($check) {
    mysqli_query($con, "UPDATE price SET pp = '$price_val' WHERE pcode = '$rs_id'");
} else {
    mysqli_query($con, "INSERT INTO price (pcode, pname, oprice, pp, total_stock, s_status) VALUES ('$rs_id', '$pname', '$price_val', '$price_val', 0, 'Instock')");
}

if (mysqli_errno($con)) {
    echo json_encode(['success'=>false,'message'=>mysqli_error($con)]); exit;
}
echo json_encode(['success'=>true,'message'=>'Price updated','price'=>$price_val]);
