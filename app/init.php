<?php
/**
 * Karuda Computers - Application Initialization & Autoloader
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load Configuration
require_once __DIR__ . '/config/config.php';

// Class Autoloader with Case-Insensitive Path Resolution for Linux & Windows
spl_autoload_register(function ($className) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/';

    $len = strlen($prefix);
    if (strncmp($prefix, $className, $len) !== 0) {
        return;
    }

    $relativeClass = substr($className, $len);
    $path = str_replace('\\', '/', $relativeClass) . '.php';

    // 1. Direct path check
    if (file_exists($baseDir . $path)) {
        require_once $baseDir . $path;
        return;
    }

    // 2. Lowercase directory check (models/UserModel.php, controllers/HomeController.php, core/Database.php)
    $parts = explode('/', $path);
    if (count($parts) > 1) {
        $parts[0] = strtolower($parts[0]);
        $lowercaseDirFile = $baseDir . implode('/', $parts);
        if (file_exists($lowercaseDirFile)) {
            require_once $lowercaseDirFile;
            return;
        }
    }

    // 3. Fully lowercase check (models/usermodel.php)
    $lowerFile = $baseDir . strtolower($path);
    if (file_exists($lowerFile)) {
        require_once $lowerFile;
        return;
    }
});

// Load Core Helper Functions & Database Bridge
require_once __DIR__ . '/core/Database.php';
$db = \App\Core\Database::getInstance();
$con = $db->getConnection();

require_once __DIR__ . '/core/general.php';
require_once __DIR__ . '/core/convert-price.php';
require_once __DIR__ . '/core/Model.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/App.php';

// Preload Essential Models to ensure availability across all PHP / Linux environments
if (file_exists(__DIR__ . '/models/UserModel.php')) {
    require_once __DIR__ . '/models/UserModel.php';
}
if (file_exists(__DIR__ . '/models/ProductModel.php')) {
    require_once __DIR__ . '/models/ProductModel.php';
}
if (file_exists(__DIR__ . '/models/CategoryModel.php')) {
    require_once __DIR__ . '/models/CategoryModel.php';
}
if (file_exists(__DIR__ . '/models/ContentModel.php')) {
    require_once __DIR__ . '/models/ContentModel.php';
}
if (file_exists(__DIR__ . '/models/CartModel.php')) {
    require_once __DIR__ . '/models/CartModel.php';
}
if (file_exists(__DIR__ . '/models/WishlistModel.php')) {
    require_once __DIR__ . '/models/WishlistModel.php';
}
if (file_exists(__DIR__ . '/models/OrderModel.php')) {
    require_once __DIR__ . '/models/OrderModel.php';
}
if (file_exists(__DIR__ . '/models/AdminModel.php')) {
    require_once __DIR__ . '/models/AdminModel.php';
}
