<?php
// QUICK SERVER VERIFY - Upload to server root and visit URL
// Delete after use!
error_reporting(E_ALL);
header('Content-Type: text/html; charset=utf-8');

$root = __DIR__;
$base = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';

// Load DB from config
$configSrc = file_get_contents($root . '/app/config/config.php');
preg_match("/define\('DB_HOST',\s*'([^']*)'\)/", $configSrc, $mh);
preg_match("/define\('DB_USER',\s*'([^']*)'\)/", $configSrc, $mu);
preg_match("/define\('DB_PASS',\s*'([^']*)'\)/", $configSrc, $mp);
preg_match("/define\('DB_NAME',\s*'([^']*)'\)/", $configSrc, $mn);
$con = mysqli_connect($mh[1]??'localhost', $mu[1]??'root', $mp[1]??'', $mn[1]??'');

echo "<pre style='font-family:monospace;font-size:13px;padding:20px;background:#1e1e1e;color:#d4d4d4'>";
echo "=== SERVER VERIFY (after upload) ===\n\n";

// 1. Count files in dishes folder
$dishesDir = $root . '/avadmin/Res_img/dishes';
if (is_dir($dishesDir)) {
    $files = array_diff(scandir($dishesDir), ['.','..']);
    $imgFiles = array_filter($files, fn($f) => preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $f));
    echo "avadmin/Res_img/dishes/ : " . count($imgFiles) . " images\n";
    echo "First 5: " . implode(', ', array_slice(array_values($imgFiles), 0, 5)) . "\n\n";
} else {
    echo "avadmin/Res_img/dishes/ : FOLDER MISSING!\n\n";
}

// 2. Test specific slider images
$r = mysqli_query($con, "SELECT * FROM slider LIMIT 5");
echo "=== SLIDER IMAGE CHECK ===\n";
while ($row = mysqli_fetch_assoc($r)) {
    $clean = preg_replace('#/+#', '/', ltrim($row['banner_img'], './'));
    $fullPath = $root . '/avadmin/' . $clean;
    $url = $base . 'avadmin/' . $clean;
    $status = file_exists($fullPath) ? "✅ EXISTS" : "❌ NOT FOUND";
    echo "ID:{$row['id']} | DB: {$row['banner_img']}\n";
    echo "         Path: $fullPath\n";
    echo "         URL:  $url\n";
    echo "         File: $status\n\n";
}

// 3. Test specific category images
$r = mysqli_query($con, "SELECT * FROM res_category LIMIT 4");
echo "=== CATEGORY ICON CHECK ===\n";
while ($row = mysqli_fetch_assoc($r)) {
    $clean = preg_replace('#/+#', '/', ltrim($row['icon'], './'));
    $fullPath = $root . '/avadmin/' . $clean;
    $url = $base . 'avadmin/' . $clean;
    $status = file_exists($fullPath) ? "✅ EXISTS" : "❌ NOT FOUND";
    echo "Cat: {$row['c_name']} | {$row['icon']}\n";
    echo "     URL: $url\n";
    echo "     File: $status\n\n";
}

// 4. Check if resolve_image_url function will work
echo "=== FUNCTION CHECK ===\n";
if (file_exists($root . '/app/core/general.php')) {
    echo "app/core/general.php: EXISTS ✅\n";
} else {
    echo "app/core/general.php: MISSING ❌\n";
}

// 5. Direct URL test for a known image
echo "\n=== DIRECT HTTP CHECK ===\n";
$testUrls = [
    $base . 'avadmin/Res_img/dishes/66d826375b80a.jpg',
    $base . 'avadmin/Res_img/dishes/66d04af63224c.png',
];
foreach ($testUrls as $url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $icon = ($code == 200) ? "✅ HTTP 200 OK" : "❌ HTTP $code";
    echo basename($url) . " -> $icon\n";
}
echo "</pre>";

// 6. Visual test
echo "<hr><h3>Visual Test (should show images):</h3>";
$r2 = mysqli_query($con, "SELECT * FROM res_category LIMIT 3");
while ($row2 = mysqli_fetch_assoc($r2)) {
    $clean = preg_replace('#/+#', '/', ltrim($row2['icon'], './'));
    $url = $base . 'avadmin/' . $clean;
    echo "<img src='$url' style='max-height:80px;max-width:120px;margin:5px;border:2px solid #ccc' onerror=\"this.style.border='2px solid red';this.alt='BROKEN: $url'\" alt='{$row2['c_name']}'>";
}
$r3 = mysqli_query($con, "SELECT * FROM slider LIMIT 2");
while ($row3 = mysqli_fetch_assoc($r3)) {
    $clean = preg_replace('#/+#', '/', ltrim($row3['banner_img'], './'));
    $url = $base . 'avadmin/' . $clean;
    echo "<img src='$url' style='max-height:100px;max-width:200px;margin:5px;border:2px solid #ccc' onerror=\"this.style.border='2px solid red';this.alt='BROKEN'\" alt='slider'>";
}
echo "<p style='color:red;font-weight:bold'>⚠ DELETE THIS FILE FROM SERVER AFTER USE!</p>";
