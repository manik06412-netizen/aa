<?php
/**
 * Physical Bridge for Cart - Karuda Computers
 * Ensures cart actions work even if mod_rewrite/.htaccess is disabled on live server
 */
if (!isset($_GET['url'])) {
    $_GET['url'] = 'cart.php';
}
require_once __DIR__ . '/index.php';
