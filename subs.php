<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
error_reporting(0);
require_once __DIR__ . '/dbconnect.php';

$is_ajax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') 
    || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)
    || isset($_POST['ajax']);

$email = trim($_POST['subs'] ?? ($_POST['email'] ?? ''));

$response = [
    'status' => 0,
    'message' => 'Please enter a valid email address.'
];

if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $email_safe = mysqli_real_escape_string($con, $email);
    
    // Check if already subscribed
    $check = mysqli_query($con, "SELECT subs_id FROM tbl_subscriber WHERE LOWER(TRIM(subs_email)) = LOWER(TRIM('$email_safe')) LIMIT 1");
    if ($check && mysqli_num_rows($check) > 0) {
        $response = [
            'status' => 1,
            'message' => 'You are already subscribed to our newsletter!'
        ];
    } else {
        $date = date('d/m/Y');
        $ins = mysqli_query($con, "INSERT INTO tbl_subscriber (subs_email, dat) VALUES ('$email_safe', '$date')");
        if ($ins) {
            $response = [
                'status' => 1,
                'message' => 'Thank you for subscribing to our newsletter!'
            ];
        } else {
            $response = [
                'status' => 0,
                'message' => 'Could not save subscription: ' . mysqli_error($con)
            ];
        }
    }
}

if ($is_ajax) {
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
} else {
    $_SESSION['newsletter_msg'] = $response['message'];
    $redirect_url = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
    echo "<!DOCTYPE html><html><head><meta http-equiv='refresh' content='0;url=" . htmlspecialchars($redirect_url) . "'></head><body><script>window.location.href = '" . addslashes($redirect_url) . "';</script></body></html>";
    exit;
}
