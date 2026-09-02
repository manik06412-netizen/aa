<?php
session_start();
include("inc/config.php");

if (isset($_POST['prd_code'], $_POST['price_id'], $_POST['prev_total'], $_POST['new_stock'], $_POST['status'])) {
    // Assigning form input to variables
    $pd_code = $_POST['prd_code'];
    $price_id = $_POST['price_id'];
    $prev = (float)$_POST['prev_total'];
    $new_stock = (float)$_POST['new_stock'];
    $new = $prev + $new_stock;
    $date = date('d-m-Y');
    $stk_status = $_POST['status'];
    $note = $new_stock . " product(s) added";

    // Querying the stock_invent table
    $query = mysqli_query($con, "SELECT * FROM stock_invent WHERE prd_id='$pd_code' AND price_id='$price_id' ORDER BY id DESC LIMIT 1");
    if ($query2 = mysqli_fetch_array($query)) {
        $tot_stk = (float)$query2['tot_stk'];
    } else {
        $tot_stk = 0; // If no record found, default total stock to 0
    }

    // Updating stock
    $tot_stk1 = $tot_stk + $new_stock;
    $formattedDate = date('F-Y'); // 'Month-Year' format

    // Prepare the statement for prd_stock table
    $stmt2 = $con->prepare("INSERT INTO prd_stock (pd_code, price_id, prev_stock, new_stock, total, date, note, stk_status) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    if ($stmt2 === false) {
        die('Prepare failed for prd_stock: ' . $con->error);
    }

    $stmt2->bind_param('siiddsss', $pd_code, $price_id, $prev, $new_stock, $new, $date, $note, $stk_status);

    // Prepare the statement for stock_invent table
    $stmt3 = $con->prepare("INSERT INTO stock_invent (prd_id, price_id, in_stk, tot_stk, date_inv, mnt_inv) 
                            VALUES (?, ?, ?, ?, ?, ?)");

    if ($stmt3 === false) {
        die('Prepare failed for stock_invent: ' . $con->error);
    }

    $stmt3->bind_param('siidss', $pd_code, $price_id, $new_stock, $tot_stk1, $date, $formattedDate);

    // Execute the second insert statement for stock_invent
    if ($stmt3->execute()) {
        // Execute the first insert statement for prd_stock
        if ($stmt2->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Execution failed: ' . $stmt2->error]);
        }
    } else {
        echo json_encode(['error' => 'Execution failed: ' . $stmt3->error]);
    }

    // Closing the statements
    $stmt3->close();
    $stmt2->close();
} else {
    echo json_encode(['error' => 'Invalid input']);
}

// Closing the connection
$con->close();
?>
