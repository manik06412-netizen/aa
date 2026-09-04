<?php 
session_start();
include('../dbconnect.php');
$id=$_GET['id'];
$menu_upd=$_GET['menu_upd'];
$upd=mysqli_query($con,"UPDATE dishes set status='$id' where rs_id='$menu_upd'");
if($upd){
    echo"<script>window.location.href='deals_view.php';</script>";
}
?>