<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/inc/config.php';
if (!isset($_SESSION["admin1_user"])) {
    echo json_encode(['status' => 0, 'message' => 'Unauthorized']);
    exit;
}
header('Content-Type: application/json');
error_reporting(0);
$order_id = trim($_POST['id_name'] ?? '');

$right_side = '';
$left_side = '';
$today = date('Y-m-d');
function Qty_of_1($con, $where) {
    $where = mysqli_real_escape_string($con, $where);
    $query = "SELECT SUM(qty) AS totals FROM final WHERE order_id = '$where'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['totals'] ?? 0;
}

$query ="SELECT final.*, chekout.*, order_sts.* FROM final 
    INNER JOIN chekout ON final.refid = chekout.ref_id 
    INNER JOIN order_sts ON final.order_id = order_sts.order_id 
    INNER JOIN ( 
        SELECT order_id, MAX(status) AS max_status 
        FROM order_sts 
        GROUP BY order_id 
        HAVING MAX(status) <= 6 
    ) AS max_status_table 
    ON order_sts.order_id = max_status_table.order_id 
    AND order_sts.status = max_status_table.max_status 
    WHERE final.status = '0' 
    AND final.order_id = '$order_id' 
    GROUP BY final.order_id 
    ORDER BY max_status_table.max_status DESC, final.id DESC";

$sql = mysqli_query($con, $query);

if(mysqli_num_rows($sql)) {
    $orders = mysqli_fetch_array($sql);
    $right_side = '
    <table class="table table-bordered table-hover table-striped">
        <tr>
            <th style="width:30%">Order Id</th>
            <th style="width:30%">Items</th>
            <th style="width:30%">Amount</th>
        </tr>
        <tr>
            <td>#'.$orders['order_id'].'</td>
            <td>'. Qty_of_1($con, $orders['order_id']).'</td>
            <td>₹'.$orders['final_amt'].'</td>
        </tr>
    </table>';
}

$order_status_find = mysqli_query($con, "SELECT * FROM order_sts WHERE order_id = '$order_id'");

$status_values = [
    0 => 'Order Placed', 
    1 => 'Order Processed', 
    2 => 'Order Packing', 
    3 => 'Order Delivered',
    4 => '',
    5 => '',
    6 => ''
];

$left_side = '<table class="table">';
$delivery_status = null;

$most_recent_status = null;
$date = '';
$status_display = '';
while ($ord_val_find = mysqli_fetch_array($order_status_find)) {
    $status = $ord_val_find['status'];
    $status_date = $ord_val_find['sts_date'];
    $status_time = $ord_val_find['sts_time'];
    if ($date == '') {
        $dateParts = explode('-', $status_date);
        $formattedDate = $dateParts[2] . '-' . $dateParts[1] . '-' . $dateParts[0];
        $date = $formattedDate;
    }
    $most_recent_status = $status;
    $status_display = $status == 5 ? 'Order Cancelled' : ($status == 6 ? 'Order Refunded' : $status_values[$status]);
    $left_side .= '<tr>
    
        <td><b>' . $status_display. '</b></td>
        <td>' . $status_date . '</td>
        <td>' . $status_time . '</td>
        <td><input type="checkbox" checked disabled></td>
    </tr>';
}

 if ($most_recent_status != 3 && $most_recent_status != 6) {
    $next_status = $most_recent_status + 1;

    $dropdown = '<select class="form-control" name="order_status" required>';
    foreach ($status_values as $value => $label) {
        if($label == ''){
            continue;
        }
        if ($value == $next_status) {
            $dropdown .= "<option value=\"$value\" selected>$label</option>";
        } else {
            $dropdown .= "<option value=\"$value\"" . ($value == $next_status ? ' selected' : ' disabled') . ">$label</option>";
        }
    }
    if($most_recent_status != 3 && $most_recent_status != 5){
        $dropdown .= "<option value=\"5\" > Order Cancelled</option>";
    }else{
        $dropdown .= "<option value=\"5\" disabled> Order Cancelled</option>";
    }
    if($most_recent_status == 5){
        $dropdown .= "<option value=\"6\" > Order Refund</option>";
    }else{
        $dropdown .= "<option value=\"6\" disabled> Order Refund</option>";
    }
    $dropdown .= '</select>';
    $today = date('Y-m-d'); 
    $current_time = date('H:i'); 
    $left_side .= '<tr>
        <td><b>' . $dropdown . '</b></td>
     
        <td><input type="date" min="' . htmlspecialchars($today) . '" max="' . htmlspecialchars($today) . '" class="form-control" name="date_" value="' . htmlspecialchars($today) . '" required></td>
    <td><input type="time" class="form-control" name="time_" value="' . htmlspecialchars($current_time) . '" required></td>
        <td></td>
    </tr>';
 }else{
    $delivery_status = 3;
 }

$left_side .= '</table>';

echo json_encode(['status'=>1,'right_side'=>$right_side,'left_side'=>$left_side,'delivery_Status'=>$delivery_status]);
?>