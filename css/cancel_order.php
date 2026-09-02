<?php
include('dbconnect.php');
if (isset($_POST['order_id']) && isset($_POST['cancel'])) {
    $order_id = mysqli_real_escape_string($con, $_POST['order_id']);
    $cancelReason = mysqli_real_escape_string($con, $_POST['cancel']);
    $date = date('d-m-Y');
    error_log("Order ID: $order_id");
    error_log("Cancel Reason: $cancelReason");
    $insertReviewQuery = "INSERT INTO cancelled_order (order_id, feedback, date) VALUES ('$order_id', '$cancelReason', '$date')";
    
    if (!mysqli_query($con, $insertReviewQuery)) {
        echo json_encode(['status' => 'success', 'message' => 'Order Cancelled successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to cancel order. Please try again.']);
    }
    $updateStatusQuery = "INSERT INTO order_sts (id, order_id, sts_date, status) VALUES (NULL, '$order_id', '$date', 5)";
   
    if (!mysqli_query($con, $updateStatusQuery)) {
        echo json_encode(['status' => 'success', 'message' => 'Order Cancelled successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to cancel order. Please try again.']);
    }

    echo 'Cancellation successful.';
} else {
    echo 'Order ID or cancellation reason not provided.';
}
?>
