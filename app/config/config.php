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
    
    // Check if running in a subfolder like /karudaCom/ on localhost
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

// ============================================================================
// 🎨 BRAND COLOR THEME
// ============================================================================
define('COLOR_PRIMARY', '#003B95');
define('COLOR_PRIMARY_DARK', '#002566');

// ============================================================================
// 📧 SMTP EMAIL & PASSKEY CONFIGURATION (Gmail / Custom SMTP)
// ============================================================================
// Ungaloda Mail ID matrum Google App Password (Passkey) inge add pannunga:
// 1. Google Account -> Security -> 2-Step Verification ON panni "App Passwords" create pannunga.
// 2. SMTP_USER-la unga Mail ID, SMTP_PASS-la antha 16-digit passkey-ah enter pannunga:
if (!defined('SMTP_HOST'))      define('SMTP_HOST', 'smtp.gmail.com');
if (!defined('SMTP_PORT'))      define('SMTP_PORT', 587); // 587 (TLS) or 465 (SSL)
if (!defined('SMTP_USER'))      define('SMTP_USER', 'ukinfotechpdk@gmail.com'); // <-- INGE UNGA MAIL ID (e.g. 'karudacomputers@gmail.com')
if (!defined('SMTP_PASS'))      define('SMTP_PASS', 'ghgp aepd vfzw mxok'); // <-- INGE UNGA 16-DIGIT PASSKEY (e.g. 'abcd efgh ijkl mnop')
if (!defined('SMTP_SECURE'))    define('SMTP_SECURE', 'tls'); // 'tls' or 'ssl'
if (!defined('SMTP_FROM_NAME')) define('SMTP_FROM_NAME', 'Karuda Computers');

