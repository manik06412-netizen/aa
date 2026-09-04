<?php
/**
 * admin1/low_stock_alert.php
 * AJAX endpoint — returns low stock products (qty <= threshold)
 * Called by dashboard & stock page widgets
 */
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/inc/config.php';

if (!isset($_SESSION['admin1_user'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

header('Content-Type: application/json');
error_reporting(0);

$threshold = max(1, (int)($_GET['threshold'] ?? 10));

$items = [];

// Query dishes & price table for low stock
$sql = "SELECT 
            d.d_id AS id,
            d.dish_name AS product_name,
            d.category,
            COALESCE(p.total_stock, 0) AS current_stock,
            d.status
        FROM dishes d
        LEFT JOIN price p ON p.pcode = d.rs_id
        WHERE COALESCE(p.total_stock, 0) <= $threshold
        ORDER BY COALESCE(p.total_stock, 0) ASC
        LIMIT 20";

$result = mysqli_query($con, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $items[] = [
            'id'            => (int)$row['id'],
            'product_name'  => $row['product_name'],
            'category'      => $row['category'] ?? '—',
            'stock'         => (int)$row['current_stock'],
            'status'        => ($row['status'] == 1 ? 'active' : 'inactive'),
            'level'         => (int)$row['current_stock'] === 0 ? 'out' : 'low',
        ];
    }
}

echo json_encode([
    'success'   => true,
    'threshold' => $threshold,
    'count'     => count($items),
    'items'     => $items,
]);
