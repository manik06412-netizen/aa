<?php

include '../config.php';
$s1=$_GET['id'];
$sql="update final set status=2 where order_id='$s1' ";
if($query=mysqli_query($con,$sql)){
    echo "<script>window.location.href='order.php'</script>";
}

?>