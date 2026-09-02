<?php 
include "dbconnect.php";
$name=$_POST['name'];
$designation=$_POST['designation'];
$message=$_POST['message'];

$query1="insert into testi values (null,'$name','$designation','$message',null)";
$result=mysqli_query($con,$query1);
if($result){
    echo"<script>alert('Inserted successfully');window.location.href='index.php';</script>";
}
else{
    echo"<script>alert('Not Inserted ');window.location.href='index.php';</script>";
}
?>