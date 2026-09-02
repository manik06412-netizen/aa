<?php 
session_start();
include('dbconnect.php');
$userid=$_SESSION['uid'];
$product=$_GET['id'];
$priceid=$_GET['price'];
$delete = mysqli_query($con, "DELETE FROM card WHERE userid='$userid' AND p_id='$product' AND status = '0'");
if($delete){
    echo"<script>window.location.href='shopping_cart.php';</script>";
}
else{
    echo"<script>window.location.href='shopping_cart.php';</script>";
}


?>