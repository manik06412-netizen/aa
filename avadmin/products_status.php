<?php
ob_start();
session_start();
error_reporting(0);
include("inc/config.php");

if(isset($_GET['del_id'])){
$p_id = trim($_GET['del_id']);

mysqli_query($con,"DELETE FROM dishes WHERE d_id = $p_id");
header("location:products_list.php");  

}

if(isset($_GET['status_id'])){
    $status_id = trim($_GET['status_id']);
    $sql=mysqli_query($con,"SELECT * FROM dishes WHERE d_id = $status_id");

    if($result = mysqli_fetch_array($sql)){
        $cur_status = $result['status'];
        if($cur_status === 3){
            echo "<script>alert('this is scheduled product!');window.location.href='products_list.php';</script>";
        }else{
        $update_status = $cur_status == 1 ? 2 : 1;

        $update = mysqli_query($con,"UPDATE dishes set status = $update_status WHERE d_id = $status_id"); 
        header("location:products_list.php");  
        }
    } 
}