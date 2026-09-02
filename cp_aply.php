<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
header('Content-Type: application/json');
error_reporting(0);

require_once __DIR__ . '/dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$coupon_code = trim($_POST['coupon_code'] ?? ($_POST['code'] ?? ''));

if (empty($coupon_code)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a coupon code']);
    exit;
}

$coupon_code_safe = mysqli_real_escape_string($con, $coupon_code);

$query = "SELECT * FROM promo WHERE LOWER(TRIM(code)) = LOWER(TRIM('$coupon_code_safe')) LIMIT 1";
$result = mysqli_query($con, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $promo = mysqli_fetch_assoc($result);
    
    // Check if status is deactivated (status '0' is inactive)
    if (isset($promo['status']) && trim($promo['status']) === '0') {
        echo json_encode([
            'success' => false, 
            'message' => 'This coupon code is currently inactive'
        ]);
        exit;
    }

    $discount_percent = floatval($promo['discount'] ?? 0);
    if ($discount_percent <= 0) {
        echo json_encode([
            'success' => false, 
            'message' => 'Invalid discount value for this coupon'
        ]);
        exit;
    }

    $_SESSION['applied_coupon'] = [
        'code' => $promo['code'],
        'discount' => $discount_percent
    ];

    echo json_encode([
        'success' => true,
        'discount' => $discount_percent,
        'code' => $promo['code'],
        'message' => 'Coupon code applied successfully!'
    ]);
    exit;
} else {
    echo json_encode([
        'success' => false, 
        'message' => 'Invalid coupon code. Please check and try again'
    ]);
    exit;
}
