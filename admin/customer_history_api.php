<?php
/**
 * admin1/customer_history_api.php
 * AJAX: Returns a customer's full order history
 */
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/inc/config.php';
header('Content-Type: application/json');
error_reporting(0);

if (!isset($_SESSION['admin1_user'])) {
    echo json_encode(['success'=>false,'message'=>'Unauthorized']); exit;
}

$user_id = intval($_GET['user_id'] ?? 0);
if (!$user_id) {
    echo json_encode(['success'=>false,'message'=>'Invalid user_id']); exit;
}

// Get customer info
$cust = null;
try {
    $st = $pdo->prepare("SELECT fname, email, mobile FROM user WHERE user_id = ?");
    $st->execute([$user_id]);
    $cust = $st->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {}

if (!$cust) {
    echo json_encode(['success'=>false,'message'=>'Customer not found']); exit;
}

// Get orders
$orders = [];
try {
    $st = $pdo->prepare("
        SELECT 
            o.id,
            o.order_id,
            o.order_date,
            o.status,
            o.total_amount,
            o.address,
            COUNT(f.id) AS item_count
        FROM tbl_order o
        LEFT JOIN final f ON f.order_id = o.order_id
        WHERE o.user_id = ?
        GROUP BY o.id
        ORDER BY o.id DESC
        LIMIT 30
    ");
    $st->execute([$user_id]);
    $orders = $st->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {}

// Stats
$total_orders  = count($orders);
$total_spend   = array_sum(array_column($orders, 'total_amount'));
$delivered     = count(array_filter($orders, fn($o) => $o['status'] === 'Delivered'));
$pending       = count(array_filter($orders, fn($o) => $o['status'] === 'Pending'));

echo json_encode([
    'success'       => true,
    'customer'      => $cust,
    'stats'         => [
        'total_orders'  => $total_orders,
        'total_spend'   => round($total_spend, 2),
        'delivered'     => $delivered,
        'pending'       => $pending,
    ],
    'orders'        => $orders,
]);
