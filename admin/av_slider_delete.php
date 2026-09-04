<?php
/**
 * admin1/av_slider_delete.php - Wrapper for avadmin/slider_delete.php
 * Session bridge: admin1 session -> avadmin compatible session
 */
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/inc/config.php';

if (!isset($_SESSION['admin1_user'])) {
    header('Location: login.php');
    exit;
}

// Bridge avadmin session compatibility
if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = [
        'id'        => $_SESSION['admin1_user']['id'],
        'full_name' => $_SESSION['admin1_user']['full_name'],
        'email'     => $_SESSION['admin1_user']['email'],
        'photo'     => $_SESSION['admin1_user']['photo'],
    ];
}

if (file_exists(__DIR__ . '/../avadmin/slider_delete.php')) {
    include __DIR__ . '/../avadmin/slider_delete.php';
} else {
    echo '<p style="padding:20px;color:red;">Not found: avadmin/slider_delete.php</p>';
}
