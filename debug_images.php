<?php
// ============================================================
// AV HERBALS - SERVER IMAGE PATH DIAGNOSTIC (safe, read-only)
// Upload this to server root and visit: https://avherbals.ukinfotech.co.in/debug_images.php
// DELETE after use!
// ============================================================
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>AV Herbals - Image Debug</title>
<style>
body{font-family:monospace;padding:20px;background:#f9fafb;color:#111}
.card{background:#fff;border-radius:8px;padding:20px;box-shadow:0 2px 6px rgba(0,0,0,.1);max-width:1100px;margin:0 auto 20px}
h2{margin-top:0;color:#305724}
table{width:100%;border-collapse:collapse;font-size:13px}
th,td{padding:8px 10px;border:1px solid #e5e7eb;text-align:left;word-break:break-all}
th{background:#f3f4f6;font-weight:bold}
.ok{color:#16a34a;font-weight:bold}
.fail{color:#dc2626;font-weight:bold}
.warn{color:#d97706;font-weight:bold}
img.thumb{max-height:48px;max-width:90px;border:1px solid #ccc;border-radius:4px}
.section-title{background:#305724;color:#fff;padding:8px 15px;border-radius:6px;font-size:15px;margin:20px 0 8px}
</style>
</head>
<body>
<div class="card">
<h2>🔍 AV Herbals Server Image Diagnostic</h2>

<?php
$root = __DIR__;  // Server root for avherbals
$base = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http')
        . '://' . $_SERVER['HTTP_HOST'] . '/';

echo "<p><strong>Server Root (PHP __DIR__):</strong> <code>$root</code></p>";
echo "<p><strong>Base URL (auto-detected):</strong> <code>$base</code></p>";

// Key folders
$folders = [
    'avadmin',
    'avadmin/Res_img',
    'avadmin/Res_img/dishes',
    'avadmin/uploads',
    'avadmin/uploads/banner',
    'admin',
    'admin/uploads',
    'admin/uploads/logo',
];

echo '<div class="section-title">1. Folder Existence Check</div>';
echo '<table><tr><th>Folder</th><th>Exists?</th><th>File Count</th><th>Sample Files</th></tr>';
foreach ($folders as $f) {
    $full = $root . '/' . $f;
    $exists = is_dir($full);
    $count = 0;
    $samples = '';
    if ($exists) {
        $files = array_diff(scandir($full), ['.', '..']);
        $imageFiles = array_filter($files, fn($fn) => preg_match('/\.(jpg|jpeg|png|gif|webp|svg)$/i', $fn));
        $count = count($imageFiles);
        $sampleArr = array_slice(array_values($imageFiles), 0, 3);
        $samples = implode(', ', $sampleArr);
    }
    $icon = $exists ? '<span class="ok">✅ YES</span>' : '<span class="fail">❌ NO (MISSING)</span>';
    echo "<tr><td><code>$f/</code></td><td>$icon</td><td>$count images</td><td><small>$samples</small></td></tr>";
}
echo '</table>';

// DB Connection
$dbHost = 'localhost';
$dbUser = 'xxxxxxxx'; // will be filled from config
$dbPass = 'xxxxxxxx';
$dbName = 'xxxxxxxx';

// Try to load from app config
$configFile = $root . '/app/config/config.php';
if (file_exists($configFile)) {
    // Extract defines without running init
    $configSrc = file_get_contents($configFile);
    preg_match("/define\('DB_HOST',\s*'([^']*)'\)/", $configSrc, $mh);
    preg_match("/define\('DB_USER',\s*'([^']*)'\)/", $configSrc, $mu);
    preg_match("/define\('DB_PASS',\s*'([^']*)'\)/", $configSrc, $mp);
    preg_match("/define\('DB_NAME',\s*'([^']*)'\)/", $configSrc, $mn);
    $dbHost = $mh[1] ?? 'localhost';
    $dbUser = $mu[1] ?? 'root';
    $dbPass = $mp[1] ?? '';
    $dbName = $mn[1] ?? '';
}

$con = @mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
if (!$con) {
    echo '<div class="section-title" style="background:#dc2626">❌ DB Connection Failed</div>';
    echo '<p class="fail">Error: ' . mysqli_connect_error() . '</p>';
    echo '</div></body></html>';
    exit;
}

echo '<p class="ok" style="margin-top:15px">✅ DB Connected to: <strong>' . $dbName . '@' . $dbHost . '</strong></p>';

// ---- IMAGE URL RESOLVER (standalone) ----
function resolveForDebug($dbPath, $root, $base) {
    if (empty($dbPath)) return ['url' => $base . 'img/alter_img.jpg', 'exists' => null, 'clean' => ''];
    $clean = preg_replace('#/+#', '/', ltrim(trim($dbPath), './'));
    $candidates = [
        'avadmin/' . $clean,
        'admin/' . $clean,
        'avadmin/Res_img/dishes/' . basename($clean),
        'avadmin/Res_img/' . basename($clean),
        'avadmin/res_img/dishes/' . basename($clean),
        'avadmin/res_img/' . basename($clean),
        'admin/Res_img/dishes/' . basename($clean),
        'admin/uploads/' . basename($clean),
        'admin/uploads/logo/' . basename($clean),
        'img/' . basename($clean),
        $clean,
    ];
    foreach ($candidates as $rel) {
        $full = $root . '/' . $rel;
        if (file_exists($full) && !is_dir($full)) {
            return ['url' => $base . $rel, 'exists' => true, 'clean' => $rel];
        }
    }
    // Fallback (first guess)
    $guessRel = 'avadmin/' . $clean;
    return ['url' => $base . $guessRel, 'exists' => false, 'clean' => $guessRel];
}

// --- LOGO ---
echo '<div class="section-title">2. Logo</div>';
$r = mysqli_query($con, "SELECT * FROM logo LIMIT 1");
if ($r && ($row = mysqli_fetch_assoc($r))) {
    $info = resolveForDebug($row['image'], $root, $base);
    $status = $info['exists'] ? '<span class="ok">✅ File Found</span>' : '<span class="fail">❌ File NOT Found on Server</span>';
    echo "<table><tr><th>DB Value</th><th>Cleaned Path</th><th>Full URL</th><th>Server Status</th><th>Preview</th></tr>";
    echo "<tr><td><code>{$row['image']}</code></td><td><code>{$info['clean']}</code></td><td><a href='{$info['url']}' target='_blank'>{$info['url']}</a></td><td>$status</td><td><img src='{$info['url']}' class='thumb' onerror=\"this.src='';this.alt='BROKEN'\"></td></tr>";
    echo "</table>";
}

// --- SLIDER / BANNER ---
echo '<div class="section-title">3. Slider / Banner Images</div>';
$r = mysqli_query($con, "SELECT * FROM slider");
if ($r) {
    echo "<table><tr><th>ID</th><th>DB Value</th><th>Cleaned Path</th><th>Full URL</th><th>Server Status</th><th>Preview</th></tr>";
    while ($row = mysqli_fetch_assoc($r)) {
        $info = resolveForDebug($row['banner_img'], $root, $base);
        $status = $info['exists'] ? '<span class="ok">✅ Found</span>' : '<span class="fail">❌ Missing</span>';
        echo "<tr><td>{$row['id']}</td><td><code>{$row['banner_img']}</code></td><td><code>{$info['clean']}</code></td><td><a href='{$info['url']}' target='_blank' style='font-size:11px'>{$info['url']}</a></td><td>$status</td><td><img src='{$info['url']}' class='thumb' onerror=\"this.src='';this.alt='BROKEN'\"></td></tr>";
    }
    echo "</table>";
}

// --- CATEGORIES ---
echo '<div class="section-title">4. Category Icons</div>';
$r = mysqli_query($con, "SELECT * FROM res_category ORDER BY orderr ASC LIMIT 10");
if ($r) {
    echo "<table><tr><th>ID</th><th>Name</th><th>DB Value</th><th>Cleaned Path</th><th>Server Status</th><th>Preview</th></tr>";
    while ($row = mysqli_fetch_assoc($r)) {
        $info = resolveForDebug($row['icon'], $root, $base);
        $status = $info['exists'] ? '<span class="ok">✅ Found</span>' : '<span class="fail">❌ Missing</span>';
        echo "<tr><td>{$row['c_id']}</td><td>{$row['c_name']}</td><td><code>{$row['icon']}</code></td><td><code>{$info['clean']}</code></td><td>$status</td><td><img src='{$info['url']}' class='thumb' onerror=\"this.src='';this.alt='BROKEN'\"></td></tr>";
    }
    echo "</table>";
}

// --- PRODUCTS ---
echo '<div class="section-title">5. Product Images (first 10)</div>';
$r = mysqli_query($con, "SELECT rs_id, dish_name, img FROM dishes WHERE status='1' ORDER BY rs_id DESC LIMIT 10");
if ($r) {
    echo "<table><tr><th>ID</th><th>Product</th><th>DB img value</th><th>Cleaned Path</th><th>Server Status</th><th>Preview</th></tr>";
    while ($row = mysqli_fetch_assoc($r)) {
        $info = resolveForDebug($row['img'], $root, $base);
        $status = $info['exists'] ? '<span class="ok">✅ Found</span>' : '<span class="fail">❌ Missing</span>';
        echo "<tr><td>{$row['rs_id']}</td><td>{$row['dish_name']}</td><td><code>{$row['img']}</code></td><td><code>{$info['clean']}</code></td><td>$status</td><td><img src='{$info['url']}' class='thumb' onerror=\"this.src='';this.alt='BROKEN'\"></td></tr>";
    }
    echo "</table>";
}

// --- FINAL SUMMARY ---
echo '<div class="section-title">6. Summary & Recommended Fix</div>';
echo '<div style="padding:15px;background:#eff6ff;border-left:4px solid #3b82f6;border-radius:4px">';
echo '<strong>Why images fail on server but work locally:</strong><br><br>';
echo '1. <code>file_exists()</code> is used in PHP to find images — on localhost your <code>avadmin/Res_img/dishes/</code> folder has the files. But on the server, if those files were NOT uploaded (git push skips binary assets), <code>file_exists()</code> returns FALSE for all candidates → the fallback URL is used but the file still doesn\'t exist on disk.<br><br>';
echo '2. The real fix is: <strong>Upload the entire <code>avadmin/Res_img/</code> folder and <code>admin/uploads/</code> folder</strong> from your local XAMPP to the server via cPanel File Manager or FTP.<br><br>';
echo '3. After upload, ALL images will resolve correctly — no code change needed.<br><br>';
echo '<strong style="color:#dc2626">⚠ This debug file should be DELETED from the server after analysis!</strong>';
echo '</div>';
?>
</div>
</body>
</html>
