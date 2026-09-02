<?php
session_start();
error_reporting(0);
include("dbconnect.php");
    $uid = $_SESSION['uid'];
    $prd_id1 = $_POST['productId'];
     $quantity1 = $_POST['quantity'];

    $price_id1=$_POST['price'];
   $date = date("d/m/Y");
  $rfid=rand(10,100).time();
   $status = 0;

   $ss= mysqli_query($con,"select * from price where id='$price_id1'");
    if($prow=mysqli_fetch_array($ss)){ 
          $corrent_price1=$prow['pp'];
         $old_price_modify1=$prow['oprice'];
         $get_per1=$prow['gst'];
   $dis=$prow['discount'];
  
    }

    
    $select2=mysqli_query($con,"select * from dishes where rs_id= '$prd_id1'");
    if($prow2=mysqli_fetch_array($select2)){
   
          $prd_name1=$prow2['dish_name'];
       $imgs1=$prow2['img'];
       $ship=$prow2['deliv_opt'];
    }
    if($ship=='Delivery Charge'){
        $ship_chg=1;
    }else{
        $ship_chg=0;
    }

    $subtot1 = $corrent_price1* $quantity1;
     $discount_amount = ($subtot1 *  $dis) / 100;
    $subtot2 = $subtot1 - $discount_amount;
    $gst_amount = ($subtot2 * $get_per1) / 100;
     $subtot = $subtot2 + $gst_amount;

        $insert = mysqli_query($con, "INSERT INTO chekout VALUES (null,'$rfid','$uid','$prd_id1','$price_id1','$prd_name1','$imgs1','$quantity1','$get_per1', '$corrent_price1' ,'$old_price_modify1', '$subtot', '$date','$status','$gst_amount','$discount_amount',$quantity1,'$subtot1',$ship_chg)");
    
  if ($insert) {

    function encrypt($data, $key) {
        $method = 'AES-256-CBC';
        
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));
        
        $encrypted = openssl_encrypt($data, $method, $key, 0, $iv);
        
        return base64_encode($encrypted . '::' . $iv);
    }
    $key = '23232322114432'; 
    $data = $rfid;
    $encrypted_data = encrypt($data, $key);

    echo json_encode(['status' => 1, 'redirect' => "checkout.php?refid=$encrypted_data"]);
} else {
    echo json_encode(['status' => 2]);
}
?>