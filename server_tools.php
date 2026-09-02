<?php
// Smart Server Image Uploader & Unpacker
// Upload this file to root: server_tools.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: text/html; charset=utf-8');

$root = __DIR__;
$targetDir = $root . '/avadmin/Res_img/dishes';
$scratchDir = $root . '/scratch';

if (!is_dir($targetDir)) {
    mkdir($targetDir, 0755, true);
}

// Handle ZIP Extract action
$msg = '';
if (isset($_POST['extract_zip'])) {
    $zipFilesRoot = glob($root . '/*.zip') ?: [];
    $zipFilesDishes = glob($targetDir . '/*.zip') ?: [];
    $zipFilesScratch = glob($scratchDir . '/*.zip') ?: [];
    $allZips = array_unique(array_merge($zipFilesRoot, $zipFilesDishes, $zipFilesScratch));
    
    if (empty($allZips)) {
        $msg = "<p style='color:red;font-weight:bold;'>❌ No ZIP file found in root, dishes, or scratch folder!</p>";
    } else {
        $totalExtracted = 0;
        foreach ($allZips as $zipPath) {
            $zip = new ZipArchive;
            if ($zip->open($zipPath) === TRUE) {
                $numFiles = $zip->numFiles;
                $zip->extractTo($targetDir);
                $zip->close();
                $totalExtracted += $numFiles;
                $msg .= "<p style='color:green;font-weight:bold;'>✅ Successfully extracted $numFiles images from " . basename($zipPath) . "!</p>";
            } else {
                $msg .= "<p style='color:red;'>❌ Failed to open ZIP: " . basename($zipPath) . "</p>";
            }
        }
    }
}

// Count current files in targetDir
$currentFiles = is_dir($targetDir) ? array_diff(scandir($targetDir), ['.', '..']) : [];
$imageCount = count(array_filter($currentFiles, fn($f) => preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $f)));

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>AV Herbals - Image Extractor</title>
<style>
body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; padding: 25px; background: #f1f5f9; color: #1e293b; }
.card { background: white; border-radius: 10px; padding: 25px; max-width: 700px; margin: 0 auto; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
h2 { color: #305724; margin-top: 0; }
.btn { background: #305724; color: white; border: none; padding: 14px 28px; border-radius: 6px; font-size: 18px; cursor: pointer; font-weight: bold; }
.btn:hover { background: #23401a; }
.status-box { padding: 15px; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 6px; margin: 20px 0; }
</style>
</head>
<body>
<div class="card">
    <h2>📦 AV Herbals Server Image Extractor</h2>
    
    <div class="status-box">
        <strong>Current Images in <code>avadmin/Res_img/dishes/</code>:</strong>
        <h3 style="margin: 5px 0; color: <?= $imageCount >= 380 ? 'green' : 'red' ?>;"><?= $imageCount ?> of 389 images present</h3>
        <?php if ($imageCount >= 380): ?>
            <p style="color:green; font-weight:bold; font-size: 16px;">🎉 Congratulations! All images are present on server!</p>
            <p><a href="index.php" style="color: #305724; font-weight:bold; font-size: 16px;">👉 Click here to open AV Herbals Home Page</a></p>
        <?php else: ?>
            <p style="color:#d97706; font-weight:bold;">⚠️ Missing <?= (389 - $imageCount) ?> images. Click the Extract button below!</p>
        <?php endif; ?>
    </div>

    <?= $msg ?>

    <?php
    $foundZips = array_merge(glob($root . '/*.zip') ?: [], glob($targetDir . '/*.zip') ?: [], glob($scratchDir . '/*.zip') ?: []);
    if (!empty($foundZips)):
    ?>
    <div style="background:#ecfdf5;border:1px solid #a7f3d0;padding:12px;border-radius:6px;margin-bottom:15px;">
        <strong style="color:#065f46;">Found ZIP files ready to extract:</strong>
        <ul>
            <?php foreach ($foundZips as $zp): ?>
                <li><code><?= basename($zp) ?></code> (in <?= basename(dirname($zp)) ?>/)</li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <form method="post" style="margin-top: 20px;">
        <button type="submit" name="extract_zip" class="btn">🚀 Extract ZIP Now</button>
    </form>
</div>
</body>
</html>
