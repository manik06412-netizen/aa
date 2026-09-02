<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/inc/config.php';
if (!isset($_SESSION["admin1_user"])) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 0, 'message' => 'Unauthorized']);
    exit;
}

header('Content-Type: application/json');
error_reporting(0);

$month_find = '';
$year_find = '';
$product_find = '';
$response = '';
$ifAll_empty ='';

if (!empty($_POST['month'])) {
    $month = (int)$_POST['month'];
    $month_find = "AND MONTH(STR_TO_DATE(date_inv, '%d-%m-%Y')) = " . $month;
}

if (!empty($_POST['yearSelect'])) {
    $year = intval($_POST['yearSelect']);
    $year_find = "AND YEAR(STR_TO_DATE(date_inv, '%d-%m-%Y')) = " . $year;    
}

if (!empty($_POST['product_id'])) {
    $product = mysqli_real_escape_string($con, trim($_POST['product_id']));
    $product_find = 'AND prd_id = "' .$product . '"';
}

if(empty($_POST['month'])){
 $ifAll_empty  =" , mnt_inv ORDER BY mnt_inv DESC";
}

function Products_measure($con, $pro, $price) {
    $stmt = $con->prepare("SELECT dish_name, qn, wg, img FROM price INNER JOIN dishes ON dishes.rs_id = price.pcode WHERE pcode = ? AND id = ?");
    if (!$stmt) {
        return null;
    }
    $stmt->bind_param("si", $pro, $price);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result ? $result->fetch_assoc() : null;
}

$query = "SELECT prd_id, price_id, mnt_inv, SUM(open_stk) AS tot_open_stk, SUM(close_stk) AS tot_close_stk
          FROM stock_invent 
          WHERE (open_stk IS NOT NULL OR close_stk IS NOT NULL) 
          $month_find $year_find $product_find 
          GROUP BY price_id, prd_id $ifAll_empty";

$sql = mysqli_query($con, $query);

if ($sql && mysqli_num_rows($sql) > 0) {
    while ($orders = mysqli_fetch_assoc($sql)) { 
        $prod_id = trim($orders['prd_id'] ?? '');
        $price_of =  trim($orders['price_id'] ?? '');
        $rows_ = Products_measure($con, $prod_id , $price_of) ?? [];
        $img_src = !empty($rows_['img']) ? htmlspecialchars($rows_['img']) : 'Res_img/no_image.png';
        if (strpos($img_src, 'img/') === 0) {
            $img_src = '../' . $img_src;
        }
        $dish_title = !empty($rows_['dish_name']) ? htmlspecialchars($rows_['dish_name']) : ('Product #' . htmlspecialchars($prod_id));
        $measure_text = (!empty($rows_['qn']) ? htmlspecialchars($rows_['qn']) : '') . (!empty($rows_['wg']) ? (' - ' . htmlspecialchars($rows_['wg'])) : '');

        $response .= '<tr>
            <td>' . htmlspecialchars($orders['mnt_inv'] ?? '') . '</td>
            <td><img src="'.$img_src .'" style="width:46px; height: 46px; object-fit:cover; border-radius:8px; border: 1px solid #e2e8f0;" onerror="this.onerror=null; this.src=\'Res_img/no_image.png\';"></td>
            <td>'. $dish_title . '<br><small class="text-muted">#' . htmlspecialchars($prod_id) . '</small></td>
            <td>' . htmlspecialchars($measure_text ?: 'Default') . '</td>
            <td style="text-align: left;">' . htmlspecialchars($orders['tot_open_stk'] ?? 0) . '</td>
            <td style="text-align: left;">' . htmlspecialchars($orders['tot_close_stk'] ?? 0) . '</td>
        </tr>';
    }
} else {
    $response = "<tr><td colspan='6' style='text-align:center;'>No records found for the selected filter</td></tr>";
}

echo json_encode(['status' => 1, 'response' => $response]);
?>