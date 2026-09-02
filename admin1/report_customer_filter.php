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
error_reporting(0);
include('./inc/config.php');

function getOrderStatus($status) {
    switch ($status) {
        case 0: return '<span class="text-primary">Order Placed</span>';
        case 1: return '<span class="text-success">Order Processed</span>';
        case 2: return '<span class="text-success">Order Packing</span>';
        case 3: return '<span class="text-secondary">Order Delivered</span>';
        case 4: return '<span class="text-danger">Order Declined</span>';
        case 5: return '<span class="text-danger">Order Cancelled</span>';
        case 6: return '<span class="text-warning">Order Refund</span>';
        default: return '<span>Unknown Status</span>';
    }
}

if (isset($_POST['user_id'])) {
    $filter_add_1 = '';
    $filter_add_2 = '';
    if (isset($_POST['startDate']) && isset($_POST['endDate']) && !empty($_POST['startDate']) && !empty($_POST['endDate'])) {
        $startDate = DateTime::createFromFormat('Y-m-d', $_POST['startDate'])->format('d-m-Y');
        $endDate = DateTime::createFromFormat('Y-m-d', $_POST['endDate'])->format('d-m-Y');
        
        $filter_add_1 = "AND order_date BETWEEN '$startDate' AND '$endDate' ";
    }
    

    // Status filtering
    if (isset($_POST['status']) && $_POST['status'] !== '') {
        $status = (int) $_POST['status'];
        $filter_add_2 = "AND o.status = $status "; 
    }

    $response = '';
    $product = mysqli_real_escape_string($con, $_POST['user_id']); 

    // Build the query
    $query = "
    SELECT *, SUM(final.qty) AS total_qty, SUM(final.final_amt) AS total_amt, MAX(o.status) AS latest_status
    FROM final 
    JOIN dishes ON final.product_id = dishes.rs_id  
    JOIN (
        SELECT order_id, MAX(id) AS latest_status_time 
        FROM order_sts
        GROUP BY order_id
    ) AS latest_order ON final.order_id = latest_order.order_id 
    JOIN order_sts o ON latest_order.order_id = o.order_id AND o.id = latest_order.latest_status_time
    WHERE final.user_id = '$product' 
    $filter_add_1
    $filter_add_2
    GROUP BY final.order_id, final.order_date
    ";

    $select = mysqli_query($con, $query);
    $totalAmount = 0; // Initialize total amount variable

    if ($select) {
        $response = '<table class="table table-bordered" id="export_modal_table"> 
         <thead>
        <tr>
            <th>Date</th>
            <th>Order id</th>
            <th>Quantity</th>
            <th>Amount</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>';

        while ($row = mysqli_fetch_array($select)) {
            $response .= '<tr>
                <td>' . $row['order_date'] . '</td>
                <td>' . htmlspecialchars($row['order_id']) . '</td>
                <td>' . htmlspecialchars($row['qty']) . '</td>
                <td>₹ ' . htmlspecialchars($row['final_amt']) . '</td>
                <td>' . getOrderStatus($row['status']) . '</td>
            </tr>';

            // Sum the final_amt to totalAmount
            $totalAmount += $row['final_amt'];
        }
        
        $response .= '</tbody>';
        
        // Add tfoot for total amount
        $response .= '<tfoot>
            <tr>
                <td colspan="4" style="text-align: right;"><strong>Total:</strong></td>
                <td>₹ ' . htmlspecialchars($totalAmount) . '</td>
                <td></td>
            </tr>
        </tfoot>';

        $response .= '</table>';

        echo json_encode(['status' => 1, 'response' => $response]);
    } else {
        echo json_encode(['status' => 0, 'message' => 'Query failed: ' . mysqli_error($con)]);
    }
} else {
    echo json_encode(['status' => 0, 'message' => 'Invalid input.']);
}
?>
