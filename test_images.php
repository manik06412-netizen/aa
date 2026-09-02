<?php
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>AV Herbals - Image Diagnostic Tool</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; padding: 30px; background: #f8fafc; color: #1e293b; }
        .card { background: white; border-radius: 10px; padding: 25px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); max-width: 900px; margin: 0 auto 20px; }
        h1 { color: #0f172a; margin-top: 0; }
        .status-ok { color: #16a34a; font-weight: bold; }
        .status-miss { color: #dc2626; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { text-align: left; padding: 12px; border-bottom: 1px solid #e2e8f0; font-size: 14px; }
        th { background: #f1f5f9; }
        .thumb { max-height: 50px; max-width: 80px; vertical-align: middle; border-radius: 4px; border: 1px solid #cbd5e1; }
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; background: #eff6ff; border-left: 4px solid #3b82f6; }
        .alert-warn { background: #fef3c7; border-left-color: #f59e0b; color: #92400e; }
    </style>
</head>
<body>
<div class="card">
    <h1>🖼️ Server Image Files Diagnostic</h1>
    <p>This tool checks whether the required image files and folders exist on this server.</p>

    <?php
    $baseDir = __DIR__;

    $folders = [
        'avadmin/Res_img' => 'avadmin/Res_img',
        'avadmin/Res_img/dishes' => 'avadmin/Res_img/dishes',
        'avadmin/uploads' => 'avadmin/uploads',
        'admin/uploads' => 'admin/uploads',
        'admin/uploads/logo' => 'admin/uploads/logo',
        'admin/Res_img' => 'admin/Res_img',
        'img' => 'img'
    ];

    echo "<h3>1. Directory Check:</h3><table><tr><th>Folder Path</th><th>Status</th><th>Total Files</th></tr>";
    foreach ($folders as $name => $relPath) {
        $fullPath = $baseDir . '/' . $relPath;
        $exists = is_dir($fullPath);
        $count = $exists ? count(scandir($fullPath)) - 2 : 0;
        echo "<tr>";
        echo "<td><code>$relPath/</code></td>";
        echo "<td>" . ($exists ? "<span class='status-ok'>✅ Exists</span>" : "<span class='status-miss'>❌ Missing Folder</span>") . "</td>";
        echo "<td>" . ($exists ? "$count files" : "-") . "</td>";
        echo "</tr>";
    }
    echo "</table>";

    require_once __DIR__ . '/app/init.php';

    echo "<h3 style='margin-top:30px;'>2. Database Images Check:</h3>";
    echo "<table><tr><th>Type</th><th>DB Stored Path</th><th>Server File Status</th><th>Preview</th></tr>";

    // Logo check
    $qLogo = mysqli_query($con, "SELECT * FROM logo LIMIT 1");
    if ($qLogo && ($rLogo = mysqli_fetch_assoc($qLogo))) {
        $logoRel = 'admin/' . ltrim($rLogo['image'], './');
        $logoFull = $baseDir . '/' . $logoRel;
        $logoExists = file_exists($logoFull);
        echo "<tr>";
        echo "<td><strong>Logo</strong></td>";
        echo "<td><code>$logoRel</code></td>";
        echo "<td>" . ($logoExists ? "<span class='status-ok'>✅ Found on Server</span>" : "<span class='status-miss'>❌ File Not Found!</span>") . "</td>";
        echo "<td>" . ($logoExists ? "<img src='$logoRel' class='thumb'>" : "N/A") . "</td>";
        echo "</tr>";
    }

    // Sliders check
    $qSlide = mysqli_query($con, "SELECT * FROM slider LIMIT 5");
    if ($qSlide) {
        while ($rSlide = mysqli_fetch_assoc($qSlide)) {
            $slideRel = 'avadmin/' . ltrim($rSlide['banner_img'], './');
            $slideFull = $baseDir . '/' . $slideRel;
            $slideExists = file_exists($slideFull);
            echo "<tr>";
            echo "<td>Slider (ID: {$rSlide['id']})</td>";
            echo "<td><code>$slideRel</code></td>";
            echo "<td>" . ($slideExists ? "<span class='status-ok'>✅ Found on Server</span>" : "<span class='status-miss'>❌ File Not Found!</span>") . "</td>";
            echo "<td>" . ($slideExists ? "<img src='$slideRel' class='thumb'>" : "N/A") . "</td>";
            echo "</tr>";
        }
    }

    // Categories check
    $qCat = mysqli_query($con, "SELECT * FROM res_category ORDER BY orderr ASC LIMIT 4");
    if ($qCat) {
        while ($rCat = mysqli_fetch_assoc($qCat)) {
            $catRel = 'avadmin/' . ltrim($rCat['icon'], './');
            $catFull = $baseDir . '/' . $catRel;
            $catExists = file_exists($catFull);
            echo "<tr>";
            echo "<td>Category ({$rCat['c_name']})</td>";
            echo "<td><code>$catRel</code></td>";
            echo "<td>" . ($catExists ? "<span class='status-ok'>✅ Found on Server</span>" : "<span class='status-miss'>❌ File Not Found!</span>") . "</td>";
            echo "<td>" . ($catExists ? "<img src='$catRel' class='thumb'>" : "N/A") . "</td>";
            echo "</tr>";
        }
    }
    echo "</table>";
    ?>

    <div class="alert alert-warn" style="margin-top:25px;">
        <strong>💡 Solution:</strong> If files show as <span class="status-miss">❌ File Not Found</span>, please upload the following folders from your local computer (<code>c:\xampp\htdocs\avherbals_in\</code>) to the server root via cPanel File Manager or FTP:
        <ul>
            <li><code>avadmin/Res_img/</code> (Contains all Slider, Category, Product images)</li>
            <li><code>admin/uploads/</code> (Contains Logo and banners)</li>
        </ul>
    </div>
</div>
</body>
</html>
