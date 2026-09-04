<?php
session_start();
error_reporting(0);
include("config.php");

    $uid = $_POST['userid'];
    $prd_id = $_POST['prd_id'];
    $quantity = $_POST['pro_qty'];
    $corrent_price=$_POST['corrent_price'];
    $subtot = $_POST['subtot'];
    $prd_name=$_POST['pro_name'];
    $get_per = $_POST['get_per'];
    $imgs=$_POST['imgs'];
    $old_price_modify=$_POST['old_price_modify'];
    $date = date("d/m/Y");
    $rfid=rand(10,100).time();
    $status = 0;
    for ($i = 0; $i < count($prd_id); $i++) {
        $imgs1=$imgs[$i];
        $prd_id1 = $prd_id[$i];
        $quantity1 = $quantity[$i];
        $prd_name1 = $prd_name[$i];
        $old_price_modify1=$old_price_modify[$i];
        $corrent_price1=$corrent_price[$i];
        $get_per1 = $get_per[$i];
        $insert = mysqli_query($con, "INSERT INTO chekout VALUES (null,'$rfid','$uid','$prd_id1','$prd_name1','$imgs1','$quantity1','$get_per1', '$corrent_price1' ,'$old_price_modify1', '$subtot', '$date','$status')");
    }
    if ($insert) {
        echo "<script>window.location.href='checkout.php?refid=$rfid';</script>";
    } else {
        
    }
?>








<?php
// session_start();
// include("dbconnect.php");
//     $uid = $_POST['userid'];
//     $prd_id = $_POST['prd_id'];
//     $quantity = $_POST['pro_qty'];
//     $price_id=$_POST['price_id'];
//     $corrent_price=$_POST['current_price'];
//     $tot = $_POST['tot'];
//     $prd_name=$_POST['pro_name'];
//     $get_per = $_POST['get_per'];
//     $imgs=$_POST['imgs'];
//    $old_price=$_POST['old_price'];
//        $date = date("d/m/Y");
//     $rfid=rand(10,100).time();
//     $transid=rand(6,1000).time();
//     $flex=$_POST['flex'];
//     $insert = mysqli_query($con, "INSERT INTO chekout (ref_id, userid, pr_id, price_id, p_name, product_img, qty, gstper, ct_py, old_prc, total) 
//     VALUES ('$rfid', '$uid', '$prd_id', '$price_id', '$prd_name', '$imgs', '$quantity', '$get_per', '$corrent_price', '$old_price', '$tot')");
        
//     if ($insert) {
//         echo "<script>window.location.href='checkout.php?refid=$rfid&flex=$flex';</script>";
//     } else {
        
//     }

?>
