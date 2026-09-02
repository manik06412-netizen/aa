<?php
/**
 * Newsletter Subscription Endpoint
 * Inserts email into tbl_subscriber so admin can view in admin1/subscriber.php
 */
if (session_status() === PHP_SESSION_NONE) session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/db_config.php';

$email = trim($_POST['email'] ?? '');

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Please provide a valid email address.']);
    exit;
}

$esc_email = mysqli_real_escape_string($con, $email);
$date = date('d-m-Y H:i:s');

// Check if already subscribed
$check = mysqli_query($con, "SELECT subs_id FROM tbl_subscriber WHERE subs_email = '$esc_email'");
if ($check && mysqli_num_rows($check) > 0) {
    echo json_encode(['success' => true, 'message' => 'You are already subscribed to Karuda Computers!']);
    exit;
}

$insert = mysqli_query($con, "INSERT INTO tbl_subscriber (subs_email, dat) VALUES ('$esc_email', '$date')");

if ($insert) {
    echo json_encode(['success' => true, 'message' => 'Thank you for subscribing to Karuda Computers newsletter!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Subscription failed: ' . mysqli_error($con)]);
}
