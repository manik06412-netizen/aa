<?php
include("../config.php");
// error_reporting(0);
session_start();


// sending query
$cate = $_GET['cat_del'];
$table = $_GET['table'];
$path = $_GET['path'];
$del = mysqli_query($con,"DELETE FROM $table WHERE id = '{$cate}'");
if($del){
    echo"<script>alert('DELETED SUCCESS');window.location.href='$path.php';</script>";
}else{
    echo"<script>alert('ERROR');window.location.href='$path.php';</script>";
}

?>
