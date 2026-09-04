<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/inc/config.php';
if (!isset($_SESSION["admin1_user"])) {
    header("Location: login.php");
    exit;
}
error_reporting(0);

if (isset($_GET['cat_del'])) {
    $id = intval($_GET['cat_del']);
    mysqli_query($con, "DELETE FROM banner WHERE id = '$id'");
}

header("Location: banner.php");
exit;
