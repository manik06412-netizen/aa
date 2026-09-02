<?php
session_start();
include("inc/config.php");

// Ensure POST data is set
if (isset($_POST['prd_code'], $_POST['price_id'], $_POST['prev_total'], $_POST['new_stock'], $_POST['status'])) {

    // Assign form input to variables
    $pd_code = $_POST['prd_code'];
    $price_id = $_POST['price_id'];
    $prev = (float)$_POST['prev_total'];
    $new_stock = (float)$_POST['new_stock'];
    $new_total =  $new_stock;
    $date = date('d-m-Y');
    $formattedDate = date('F-Y'); // 'Month-Year' format
    $stk_status = $_POST['status'];
    $note = $new_stock . " product(s) setted";

    // Querying the stock_invent table to get the current total stock
    $query = mysqli_query($con, "SELECT * FROM stock_invent WHERE prd_id='$pd_code' AND price_id='$price_id' ORDER BY id DESC LIMIT 1");
    
    if ($query2 = mysqli_fetch_array($query)) {
        $tot_stk = (float)$query2['tot_stk'];
    } else {
        $tot_stk = 0; // If no previous record, assume total stock is 0
    }

    // Update the total stock in the inventory (add new stock)
    $tot_stk1 = $tot_stk + $new_stock;

    // Prepare the statement for the stock_invent table
    $stmt3 = $con->prepare("INSERT INTO stock_invent (prd_id, price_id, in_stk, tot_stk, date_inv, mnt_inv) 
                            VALUES (?, ?, ?, ?, ?, ?)");
    
    if ($stmt3 === false) {
        die(json_encode(['error' => 'Prepare failed for stock_invent: ' . $con->error]));
    }

    // Bind the parameters and execute the statement
    $stmt3->bind_param('siidss', $pd_code, $price_id, $new_stock, $tot_stk1, $date, $formattedDate);

    if ($stmt3->execute()) {

        // Prepare the statement for the prd_stock table
        $stmt2 = $con->prepare("INSERT INTO prd_stock (pd_code, price_id, prev_stock, new_stock, total, date, note, stk_status) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        if ($stmt2 === false) {
            die(json_encode(['error' => 'Prepare failed for prd_stock: ' . $con->error]));
        }

        // Bind the parameters and execute the statement
        $stmt2->bind_param('siidssss', $pd_code, $price_id, $prev, $new_stock, $new_total, $date, $note, $stk_status);

        if ($stmt2->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Execution failed: ' . $stmt2->error]);
        }

        // Close the prepared statement for prd_stock
        $stmt2->close();

    } else {
        echo json_encode(['error' => 'Execution failed: ' . $stmt3->error]);
    }

    // Close the prepared statement for stock_invent
    $stmt3->close();

} else {
    echo json_encode(['error' => 'Invalid input']);
}

// Close the connection
$con->close();
?>
