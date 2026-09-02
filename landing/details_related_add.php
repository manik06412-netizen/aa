<?php 
session_start();
$userid=$_SESSION['uid'];
include("dbconnect.php");
$qty=$_POST['qty'];
$prd=$_POST['prdid'];
$price=$_POST['pid'];

$adck=mysqli_query($con,"SELECT * FROM card where userid='$userid' and p_id='$prd' and pric_id='$price' and status='0'");
if(mysqli_num_rows($adck)){
            echo"<script>alert('Product Already Added In Your Card!');window.location.href='details.php?id=$prd';</script>";
}else{
$sel=mysqli_query($con,"SELECT * FROM price where id='$price'");
if($ro=mysqli_fetch_array($sel)){
    $amt=$ro['pp'];
  $gst=$ro['gst'];
}
$amt1=$amt * $qty; 
 $gst1=$amt1 +( $amt1 * ($gst / 100));

$date=date('d/m/Y');
$sta=0;
$insert=mysqli_query($con,"INSERT INTO card values(null,'$userid','$prd','$price','$qty','$amt1','$gst','$gst1','$date','$sta')");
if($insert){
        echo"<script>alert('hooray ! 1 item added to the card!');window.location.href='details.php?id=$prd';</script>";
}
}

?>