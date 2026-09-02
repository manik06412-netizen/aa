<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/inc/config.php';
if (!isset($_SESSION["admin1_user"])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

header('Content-Type: application/json');
error_reporting(0);

if (isset($_POST['prd_code'], $_POST['price_id'], $_POST['prev_total'], $_POST['new_stock'], $_POST['status'])) {
    $pd_code = trim($_POST['prd_code']);
    $price_id = (int)$_POST['price_id'];
    $prev = (float)$_POST['prev_total'];
    $new_stock = (float)$_POST['new_stock'];
    $new = $prev - $new_stock;
    $date = date('d-m-Y');
    $stk_status = trim($_POST['status']);
    $note = $new_stock . " product(s) reduced";

    $stmt2 = $con->prepare("INSERT INTO prd_stock (pd_code, price_id, prev_stock, new_stock, total, date, note, stk_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt2 === false) {
        echo json_encode(['error' => 'Prepare failed: ' . $con->error]);
        exit;
    }

    $stmt2->bind_param('siiddsss', $pd_code, $price_id, $prev, $new_stock, $new, $date, $note, $stk_status);
    if ($stmt2->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Execution failed: ' . $stmt2->error]);
    }
   
    $stmt2->close();
} else {
    echo json_encode(['error' => 'Invalid input']);
}
?>
