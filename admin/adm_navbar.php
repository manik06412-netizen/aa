<?php
/**
 * admin1/adm_navbar.php - Wrapper for admin/navbar.php
 * Session bridge: admin1 session -> admin compatible session
 */
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/inc/config.php';

if (!isset($_SESSION['admin1_user'])) {
    header('Location: login.php');
    exit;
}

// Bridge admin session compatibility
if (!isset($_SESSION['adm_id'])) {
    $_SESSION['adm_id'] = $_SESSION['admin1_user']['id'];
}

if (file_exists(__DIR__ . '/../admin/navbar.php')) {
    include __DIR__ . '/../admin/navbar.php';
} else {
    echo '<p style="padding:20px;color:red;">Not found: admin/navbar.php</p>';
}
