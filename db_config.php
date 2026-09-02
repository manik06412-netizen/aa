<?php
/**
 * ============================================================
 * MASTER DATABASE CONFIGURATION - Karuda Computers
 * ============================================================
 * Central DB config file used by ALL pages:
 *  - Website (include/)
 *  - Admin panel (admin/)
 *  - AV Admin panel (avadmin/)
 *  - admin1/ combined panel
 *
 * Edit DB credentials ONLY in this file.
 * All other dbconnect.php / config.php files include this.
 * ============================================================
 */

if (defined('DB_CONFIG_LOADED'))
    return;
define('DB_CONFIG_LOADED', true);

// ── Database Credentials ───────────────────────────────────
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'av_herbal1');

// ── Timezone & Error Reporting ─────────────────────────────
date_default_timezone_set('Asia/Kolkata');
// ini_set('error_reporting', E_ALL); // Uncomment for development

// ── MySQLi Connection (used by admin, website pages) ───────
if (!isset($con) || !($con instanceof mysqli) || !@mysqli_ping($con)) {
    $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if (!$con) {
        die("Database connection failed: " . mysqli_connect_error());
    }
}
$db = $con; // alias used by some pages

// ── PDO Connection (used by avadmin pages) ─────────────────
if (!isset($pdo) || !($pdo instanceof PDO)) {
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
            DB_USER,
            DB_PASSWORD
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("PDO Connection error: " . $e->getMessage());
    }
}

// ── Base URL Definitions ───────────────────────────────────
if (!defined('BASE_URL')) {
    define('BASE_URL', '');
}
if (!defined('ADMIN_URL')) {
    define('ADMIN_URL', BASE_URL . 'admin1/');
}
if (!defined('AVADMIN_URL')) {
    define('AVADMIN_URL', BASE_URL . 'admin1/');
}
if (!defined('ADMIN1_URL')) {
    define('ADMIN1_URL', BASE_URL . 'admin1/');
}

// ── Universal Image Resolver ───────────────────────────────
if (!function_exists('resolve_image_url')) {
    function resolve_image_url($path)
    {
        if (empty($path)) {
            return (defined('BASE_URL') ? BASE_URL : '') . 'img/alter_img.jpg';
        }
        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }
        $clean = ltrim($path, './');
        $base = defined('BASE_URL') ? BASE_URL : '';
        $doc_root = __DIR__;

        // Priority 1: admin1/{clean}
        if (file_exists($doc_root . '/admin1/' . $clean)) {
            return $base . 'admin1/' . $clean;
        }
        // Priority 2: avadmin/{clean}
        if (file_exists($doc_root . '/avadmin/' . $clean)) {
            return $base . 'avadmin/' . $clean;
        }
        // Priority 3: admin/{clean}
        if (file_exists($doc_root . '/admin/' . $clean)) {
            return $base . 'admin/' . $clean;
        }
        // Priority 4: root/{clean}
        if (file_exists($doc_root . '/' . $clean)) {
            return $base . $clean;
        }

        return $base . 'admin1/' . $clean;
    }
}
