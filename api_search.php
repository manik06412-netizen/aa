<?php
/**
 * api_search.php
 * Real-time AJAX Auto-Suggest API for Karuda Computers
 */
header('Content-Type: application/json');
require_once __DIR__ . '/db_config.php';

$term = trim($_GET['q'] ?? $_GET['term'] ?? '');
if (strlen($term) < 1) {
    echo json_encode(['results' => [], 'categories' => [], 'count' => 0, 'query' => '']);
    exit;
}

$safe = mysqli_real_escape_string($con, $term);

// ── Fetch matching products ──
$sql = "SELECT d.d_id, d.rs_id, d.dish_name, d.category, d.brand_name, d.img,
               COALESCE(MIN(p.pp), 0) AS current_price,
               COALESCE(MIN(p.oprice), 0) AS old_price,
               COALESCE(SUM(p.total_stock), 0) AS total_stock
        FROM dishes d
        LEFT JOIN price p ON p.pcode = d.rs_id
        WHERE d.status = 1 
          AND (d.dish_name LIKE '%$safe%' OR d.category LIKE '%$safe%' OR d.brand_name LIKE '%$safe%' OR d.keywords LIKE '%$safe%' OR d.description LIKE '%$safe%')
        GROUP BY d.d_id
        ORDER BY 
          CASE 
            WHEN d.dish_name LIKE '$safe%' THEN 1
            WHEN d.dish_name LIKE '%$safe%' THEN 2
            WHEN d.category LIKE '%$safe%' THEN 3
            WHEN d.brand_name LIKE '%$safe%' THEN 4
            ELSE 5
          END,
          d.d_id DESC
        LIMIT 8";

$res = mysqli_query($con, $sql);
$results = [];

if ($res && mysqli_num_rows($res) > 0) {
    while ($row = mysqli_fetch_assoc($res)) {
        $p_id = !empty($row['rs_id']) ? $row['rs_id'] : $row['d_id'];
        $img_src = resolve_image_url($row['img']);
        $price = (float)$row['current_price'];
        $oprice = (float)$row['old_price'];
        $stock = (int)$row['total_stock'];

        $results[] = [
            'id' => $p_id,
            'name' => $row['dish_name'],
            'category' => $row['category'],
            'image' => $img_src,
            'price' => number_format($price, 2),
            'raw_price' => $price,
            'old_price' => $oprice > $price ? number_format($oprice, 2) : null,
            'stock' => $stock,
            'in_stock' => $stock > 0,
            'url' => 'details.php?id=' . urlencode($p_id)
        ];
    }
}

// ── Fetch matching categories ──
$categories = [];
$cat_sql = "SELECT c_id, c_name FROM res_category 
            WHERE c_name LIKE '%$safe%' 
            ORDER BY CASE WHEN c_name LIKE '$safe%' THEN 1 ELSE 2 END, c_name ASC 
            LIMIT 5";
$cat_res = mysqli_query($con, $cat_sql);
if ($cat_res && mysqli_num_rows($cat_res) > 0) {
    while ($cat_row = mysqli_fetch_assoc($cat_res)) {
        $categories[] = [
            'id'   => $cat_row['c_id'],
            'name' => $cat_row['c_name'],
            'url'  => 'category_list.php?search=' . urlencode($cat_row['c_name'])
        ];
    }
}

// ── Also collect unique categories from product results ──
if (empty($categories)) {
    $seen_cats = [];
    foreach ($results as $r) {
        if (!empty($r['category']) && !in_array($r['category'], $seen_cats)) {
            $seen_cats[] = $r['category'];
            $cat_safe = mysqli_real_escape_string($con, $r['category']);
            $categories[] = [
                'id'   => null,
                'name' => $r['category'],
                'url'  => 'category_list.php?search=' . urlencode($r['category'])
            ];
        }
        if (count($categories) >= 4) break;
    }
}

echo json_encode([
    'results'    => $results,
    'categories' => $categories,
    'count'      => count($results),
    'query'      => $term
]);
exit;
