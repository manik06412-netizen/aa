<?php
$salt_key = '7d21378a-e1f5-4873-b2d5-4eefaab99e6b';
$transaction_id = $_GET['transaction_id'];
$amount = $_GET['amount'];
$status = $_GET['status'];
$checksum = $_GET['checksum'];
$expected_checksum = hash_hmac('sha256', $transaction_id . $amount . $status . $salt_key, $salt_key);

if ($checksum === $expected_checksum) {
    if ($status === 'success') {
        echo 'Payment successful!';
       
    } else {
        echo 'Payment failed!';
        
    }
} else {
    echo 'Checksum verification failed!';
    // Handle checksum mismatch
}
?>
