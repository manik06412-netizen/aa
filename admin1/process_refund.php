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
// Include your database connection file
include '../dbconnect.php'; // Ensure this path is correct based on your project structure

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read the JSON data
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Log the received data for debugging
    error_log(print_r($data, true)); // Log incoming data for debugging

    // Check if the data is valid
    if (isset($data['order_id']) && isset($data['ordered_amt']) && isset($data['refund_amount'])) {
        $order_id = mysqli_real_escape_string($con, $data['order_id']);
        $ordered_amt = mysqli_real_escape_string($con, $data['ordered_amt']);
        $refund_amount = mysqli_real_escape_string($con, $data['refund_amount']);
        $date = date('d-m-Y');
        $time = date('H:i');
        
        // Prepare your SQL statement to insert the refund details
        $query = "INSERT INTO refund (order_id, ordered_amt, refund_amt, refund_date) VALUES ('$order_id', '$ordered_amt', '$refund_amount', '$date')";

        // Execute the refund insert query
        if (mysqli_query($con, $query)) {
            // Prepare your SQL statement to insert into the order_sts table
            $query_order_status = "INSERT INTO order_sts (order_id, sts_date, sts_time, status) VALUES ('$order_id', '$date', '$time', 6)";

            // Execute the order status insert query
            if (mysqli_query($con, $query_order_status)) {
                echo json_encode(['success' => true, 'message' => 'Refund initiated successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Database error on order status: ' . mysqli_error($con)]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error on refund: ' . mysqli_error($con)]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid request data.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
