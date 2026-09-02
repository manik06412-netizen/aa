<?php 

include "../dbconnect.php";
$id=$_GET['id'];
$query="delete from cust_reviews where uid='$id'";
$result=mysqli_query($con,$query);
if($result){
    echo "<script>alert('delete successfully');window.location.href='productreview.php';</script>";
}
else{
    echo "<script>alert('failed to deleted');window.location.href='productreview.php';</script>";
}
?>
