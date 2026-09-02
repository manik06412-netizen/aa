<?php
/**
 * Karuda Computers - Core Application Configuration
 */

require_once dirname(__DIR__, 2) . '/db_config.php';
if (!defined('DB_PASS') && defined('DB_PASSWORD')) {
    define('DB_PASS', DB_PASSWORD);
}

// Auto-detect BASE_URL dynamically for both Localhost & Live Server
if (!defined('BASE_URL')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    
    // Check if running in a subfolder like /avherbals_in/ on localhost
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    $subDir = trim($scriptDir, '/');
    
    if (!empty($subDir)) {
        $baseUrl = $protocol . $host . '/' . $subDir . '/';
    } else {
        $baseUrl = $protocol . $host . '/';
    }
    define('BASE_URL', $baseUrl);
}

define('APP_ROOT', dirname(__DIR__));
define('SITE_NAME', 'Karuda Computers');
define('DEFAULT_CURRENCY', '₹');
