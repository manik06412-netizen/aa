<?php
$merchant_id = 'M12K7SQGWIS6';
$salt_key = '7d21378a-e1f5-4873-b2d5-4eefaab99e6b';
$amount = $_POST['amount'];
$transaction_id = uniqid();
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$return_url = $protocol . '://' . $host . '/karudacom/p4/return.php';

// Prepare data to send to PhonePe
$data = [
    'merchant_id' => $merchant_id,
    'transaction_id' => $transaction_id,
    'amount' => $amount,
    'return_url' => $return_url,
    'salt_key' => $salt_key,
    'checksum' => hash_hmac('sha256', $transaction_id . $amount . $salt_key, $salt_key),
];

// Prepare URL for PhonePe API endpoint
$phonepe_url = 'https://secure.phonepe.com/pay'; // Replace with actual PhonePe endpoint

// Initialize cURL
$ch = curl_init($phonepe_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

// Set cURL options for debugging
curl_setopt($ch, CURLOPT_VERBOSE, true);
$verbose = fopen('php://temp', 'w+');
curl_setopt($ch, CURLOPT_STDERR, $verbose);

// Execute cURL request
$response = curl_exec($ch);

// Check for cURL errors
if ($response === false) {
    $error_msg = curl_error($ch);
    echo 'cURL Error: ' . $error_msg;
    rewind($verbose);
    $verbose_log = stream_get_contents($verbose);
    echo "Verbose information:\n", htmlspecialchars($verbose_log);
} else {
    // Check if response is successful
    header('Location: ' . $response);
    exit();
}

// Close cURL
curl_close($ch);
?>
