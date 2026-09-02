<?php 
session_start();
error_reporting(0);
include("inc/config.php");
date_default_timezone_set('Asia/Kolkata');
$currentDateTime = new DateTime();
$formattedTime = $currentDateTime->format('g:i a');

function CHECK_STATUS($con, $id, $status){
    $select = mysqli_query($con,"SELECT * FROM order_sts WHERE order_id = '$id' AND status =$status");
    if(mysqli_num_rows($select)){
        return true;
    }else{
        return false;
    }
}
function INSERT_STATUS($con, $id, $mgs, $date, $formattedTime, $status){
    $insert = mysqli_query($con,"INSERT INTO order_sts VALUES(null,'$id','$mgs', '$date', '$formattedTime', $status)");
    if($insert){
        return true;
    }else{
        return false;
    }
}
if($_POST['order_id']){


    $order_id = $_POST['order_id'];
    $order_status = $_POST['order_status'];
    $mgs= $_POST['Message'] ?? '';
    $date = $_POST['date_'];
    $time = $_POST['time_'];
    $dateObj = new DateTime($date);
    $formattedDate = $dateObj->format('d-m-Y');
    // $date = date('d-m-Y');

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