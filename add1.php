<?php
/**
 * Physical Bridge for Add to Cart - Karuda Computers
 * Ensures product details add to cart works even if mod_rewrite/.htaccess is disabled on live server
 */
if (!isset($_GET['url'])) {
    $_GET['url'] = 'add1.php';
}
require_once __DIR__ . '/index.php';
