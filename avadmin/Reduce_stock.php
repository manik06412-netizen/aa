<?php
session_start();
include("inc/config.php");
// Ensure POST data is set
if (isset($_POST['prd_code'], $_POST['price_id'], $_POST['prev_total'], $_POST['new_stock'], $_POST['status'])) {
    $stmt2 = $con->prepare("INSERT INTO prd_stock (pd_code, price_id, prev_stock, new_stock, total, date, note, stk_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    if ($stmt2 === false) {
        die('Prepare failed: ' . $con->error);
    }

    $pd_code = $_POST['prd_code'];
    $price_id = $_POST['price_id'];
    $prev = $_POST['prev_total'];
    $new_stock = $_POST['new_stock'];
    $new=$_POST['prev_total']-$_POST['new_stock'];
    $total = $_POST['prev_total']; 
    $date = date('Y-m-d');
    $stk_status = $_POST['status'];
    $note = $_POST['new_stock'] . " product added";
   
    $stmt2->bind_param('siidssss', $pd_code, $price_id, $prev, $new_stock, $new, $date, $note, $stk_status);
    if ($stmt2->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Execution failed: ' . $stmt2->error]);
    }
   
    $stmt2->close();
} else {
    echo json_encode(['error' => 'Invalid input']);
}
$con->close();
?>
