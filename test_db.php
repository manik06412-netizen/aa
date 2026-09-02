<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h2>1. PHP is working! PHP Version: " . phpversion() . "</h2>";

require_once __DIR__ . '/app/config/config.php';

echo "<p>Testing Database Connection to: <strong>" . DB_NAME . "</strong> on <strong>" . DB_HOST . "</strong> with user <strong>" . DB_USER . "</strong>...</p>";

$conn = @mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    echo "<h3 style='color:red;'>❌ Database Connection FAILED!</h3>";
    echo "<p><strong>Error:</strong> " . mysqli_connect_error() . "</p>";
} else {
    echo "<h3 style='color:green;'>✅ Database Connected Successfully!</h3>";
    
    $res = mysqli_query($conn, "SHOW TABLES");
    if ($res) {
        echo "<p>Total Tables in Database: " . mysqli_num_rows($res) . "</p>";
    }
}
