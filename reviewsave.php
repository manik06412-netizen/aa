<?php
/**
 * reviewsave.php - Product Review Submission Handler
 */
error_reporting(0);
ini_set('display_errors', '0');
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/db_config.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

// Ensure cust_reviews table exists
$table_check = mysqli_query($con, "SHOW TABLES LIKE 'cust_reviews'");
if (!$table_check || mysqli_num_rows($table_check) === 0) {
    $create_table = "CREATE TABLE IF NOT EXISTS cust_reviews (
        uid INT AUTO_INCREMENT PRIMARY KEY,
        cid INT NOT NULL,
        uname VARCHAR(255) NOT NULL,
        uemail VARCHAR(255) NOT NULL,
        ureview TEXT NOT NULL,
        uratings INT DEFAULT 5,
        createdat TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    mysqli_query($con, $create_table);
}

// Extract & sanitize parameters
$cid = isset($_POST['cid']) ? (int)$_POST['cid'] : (isset($_SESSION['did']) ? (int)$_SESSION['did'] : 0);
$uname = isset($_POST['uname']) ? trim(mysqli_real_escape_string($con, $_POST['uname'])) : '';
$uemail = isset($_POST['uemail']) ? trim(mysqli_real_escape_string($con, $_POST['uemail'])) : '';
$ureview = isset($_POST['ureview']) ? trim(mysqli_real_escape_string($con, $_POST['ureview'])) : '';
$urating = isset($_POST['urating']) ? (int)$_POST['urating'] : (isset($_POST['ratings']) ? (int)$_POST['ratings'] : 5);

if ($urating < 1) $urating = 5;
if ($urating > 5) $urating = 5;

if (empty($uname)) {
    echo json_encode(['success' => false, 'message' => 'Please enter your full name.']);
    exit;
}

if (empty($uemail) || !filter_var($_POST['uemail'], FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
    exit;
}

if (empty($ureview)) {
    echo json_encode(['success' => false, 'message' => 'Please write your review.']);
    exit;
}

// Check column name in cust_reviews (uratings vs urating)
$col_check = mysqli_query($con, "SHOW COLUMNS FROM cust_reviews LIKE 'uratings'");
$rating_col = ($col_check && mysqli_num_rows($col_check) > 0) ? 'uratings' : 'urating';

$insert_sql = "INSERT INTO cust_reviews (cid, uname, uemail, ureview, $rating_col, createdat) 
               VALUES ('$cid', '$uname', '$uemail', '$ureview', '$urating', NOW())";

if (mysqli_query($con, $insert_sql)) {
    echo json_encode([
        'success' => true,
        'message' => 'Thank you! Your review has been submitted successfully.'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . mysqli_error($con)
    ]);
}
exit;
