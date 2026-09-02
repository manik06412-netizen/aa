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
    $new_total = $new_stock;
    $date = date('d-m-Y');
    $formattedDate = date('F-Y');
    $stk_status = trim($_POST['status']);
    $note = $new_stock . " product(s) set";

    $query = mysqli_query($con, "SELECT * FROM stock_invent WHERE prd_id='$pd_code' AND price_id='$price_id' ORDER BY id DESC LIMIT 1");
    if ($query2 = mysqli_fetch_array($query)) {
        $tot_stk = (float)$query2['tot_stk'];
    } else {
        $tot_stk = 0;
    }

    $tot_stk1 = $tot_stk + $new_stock;

    $stmt3 = $con->prepare("INSERT INTO stock_invent (prd_id, price_id, in_stk, tot_stk, date_inv, mnt_inv) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt3 === false) {
        echo json_encode(['error' => 'Prepare failed for stock_invent: ' . $con->error]);
        exit;
    }
    $stmt3->bind_param('siidss', $pd_code, $price_id, $new_stock, $tot_stk1, $date, $formattedDate);

    $stmt2 = $con->prepare("INSERT INTO prd_stock (pd_code, price_id, prev_stock, new_stock, total, date, note, stk_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt2 === false) {
        echo json_encode(['error' => 'Prepare failed for prd_stock: ' . $con->error]);
        exit;
    }
    $stmt2->bind_param('siiddsss', $pd_code, $price_id, $prev, $new_stock, $new_total, $date, $note, $stk_status);

    if ($stmt3->execute()) {
        if ($stmt2->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Execution failed: ' . $stmt2->error]);
        }
    } else {
        echo json_encode(['error' => 'Execution failed: ' . $stmt3->error]);
    }

    $stmt3->close();
    $stmt2->close();
} else {
    echo json_encode(['error' => 'Invalid input']);
}
?>
