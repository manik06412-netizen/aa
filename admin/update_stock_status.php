<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/inc/config.php';
if (!isset($_SESSION["admin1_user"])) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 0, 'message' => 'Unauthorized']);
    exit;
}

header('Content-Type: application/json');
error_reporting(0);

$date = date("d-m-Y"); 

if (isset($_POST['product_id']) && isset($_POST['price_id']) && isset($_POST['status_of'])) {
    
    $productId = trim($_POST['product_id']);
    $priceId = (int)$_POST['price_id'];
    $statusOf = trim($_POST['status_of']);

    // Get the latest stock record for this product and price
    $stmt = $con->prepare("SELECT * FROM prd_stock WHERE pd_code = ? AND price_id = ? ORDER BY id DESC LIMIT 1");
    $stmt->bind_param("si", $productId, $priceId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $preview_stock = (int)$row['prev_stock'];
        $new_stock = (int)$row['new_stock'];
        $total = (int)$row['total'];

        if (strcasecmp($statusOf, 'Instock') === 0 || strcasecmp($statusOf, 'In Stock') === 0) {
            $statusOf = 'Instock';
            $note = 'Stock Activated';
        } else {
            $statusOf = 'Currently Unavailable';
            $note = 'Stock Inactivated';
        }

        $stmt2 = $con->prepare("INSERT INTO prd_stock (pd_code, price_id, prev_stock, new_stock, total, date, note, stk_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt2->bind_param("siiiisss", $productId, $priceId, $preview_stock, $new_stock, $total, $date, $note, $statusOf);
        $insert = $stmt2->execute();

        if ($insert) {
            echo json_encode(['status' => 1]);
        } else {
            echo json_encode(['status' => 2, 'message' => 'Failed to insert']);
        }
        $stmt2->close();
    } else {
        // If no prior prd_stock row exists, create initial record
        $default_stock = 0;
        $note = ($statusOf === 'Instock') ? 'Stock Activated' : 'Stock Inactivated';
        $stmt2 = $con->prepare("INSERT INTO prd_stock (pd_code, price_id, prev_stock, new_stock, total, date, note, stk_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt2->bind_param("siiiisss", $productId, $priceId, $default_stock, $default_stock, $default_stock, $date, $note, $statusOf);
        $insert = $stmt2->execute();
        
        if ($insert) {
            echo json_encode(['status' => 1]);
        } else {
            echo json_encode(['status' => 3, 'message' => 'No matching record found']);
        }
        $stmt2->close();
    }
    $stmt->close();
} else {
    echo json_encode(['status' => 4, 'message' => 'Invalid parameters']);
}
?>