<?php
/**
 * admin1/bulk_product_action.php
 * AJAX: Bulk activate / deactivate / delete selected products
 */
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/inc/config.php';
header('Content-Type: application/json');
error_reporting(0);

if (!isset($_SESSION['admin1_user'])) {
    echo json_encode(['success'=>false,'message'=>'Unauthorized']); exit;
}

$action = $_POST['action'] ?? '';
$ids_raw = $_POST['ids'] ?? '';
$ids = array_filter(array_map('intval', explode(',', $ids_raw)));

if (empty($ids) || !in_array($action, ['activate','deactivate','delete'])) {
    echo json_encode(['success'=>false,'message'=>'Invalid request']); exit;
}

$placeholders = implode(',', array_fill(0, count($ids), '?'));
$affected = 0;

try {
    if ($action === 'activate') {
        $stmt = $con->prepare("UPDATE dishes SET status = 1 WHERE d_id IN ($placeholders)");
        $stmt->bind_param(str_repeat('i', count($ids)), ...$ids);
        $stmt->execute();
        $affected = $stmt->affected_rows;
        $msg = "$affected product(s) activated successfully.";
    } elseif ($action === 'deactivate') {
        $stmt = $con->prepare("UPDATE dishes SET status = 2 WHERE d_id IN ($placeholders)");
        $stmt->bind_param(str_repeat('i', count($ids)), ...$ids);
        $stmt->execute();
        $affected = $stmt->affected_rows;
        $msg = "$affected product(s) deactivated successfully.";
    } elseif ($action === 'delete') {
        $stmt = $con->prepare("DELETE FROM dishes WHERE d_id IN ($placeholders)");
        $stmt->bind_param(str_repeat('i', count($ids)), ...$ids);
        $stmt->execute();
        $affected = $stmt->affected_rows;
        $msg = "$affected product(s) deleted.";
    }
    echo json_encode(['success'=>true,'message'=>$msg,'affected'=>$affected]);
} catch (Exception $e) {
    echo json_encode(['success'=>false,'message'=>'DB error: '.$e->getMessage()]);
}
