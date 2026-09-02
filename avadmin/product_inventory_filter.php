<?php 
session_start();
error_reporting(0);
include('./inc/config.php');

$month_find = '';
$year_find = '';
$product_find = '';
$response = '';
$ifAll_empty ='';

if (!empty($_POST['month'])) {
    $month = trim($_POST['month']);
    $month_find = "AND MONTH(STR_TO_DATE(date_inv, '%d-%m-%Y')) = " . $month;
}

if (!empty($_POST['yearSelect'])) {
    $year = trim($_POST['yearSelect']);
    $year_find = "AND YEAR(STR_TO_DATE(date_inv, '%d-%m-%Y')) = " . intval($year);    
}

if (!empty($_POST['product_id'])) {
    $product = trim($_POST['product_id']);
    $product_find = 'AND prd_id = "' .$product . '"';
}

if(empty($_POST['month'])){
 $ifAll_empty  =" , mnt_inv ORDER BY mnt_inv DESC";
}



function Products_measure($con, $pro, $price) {
    $stmt = $con->prepare("SELECT dish_name, qn, wg, img FROM price INNER JOIN dishes ON dishes.rs_id = price.pcode WHERE  pcode = ? AND id = ?");
    
    if (!$stmt) {
        die("Prepare failed: " . $con->error);
    }

    $stmt->bind_param("si", $pro, $price);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        return $result->fetch_assoc();
    } else {
        die("Query failed: " . $con->error);
    }
}


$query = "SELECT prd_id, price_id, mnt_inv, SUM(open_stk) AS tot_open_stk, SUM(close_stk) AS tot_close_stk
          FROM stock_invent 
          WHERE (open_stk IS NOT NULL OR close_stk IS NOT NULL) 
          $month_find $year_find $product_find 
          GROUP BY price_id, prd_id $ifAll_empty";

$sql = mysqli_query($con, $query);

    while ($orders = mysqli_fetch_assoc($sql)) { 
        $prod_id = trim($orders['prd_id']);
        $price_of =  trim($orders['price_id']);
        $rows_ = Products_measure($con, $prod_id , $price_of);
        $response .= '<tr>
            <td>' . htmlspecialchars($orders['mnt_inv']) . '</td>
            <td><img src="'.$rows_['img'] .'" style="width:70px; height: 70px; " ></td>
            <td>'. $rows_['dish_name']. '<br>' . htmlspecialchars($orders['prd_id']) . '</td>
            <td>' . htmlspecialchars($rows_['qn'] . "-" . $rows_['wg']) . '</td>
            <td style="text-align: left;">' . htmlspecialchars($orders['tot_open_stk']) . '</td>
            <td style="text-align: left;">' . htmlspecialchars($orders['tot_close_stk']) . '</td>
        </tr>';
    }

echo json_encode(['status' => 1, 'response' => $response]);
?>