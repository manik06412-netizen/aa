<?php
/**
 * Karuda Computers - Front Controller & Application Entry Point
 * Architecture: Model-View-Controller (MVC)
 */

// Display Errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Initialize Application & Autoloader
require_once __DIR__ . '/app/init.php';

// Dispatch MVC Request
$app = new \App\Core\App();
?>