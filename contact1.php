<?php
/**
 * Direct Contact Form Submission Endpoint
 * Dispatches to MVC PageController::contactSubmit
 */
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/app/init.php';

$controller = new \App\Controllers\PageController();
$controller->contactSubmit();
