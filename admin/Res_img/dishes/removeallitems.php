<?php
include('dbconnect.php');
$uid = $_GET['userid'];

$sql = "delete FROM card where userid='$uid' AND status='0'";

if(mysqli_query($con,$sql)){
    echo"<script>window.location.href='shopping_cart.php';</script>";
}

?>