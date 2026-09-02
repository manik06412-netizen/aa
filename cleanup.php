<?php
/**
 * KARUDA - Unwanted Files Cleanup Script
 * URL: http://localhost/karuda/aa/cleanup.php
 * 
 * Lists and removes:
 * - Debug/test PHP files (debug_images.php, test_*.php, verify_upload.php etc.)
 * - Temp/scratch folders
 * - Loose root-level files that are not needed
 * - Duplicate "admin" folder that duplicates admin1/ (admin/ vs admin1/)
 * 
 * DELETE THIS FILE AFTER USE.
 */

define('CLEANUP_TOKEN', 'karuda_cleanup2026');
$token_ok = isset($_GET['token']) && $_GET['token'] === CLEANUP_TOKEN;

$base = __DIR__;

// ── Files to remove from ROOT (aa/) ──────────────────────────────────────────
// These are debug, test, scratch, or clearly unwanted files at the project root.
$root_files_to_delete = [
    'debug_images.php',       // debug file - should not exist in production
    'test_db.php',            // test file - not needed in production
    'test_images.php',        // test file - not needed in production
    'verify_upload.php',      // test/debug file
    'edit1.php',              // unclear scratch file
    'cp_aply.php',            // seems unused / coupon apply scratch
    'style.css',              // 8 bytes, empty style file at root
    'reviews.avif',           // stray image at root
    'vector.webp',            // stray image at root
    'icons8-non-veg-48.png',  // stray icon at root
    'icons8-veg-48.png',      // stray icon at root
    'ovi-logo.png',           // stray image at root
    // seed_runner and cleanup are deleted manually after use
];

// ── Folders to remove from ROOT (aa/) ────────────────────────────────────────
// These are clearly duplicate, stray, or leftover folders.
$root_dirs_to_delete = [
    // 'admin',    // Uncomment ONLY if you are fully switching to admin1/ panel
    // 'exe',      // Old payment folder (p4/ is newer) — uncomment to delete
];

// ── Files to remove from admin1/ ─────────────────────────────────────────────
$admin1_files_to_delete = [
    'test_runner.php',
    'test_suite.php',
    'reviewsave.php',         // 53 bytes - stub file
    'barcode_upd.php',        // duplicate of av_barcode_upd.php
    // stub redirect files (these just redirect to their av_ counterparts)
    // Uncomment below if you want to remove them - they are just wrappers
];

// ── Temp folders to clean (contents only) ────────────────────────────────────
$temp_folders_to_empty = [
    $base . '/admin/temp',
    $base . '/admin1/temp',
    $base . '/avadmin/temp',
    $base . '/exe',  // old payment folder
    $base . '/landing', // landing page folder (empty, no PHP files)
];

// ─────────────────────────────────────────────────────────────────────────────
// Helper functions
// ─────────────────────────────────────────────────────────────────────────────
function rrmdir($dir) {
    if (!is_dir($dir)) return;
    $objects = scandir($dir);
    foreach ($objects as $object) {
        if ($object === "." || $object === "..") continue;
        $path = $dir . DIRECTORY_SEPARATOR . $object;
        if (is_dir($path)) rrmdir($path);
        else unlink($path);
    }
    rmdir($dir);
}

function deleteFile($path) {
    if (file_exists($path) && is_file($path)) {
        if (unlink($path)) return ['status' => 'deleted', 'path' => $path];
        else return ['status' => 'failed', 'path' => $path];
    }
    return ['status' => 'notfound', 'path' => $path];
}

function deleteDir($path) {
    if (is_dir($path)) {
        rrmdir($path);
        return ['status' => 'deleted', 'path' => $path];
    }
    return ['status' => 'notfound', 'path' => $path];
}

// Scan the filesystem and build a "what we found" list
$scan_results = [
    'root_files'   => [],
    'root_dirs'    => [],
    'admin1_files' => [],
    'temp_dirs'    => [],
];

