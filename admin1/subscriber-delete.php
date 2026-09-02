<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/inc/config.php';
if (!isset($_SESSION["admin1_user"])) {
    header("Location: login.php");
    exit;
}
error_reporting(0);

if (!isset($_REQUEST['id'])) {
    header('Location: subscriber.php');
    exit;
}

$id = intval($_REQUEST['id']);
$statement = $pdo->prepare("DELETE FROM tbl_subscriber WHERE subs_id = ?");
$statement->execute([$id]);

header('Location: subscriber.php');
exit;