<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/inc/config.php';
if (!isset($_SESSION["admin1_user"])) {
    echo json_encode(['status' => 0, 'message' => 'Unauthorized']);
    exit;
}
header('Content-Type: application/json');
error_reporting(0);
date_default_timezone_set('Asia/Kolkata');
$currentDateTime = new DateTime();
$formattedTime = $currentDateTime->format('g:i a');

function CHECK_STATUS($con, $id, $status){
    $id = mysqli_real_escape_string($con, $id);
    $status = (int)$status;
    $select = mysqli_query($con,"SELECT * FROM order_sts WHERE order_id = '$id' AND status = $status");
    return (mysqli_num_rows($select) > 0);
}
function INSERT_STATUS($con, $id, $mgs, $date, $formattedTime, $status){
    $id = mysqli_real_escape_string($con, $id);
    $mgs = mysqli_real_escape_string($con, $mgs);
    $date = mysqli_real_escape_string($con, $date);
    $formattedTime = mysqli_real_escape_string($con, $formattedTime);
    $status = (int)$status;
    $insert = mysqli_query($con,"INSERT INTO order_sts VALUES(null,'$id','$mgs', '$date', '$formattedTime', $status)");
    return (bool)$insert;
}

if (!empty($_POST['order_id'])) {
    $order_id = trim($_POST['order_id']);
    $order_status = isset($_POST['order_status']) ? (int)$_POST['order_status'] : 0;
    $mgs = $_POST['Message'] ?? '';
    
    $date = !empty($_POST['date_']) ? $_POST['date_'] : date('Y-m-d');
    $time = !empty($_POST['time_']) ? $_POST['time_'] : date('H:i');
    
    $dateObj = new DateTime($date);
    $formattedDate = $dateObj->format('d-m-Y');

    if(INSERT_STATUS($con, $order_id, $mgs, $formattedDate, $time, $order_status) == true){
        echo json_encode(['status'=>1]);
    }else{
        echo json_encode(['status'=>2]);
    }

 

if($order_status==5)
{
$select_prd=mysqli_query($con,"select * from final where order_id='$order_id'");
while($fin_res=mysqli_fetch_array($select_prd)){
$fn_prd_id=$fin_res['product_id'];
$fn_pric_id=$fin_res['price_id'];
$fn_qty=$fin_res['qty'];


$dd = mysqli_query($con,"SELECT * from prd_stock where pd_code='$fn_prd_id' AND price_id = $fn_pric_id order by id desc");

if($d1=mysqli_fetch_array($dd)){
    $qn=$d1['total'];
    $balance_qty = $qn + $fn_qty;
    if($balance_qty  <= 0){
        $stock_status ='Currently Unavailable';
    }else{ $stock_status = 'Instock'; }

    $purchased = $fn_qty . 'Cancelled';

    $stock = mysqli_query($con, "INSERT INTO prd_stock (pd_code, price_id, prev_stock, new_stock,total, date, note,stk_status) VALUES('$fn_prd_id',$fn_pric_id, $qn, $fn_qty, $balance_qty, '$date', '$purchased','$stock_status')");
}

$query = mysqli_query($con, "SELECT * FROM stock_invent WHERE prd_id='$fn_prd_id' AND price_id='$fn_pric_id' order by id desc ");
if ($query2 = mysqli_fetch_array($query)) {
    $tot_stk = $query2['tot_stk'];
    $balance_qty1 = $tot_stk + $fn_qty;

    $dateString = date('d-m-Y');
    $dates = new DateTime($dateString);
    $formattedDate = $dates->format('F-Y');

$stock2 = mysqli_query($con, "INSERT INTO stock_invent(prd_id, price_id, in_stk ,tot_stk, date_inv,mnt_inv) VALUES('$fn_prd_id', $fn_pric_id,  $fn_qty, $balance_qty1, '$date','$formattedDate')");

}
}
               }


   
}
?>