foreach ($root_files_to_delete as $f) {
    $path = $base . '/' . $f;
    $scan_results['root_files'][] = [
        'name'   => $f,
        'path'   => $path,
        'exists' => file_exists($path),
        'size'   => file_exists($path) ? round(filesize($path) / 1024, 1) . ' KB' : '-',
    ];
}
foreach ($root_dirs_to_delete as $d) {
    $path = $base . '/' . $d;
    $scan_results['root_dirs'][] = [
        'name'   => $d,
        'path'   => $path,
        'exists' => is_dir($path),
    ];
}
foreach ($admin1_files_to_delete as $f) {
    $path = $base . '/admin1/' . $f;
    $scan_results['admin1_files'][] = [
        'name'   => $f,
        'path'   => $path,
        'exists' => file_exists($path),
        'size'   => file_exists($path) ? round(filesize($path) / 1024, 1) . ' KB' : '-',
    ];
}
foreach ($temp_folders_to_empty as $d) {
    $scan_results['temp_dirs'][] = [
        'name'   => basename(dirname($d)) . '/' . basename($d),
        'path'   => $d,
        'exists' => is_dir($d),
    ];
}

// Execute cleanup if confirmed
$cleanup_results = [];
$did_cleanup = false;

if ($token_ok && isset($_POST['action']) && $_POST['action'] === 'cleanup') {
    $did_cleanup = true;

    foreach ($root_files_to_delete as $f) {
        $cleanup_results[] = deleteFile($base . '/' . $f) + ['label' => "Root: $f"];
    }
    foreach ($root_dirs_to_delete as $d) {
        $cleanup_results[] = deleteDir($base . '/' . $d) + ['label' => "Root dir: $d"];
    }
    foreach ($admin1_files_to_delete as $f) {
        $cleanup_results[] = deleteFile($base . '/admin1/' . $f) + ['label' => "admin1: $f"];
    }
    // Empty temp folders (don't delete the folders themselves, just contents)
    foreach ($temp_folders_to_empty as $d) {
        if (is_dir($d)) {
            $files = glob($d . '/*');
            $count = 0;
            if ($files) {
                foreach ($files as $file) {
                    if (is_file($file)) { unlink($file); $count++; }
                }
            }
            $cleanup_results[] = [
                'status' => 'emptied',
                'path'   => $d,
                'label'  => basename(dirname($d)) . '/' . basename($d) . " ($count files removed)",
            ];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Karuda Cleanup</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #0f172a; color: #e2e8f0; padding: 30px; }
        .card { background: #1e293b; border-radius: 16px; padding: 40px; max-width: 900px; margin: 0 auto; box-shadow: 0 25px 50px rgba(0,0,0,0.5); }
        h1 { font-size: 26px; color: #f59e0b; margin-bottom: 8px; }
        h2 { font-size: 15px; color: #94a3b8; margin: 24px 0 12px; text-transform: uppercase; letter-spacing: 1px; }
        .sub { color: #64748b; font-size: 14px; margin-bottom: 24px; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; margin-bottom: 20px; }
        th { background: #0f172a; padding: 9px 12px; text-align: left; color: #64748b; font-weight: 600; }
        td { padding: 8px 12px; border-bottom: 1px solid #334155; }
        .exists { color: #f87171; font-weight: 600; }
        .missing { color: #475569; }
        .ok { color: #4ade80; }
        .btn { background: #dc2626; color: white; border: none; padding: 14px 32px; border-radius: 10px; font-size: 15px; font-weight: 600; cursor: pointer; }
        .btn:hover { background: #b91c1c; }
        .btn-safe { background: #1d4ed8; }
        .btn-safe:hover { background: #1e40af; }
        .alert { background: #78350f33; border: 1px solid #f59e0b; border-radius: 8px; padding: 16px; color: #fcd34d; margin: 20px 0; font-size: 14px; }
        .result { padding: 7px 12px; border-radius: 5px; margin: 3px 0; font-size: 13px; }
        .r-deleted { background: #14532d22; border-left: 3px solid #22c55e; color: #4ade80; }
        .r-failed { background: #7f1d1d22; border-left: 3px solid #ef4444; color: #f87171; }
        .r-notfound { background: #1e293b; border-left: 3px solid #475569; color: #64748b; }
        .r-emptied { background: #172554; border-left: 3px solid #3b82f6; color: #93c5fd; }
        .error-token { text-align: center; padding: 60px 20px; }
        .error-token h2 { color: #ef4444; font-size: 22px; margin-bottom: 12px; }
        code { background: #0f172a; padding: 2px 6px; border-radius: 4px; font-size: 12px; }
    </style>
</head>
<body>
<div class="card">
<?php if (!$token_ok): ?>
    <div class="error-token">
        <h2>🔒 Access Denied</h2>
        <p style="color:#94a3b8">Add <code>?token=karuda_cleanup2026</code> to the URL.</p>
    </div>
<?php elseif ($did_cleanup): ?>
    <h1>🗑️ Cleanup Complete</h1>
    <p class="sub">Results for each file/folder:</p>
    <?php foreach ($cleanup_results as $r): 
        $cls = 'r-' . $r['status'];
        $icon = $r['status'] === 'deleted' ? '✅' : ($r['status'] === 'emptied' ? '🧹' : ($r['status'] === 'notfound' ? '—' : '❌'));
    ?>
        <div class="result <?= $cls ?>"><?= $icon ?> <?= htmlspecialchars($r['label']) ?> — <?= $r['status'] ?></div>
    <?php endforeach; ?>
    <div class="alert" style="margin-top:24px">
        ⚠️ <strong>Remember:</strong> Delete <code>cleanup.php</code> and <code>seed_runner.php</code> manually from the server now.
    </div>
<?php else: ?>
    <h1>🗑️ Karuda Cleanup Tool</h1>
    <p class="sub">Review what will be deleted, then confirm. This action cannot be undone.</p>

    <div class="alert">
        ⚠️ Review the table carefully. Files marked <span class="exists">EXISTS</span> will be permanently deleted.
    </div>

    <h2>Root Files (aa/)</h2>
    <table>
        <tr><th>File</th><th>Size</th><th>Status</th></tr>
        <?php foreach ($scan_results['root_files'] as $f): ?>
        <tr>
            <td><?= htmlspecialchars($f['name']) ?></td>
            <td><?= $f['size'] ?></td>
            <td class="<?= $f['exists'] ? 'exists' : 'missing' ?>"><?= $f['exists'] ? 'EXISTS – will delete' : 'Not found' ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h2>Root Directories</h2>
    <table>
        <tr><th>Folder</th><th>Status</th></tr>
        <?php if (empty($scan_results['root_dirs'])): ?>
        <tr><td colspan="2" style="color:#475569">None configured for deletion</td></tr>
        <?php endif; ?>
        <?php foreach ($scan_results['root_dirs'] as $d): ?>
        <tr>
            <td><?= htmlspecialchars($d['name']) ?></td>
            <td class="<?= $d['exists'] ? 'exists' : 'missing' ?>"><?= $d['exists'] ? 'EXISTS – will delete' : 'Not found' ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h2>admin1/ Files</h2>
    <table>
        <tr><th>File</th><th>Size</th><th>Status</th></tr>
        <?php foreach ($scan_results['admin1_files'] as $f): ?>
        <tr>
            <td><?= htmlspecialchars($f['name']) ?></td>
            <td><?= $f['size'] ?></td>
            <td class="<?= $f['exists'] ? 'exists' : 'missing' ?>"><?= $f['exists'] ? 'EXISTS – will delete' : 'Not found' ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h2>Temp Folders (contents will be emptied)</h2>
    <table>
        <tr><th>Folder</th><th>Status</th></tr>
        <?php foreach ($scan_results['temp_dirs'] as $d): ?>
        <tr>
            <td><?= htmlspecialchars($d['name']) ?></td>
            <td class="<?= $d['exists'] ? 'exists' : 'missing' ?>"><?= $d['exists'] ? 'EXISTS – contents will be cleared' : 'Not found' ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <form method="POST" action="?token=<?= CLEANUP_TOKEN ?>" onsubmit="return confirm('Are you sure you want to permanently delete these files?')">
        <input type="hidden" name="action" value="cleanup">
        <button type="submit" class="btn">🗑️ Delete Listed Files Now</button>
        &nbsp;
        <a href="avadmin/index.php" class="btn btn-safe" style="display:inline-block; text-decoration:none; padding:14px 24px; border-radius:10px; color:white">← Back to Admin</a>
    </form>
<?php endif; ?>
</div>
</body>
</html>
